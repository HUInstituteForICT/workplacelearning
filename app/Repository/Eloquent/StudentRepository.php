<?php

declare(strict_types=1);

namespace App\Repository\Eloquent;

use App\AccessLog;
use App\Cohort;
use App\EducationProgram;
use App\Repository\Searchable;
use App\Repository\SearchFilter;
use App\Student;
use App\Tips\Models\StudentTipView;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class StudentRepository implements Searchable
{
    /**
     * @var WorkplaceLearningPeriodRepository
     */
    private $workplaceLearningPeriodRepository;

    public function __construct(WorkplaceLearningPeriodRepository $workplaceLearningPeriodRepository)
    {
        $this->workplaceLearningPeriodRepository = $workplaceLearningPeriodRepository;
    }

    public function get(int $id): Student
    {
        return Student::findOrFail($id);
    }

    public function save(Student $student): bool
    {
        return $student->save();
    }

    public function findByEmailOrCanvasId(string $email, string $canvasUserId): ?Student
    {
        $student = Student::where('email', '=', $email)->first();

        if ($student === null) {
            $student = Student::where('canvas_user_id', '=', $canvasUserId)->first();
        }

        return $student;
    }

    /**
     * @return LengthAwarePaginator|Collection
     */
    public function search(array $filters = [], ?int $pages = 25, array $relations = [])
    {
        /** @var Builder $builder */
        $builder = Student::sortable('studentnr')->with($relations);
        $allowedFilters = $this->getSearchFilters();

        /*
         * For each search filter, check if the user has it enabled
         * If so, apply it
         */
        array_walk($allowedFilters, static function (SearchFilter $searchFilter) use ($filters, $builder) {
            // Skip filters that aren't enabled by the user
            if (!array_key_exists($searchFilter->getProperty(), $filters)) {
                return;
            }

            $searchFilter->applyFilter($builder, $filters[$searchFilter->getProperty()]);
        });

//        $builder->orderBy($orderBy[0], $orderBy[1] ?? 'ASC');

        if ($pages) {
            return $builder->paginate($pages);
        }

        return $builder->get();
    }

    public function getSearchFilters(): array
    {
        return [
            new SearchFilter('studentnr'),
            new SearchFilter('firstname'),
            new SearchFilter('lastname'),
            new SearchFilter('email'),
            new SearchFilter('userlevel', SearchFilter::TYPE_SELECT, 'role', [
                0 => 'Student',
                1 => 'Teacher',
                2 => 'Admin',
            ]),

            new SearchFilter('ep_id', SearchFilter::TYPE_SELECT, 'education_programme',
                EducationProgram::all()->reduce(static function (
                    array $carry,
                    EducationProgram $educationProgram
                ) {
                    $carry[$educationProgram->ep_id] = $educationProgram->ep_name;

                    return $carry;
                }, [])),

            new SearchFilter(
                'cohort',
                SearchFilter::TYPE_SELECT,
                null,
                Cohort::orderBy('name', 'ASC')
                    ->get()
                    ->reduce(static function (
                        array $carry,
                        Cohort $cohort
                    ) {
                        if ($cohort->disabled) {
                            $carry['disabled'][$cohort->id] = $cohort->name;
                        } else {
                            $carry['enabled'][$cohort->id] = $cohort->name;
                        }

                        return $carry;
                    }, ['enabled' => [], 'disabled' => []]),

                static function (Builder $builder, string $value): void {
                    // Try to account for "no selection"
                    if (!$value || (int) $value === -1) {
                        return;
                    }

                    // We get the cohorts through the following relation:
                    // student -> WPLPs -> usersettings for active WPLP -> cohort_id
                    $builder
                        ->leftJoin('workplacelearningperiod', 'student.student_id', '=',
                            'workplacelearningperiod.student_id')
                        ->leftJoin('usersetting', 'student.student_id', '=', 'usersetting.student_id')
                        ->where('setting_label', 'active_internship')
                        ->whereRaw('workplacelearningperiod.wplp_id = usersetting.setting_value')
                        ->where('workplacelearningperiod.cohort_id', $value);
                }
            ),
        ];
    }

    public function delete(Student $student): void
    {
        DB::transaction(function () use ($student) {
            $student->deadlines()->delete();

            foreach ($student->workplaceLearningPeriods as $workplaceLearningPeriod) {
                $workplace = $workplaceLearningPeriod->workplace;
                $this->workplaceLearningPeriodRepository->delete($workplaceLearningPeriod);
                $workplace->delete();
            }

            $student->usersettings()->delete();

            AccessLog::whereStudentId($student->student_id)->delete();
            StudentTipView::whereStudentId($student->student_id)->delete();

            DB::delete('DELETE FROM password_reset WHERE email = ?', [$student->email]);

            $student->delete();
        });
    }
}
