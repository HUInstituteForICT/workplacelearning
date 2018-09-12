<?php

namespace App\Policies;

use App\Feedback;
use App\Student;
use Illuminate\Auth\Access\HandlesAuthorization;

class FeedbackPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the Student can view the feedback.
     */
    public function view(Student $student, Feedback $feedback)
    {
        return $feedback->learningActivityProducing->workplaceLearningPeriod->student->student_id === $student->student_id;
    }

    /**
     * Determine whether the Student can create feedback.
     */
    public function create(Student $student)
    {
        return $student->educationProgram->educationprogramType->isProducing();
    }

    /**
     * Determine whether the Student can update the feedback.
     */
    public function update(Student $student, Feedback $feedback)
    {
        return $feedback->learningActivityProducing->workplaceLearningPeriod->student->student_id === $student->student_id;
    }

    /**
     * Determine whether the Student can delete the feedback.
     */
    public function delete(Student $student, Feedback $feedback)
    {
        return false;
    }
}
