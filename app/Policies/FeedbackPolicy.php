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
     *
     * @param  \App\Student $student
     * @param  \App\Feedback $feedback
     * @return mixed
     */
    public function view(Student $student, Feedback $feedback)
    {
        return $feedback->learningActivityProducing->workplaceLearningPeriod->student->student_id === $student->student_id;
    }

    /**
     * Determine whether the Student can create feedback.
     *
     * @param  \App\Student $student
     * @return mixed
     */
    public function create(Student $student)
    {
        return $student->educationProgram->educationprogramType->isProducing();
    }

    /**
     * Determine whether the Student can update the feedback.
     *
     * @param  \App\Student $student
     * @param  \App\Feedback $feedback
     * @return mixed
     */
    public function update(Student $student, Feedback $feedback)
    {
        return $feedback->learningActivityProducing->workplaceLearningPeriod->student->student_id === $student->student_id;
    }

    /**
     * Determine whether the Student can delete the feedback.
     *
     * @param  \App\Student $student
     * @param  \App\Feedback $feedback
     * @return mixed
     */
    public function delete(Student $student, Feedback $feedback)
    {
        return false;
    }
}
