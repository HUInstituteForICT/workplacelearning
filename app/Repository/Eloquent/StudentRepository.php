<?php

declare(strict_types=1);

namespace App\Repository\Eloquent;

use App\AccessLog;
use App\Cohort;
use App\EducationProgram;
use App\Student;
use App\Tips\Models\StudentTipView;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class StudentRepository
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
    public function search(array $filters = [], array $orderBy = ['studentnr', 'ASC'], ?int $pages = 25)
    {
        $builder = Student::query();
        $filterClosures = $this->getFilterClosures();
        $allowedFilters = $this->getSearchFilters();

        /*
         * For each filter
         * - Check if value is not empty
         * - Check if allowed
         *
         * Then apply filter by finding the closure of the filter's type and call that on the query builder
         */
        array_walk($filters, function (?string $filterValue, string $filterName) use (
            &$builder,
            &$filterClosures,
            &$allowedFilters
        ): void {
            if (!$filterValue || !array_key_exists($filterName, $allowedFilters)) {
                return;
            }

            $filterOptions = $allowedFilters[$filterName];

            // If the filter has a specific query, use that instead of the generic by type
            if (isset($filterOptions['filterClosure'])) {
                $filterOptions['filterClosure']($builder, $filterName, $filterValue);

                return;
            }

            $type = $filterOptions['type'];

            if (!isset($filterClosures[$type])) {
                return;
            }

            $filterClosures[$type]($builder, $filterName, $filterValue);
        });
//
//        /** @var Builder $queryBuilder */
//        $queryBuilder = Student::where(function (Builder $builder) use ($filters): void {
//            $queryFilters = [];
//            array_walk($filters, function (?string $filterValue, string $filterName) use (&$queryFilters): void {
//                if (!$filterValue || !in_array($filterName, $this->allowedSearchFilters, true)) {
//                    return;
//                }
//
//                $queryFilters[] = [$filterName, 'LIKE', '%' . $filterValue . '%'];
//            });
//
//            $builder->where($queryFilters);
//        });

        $builder->orderBy($orderBy[0], $orderBy[1] ?? 'ASC');

        if ($pages) {
            return $builder->paginate($pages);
        }

        return $builder->get();
    }

    protected function getFilterClosures(): array
    {
        return [
            'text'   => static function (Builder $builder, string $property, string $value): void {
                $builder->where($property, 'like', '%'.$value.'%');
            },
            'select' => static function (Builder $builder, string $property, string $value): void {
                // Try to account for "no selection"
                if (!$value || (int) $value === -1) {
                    return;
                }
                $builder->where($property, $value);
            },
        ];
    }

    public function getSearchFilters(): array
    {
        return [
            'studentnr' => [
                'translate_key' => 'studentnr',
                'type'          => 'text',
            ],
            'firstname' => [
                'translate_key' => 'firstname',
                'type'          => 'text',
            ],
            'lastname'  => [
                'translate_key' => 'lastname',
                'type'          => 'text',
            ],
            'email'     => [
                'translate_key' => 'email',
                'type'          => 'text',
            ],
            'userlevel' => [
                'translate_key' => 'role',
                'type'          => 'select',
                'options'       => [
                    0 => 'Student',
                    1 => 'Teacher',
                    2 => 'Admin',
                ],
            ],
            'ep_id'     => [
                'translate_key' => 'education_programme',
                'type'          => 'select',
                'options'       => EducationProgram::all()->reduce(static function (
                    array $carry,
                    EducationProgram $educationProgram
                ) {
                    $carry[$educationProgram->ep_id] = $educationProgram->ep_name;

                    return $carry;
                }, []),
            ],
            'cohort'    => [
                'translate_key' => 'cohort',
                'type'          => 'select',
                'options'       => Cohort::orderBy('name', 'ASC')->get()->reduce(static function (
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
                'filterClosure' => static function (Builder $builder, string $property, string $value): void {
                    // Try to account for "no selection"
                    if (!$value || (int) $value === -1) {
                        return;
                    }

                    // Join the WPLP on the student
                    // add where clause for the cohort id
                    $builder
                        ->leftJoin('workplacelearningperiod', 'student.student_id', '=', 'workplacelearningperiod.student_id')
                        ->leftJoin('usersetting', 'student.student_id', '=', 'usersetting.student_id')
                        ->where('setting_label', 'active_internship')
                        ->whereRaw('workplacelearningperiod.wplp_id = usersetting.setting_value')
                        ->where('workplacelearningperiod.cohort_id', $value);
                },
            ],
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
