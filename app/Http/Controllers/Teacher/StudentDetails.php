<?php

declare(strict_types=1);

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Student;

class StudentDetails extends Controller
{
    public function __invoke(Student $student)
    {
        $currentWorkplace = $student->getCurrentWorkplace();

        return view('pages.teacher.student_details')
            ->with('student', $student)
            ->with('currentWorkplace', $currentWorkplace);
    }
}
