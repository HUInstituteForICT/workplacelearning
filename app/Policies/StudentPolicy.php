<?php

namespace App\Policies;

use App\Student;
use Illuminate\Auth\Access\HandlesAuthorization;

class StudentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the Student information.
     * Users with the role of teacher can only view the details of students who are assigned to them.
     */
    public function view(Student $teacher, Student $student): bool
    {
        foreach ($student->workplaceLearningPeriods as $wplp) {
            if ($teacher->student_id == $wplp->teacher_id) {
                return true;
            }
        }

        return false;
    }
}
