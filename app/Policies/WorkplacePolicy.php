<?php

namespace App\Policies;

use App\Student;
use App\Workplace;
use Illuminate\Auth\Access\HandlesAuthorization;

class WorkplacePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the workplace.
     */
    public function view(Student $student, Workplace $workplace)
    {
        return $student->is($workplace->workplaceLearningPeriod->student);
    }

    /**
     * Determine whether the user can create workplaces.
     */
    public function create(Student $student)
    {
        return true;
    }

    /**
     * Determine whether the user can update the workplace.
     */
    public function update(Student $student, Workplace $workplace)
    {
        return $student->is($workplace->workplaceLearningPeriod->student);
    }

    /**
     * Determine whether the user can delete the workplace.
     */
    public function delete(Student $student, Workplace $workplace)
    {
        return $student->is($workplace->workplaceLearningPeriod->student);
    }
}
