<?php

declare(strict_types=1);

namespace App\Policies;

use App\Interfaces\LearningActivityInterface;
use App\Student;
use Illuminate\Auth\Access\HandlesAuthorization;

class LearningActivityPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the Student can view the learningActivityInterface.
     */
    public function view(Student $student, LearningActivityInterface $learningActivityInterface): bool
    {
        return $learningActivityInterface->workplaceLearningPeriod->is($student->getCurrentWorkplaceLearningPeriod());
    }

    /**
     * Determine whether the Student can create learningActivityInterfaces.
     */
    public function create(Student $student): bool
    {
        return $student->getCurrentWorkplaceLearningPeriod() !== null;
    }

    /**
     * Determine whether the Student can update the learningActivityInterface.
     */
    public function update(Student $student, LearningActivityInterface $learningActivityInterface): bool
    {
        return $learningActivityInterface->workplaceLearningPeriod->is($student->getCurrentWorkplaceLearningPeriod());
    }

    /**
     * Determine whether the Student can delete the learningActivityInterface.
     */
    public function delete(Student $student, LearningActivityInterface $learningActivityInterface): bool
    {
        return $learningActivityInterface->workplaceLearningPeriod->is($student->getCurrentWorkplaceLearningPeriod());
    }
}
