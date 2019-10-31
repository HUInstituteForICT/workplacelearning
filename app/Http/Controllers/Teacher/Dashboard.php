<?php

declare(strict_types=1);

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Student;

class Dashboard extends Controller
{
    public function __invoke()
    {
        $user = Auth::user();
        $students = student::join('workplacelearningperiod', 'student.student_id', '=', 'workplacelearningperiod.student_id')
            ->where('workplacelearningperiod.teacher_id', $user->student_id)
            ->select('student.*')
            ->distinct()
            ->get();

        return view('pages.teacher.dashboard')->with('students', $students);
    }
}
