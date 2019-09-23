<?php

declare(strict_types=1);

namespace App\Repository\Eloquent;

use App\AccessLog;
use App\Student;
use App\Tips\Models\StudentTipView;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class StudentRepository
{
    private $allowedSearchFilters = [
        'studentnr',
        'firstname',
        'lastname',
        'email',
    ];

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
        /** @var Builder $queryBuilder */
        $queryBuilder = Student::where(function (Builder $builder) use ($filters): void {
            $queryFilters = [];
            array_walk($filters, function (?string $filterValue, string $filterName) use (&$queryFilters): void {
                if (!$filterValue || !in_array($filterName, $this->allowedSearchFilters, true)) {
                    return;
                }

                $queryFilters[] = [$filterName, 'LIKE', '%'.$filterValue.'%'];
            });

            $builder->where($queryFilters);
        });

        $queryBuilder->orderBy($orderBy[0], $orderBy[1] ?? 'ASC');

        if ($pages) {
            return $queryBuilder->paginate($pages);
        }

        return $queryBuilder->get();
    }

    public function getSearchFilters(): array
    {
        return $this->allowedSearchFilters;
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
