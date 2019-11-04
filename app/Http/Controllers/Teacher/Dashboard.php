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
        $wplps = Auth::user()->linkedWorkplaceLearningPeriods()->get();
        $students = array();

        foreach ($wplps as $wplp) {
            array_push($students, $wplp->student);
        }

        return view('pages.teacher.dashboard')->with('students', array_unique($students));
    }
}
