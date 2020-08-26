<?php

declare(strict_types=1);

namespace App\Policies;

use App\Exceptions\UnlinkedInternshipException;
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
            try {
                /** @throws UnlinkedInternshipException */
                if ($teacher->is($wplp->teacher)) {
                    return true;
                }
            } catch (UnlinkedInternshipException $exception) {
                continue;
            }

        }

        return false;
    }
}
