<?php

namespace App\Http\Middleware;

use App\Student;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

class RequireActiveInternship
{
    /**
     * @var Student|null
     */
    private $student;
    /**
     * @var Redirector
     */
    private $redirector;

    public function __construct(?Student $student, Redirector $redirector)
    {
        $this->student = $student;
        $this->redirector = $redirector;
    }

    public function handle(Request $request, Closure $next)
    {
        if($this->student === null) {
            return $this->redirector->route('login');
        }

        if (!$this->student->hasCurrentWorkplaceLearningPeriod()) {
            return $this->redirector->route('profile')->withErrors([__('notifications.internship-required')]);
        }

        return $next($request);
    }
}
