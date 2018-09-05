<?php

namespace App\Http\Middleware;

use App\Student;
use Closure;
use Illuminate\Http\Request;

class RequireActiveInternship
{
    /**
     * @var Student
     */
    private $student;

    public function __construct(Student $student)
    {
        $this->student = $student;
    }

    public function handle(Request $request, Closure $next)
    {
        if (!$this->student->hasCurrentWorkplaceLearningPeriod()) {
            return redirect()->route('profile')->withErrors([__('notifications.internship-required')]);
        }

        return $next($request);
    }
}
