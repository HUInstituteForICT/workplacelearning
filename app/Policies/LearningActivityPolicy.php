<?php

namespace App\Policies;

use App\LearningActivityInterface;
use App\Student;
use App\WorkplaceLearningPeriod;
use Illuminate\Auth\Access\HandlesAuthorization;

class LearningActivityPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the Student can view the learningActivityInterface.
     *
     * @param  \App\Student $student
     * @param  \App\LearningActivityInterface $learningActivityInterface
     * @return mixed
     */
    public function view(Student $student, LearningActivityInterface $learningActivityInterface)
    {
        return $learningActivityInterface->workplaceLearningPeriod->is($student->getCurrentWorkplaceLearningPeriod());
    }

    /**
     * Determine whether the Student can create learningActivityInterfaces.
     *
     * @param  \App\Student $student
     * @return mixed
     */
    public function create(Student $student)
    {
        return $student->getCurrentWorkplaceLearningPeriod() !== null;
    }

    /**
     * Determine whether the Student can update the learningActivityInterface.
     *
     * @param  \App\Student $student
     * @param  \App\LearningActivityInterface $learningActivityInterface
     * @return mixed
     */
    public function update(Student $student, LearningActivityInterface $learningActivityInterface)
    {
        return $learningActivityInterface->workplaceLearningPeriod->is($student->getCurrentWorkplaceLearningPeriod());
    }

    /**
     * Determine whether the Student can delete the learningActivityInterface.
     *
     * @param  \App\Student $student
     * @param  \App\LearningActivityInterface $learningActivityInterface
     * @return mixed
     */
    public function delete(Student $student, LearningActivityInterface $learningActivityInterface)
    {
        return $learningActivityInterface->workplaceLearningPeriod->is($student->getCurrentWorkplaceLearningPeriod());
    }
}
