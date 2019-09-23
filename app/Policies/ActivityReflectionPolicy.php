<?php

namespace App\Policies;

use App\Student;
use App\Reflection\Models\ActivityReflection;
use Illuminate\Auth\Access\HandlesAuthorization;

class ActivityReflectionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the Student can view the ActivityReflection.
     */
    public function view(Student $student, ActivityReflection $activityReflection): bool
    {
        return $activityReflection->learningActivity->workplaceLearningPeriod->student->student_id === $student->student_id;
    }

    /**
     * Determine whether the Student can create ActivityReflection.
     */
    public function create(Student $student): bool
    {
        return  $student->hasCurrentWorkplaceLearningPeriod();
    }

    /**
     * Determine whether the Student can update the ActivityReflection.
     */
    public function update(Student $student, ActivityReflection $activityReflection): bool
    {
        return $activityReflection->learningActivity->workplaceLearningPeriod->student->student_id === $student->student_id;
    }

    /**
     * Determine whether the Student can delete the ActivityReflection.
     */
    public function delete(Student $student, ActivityReflection $activityReflection): bool
    {
        return $activityReflection->learningActivity->workplaceLearningPeriod->student->student_id === $student->student_id;
    }
}
