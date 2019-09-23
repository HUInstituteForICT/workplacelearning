<?php

declare(strict_types=1);

namespace App\Policies;

use App\Student;
use App\WorkplaceLearningPeriod;
use Illuminate\Auth\Access\HandlesAuthorization;

class WorkplaceLearningPeriodPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the workplace learning period.
     */
    public function view(Student $student, WorkplaceLearningPeriod $workplaceLearningPeriod)
    {
        return $student->is($workplaceLearningPeriod->student);
    }

    /**
     * Determine whether the user can create workplace learning periods.
     */
    public function create(Student $student)
    {
        return true;
    }

    /**
     * Determine whether the user can update the workplace learning period.
     */
    public function update(Student $student, WorkplaceLearningPeriod $workplaceLearningPeriod)
    {
        return $student->is($workplaceLearningPeriod->student);
    }

    /**
     * Determine whether the user can delete the workplace learning period.
     */
    public function delete(Student $student, WorkplaceLearningPeriod $workplaceLearningPeriod)
    {
        return $student->is($workplaceLearningPeriod->student);
    }
}
