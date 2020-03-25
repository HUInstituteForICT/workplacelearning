<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Student;
use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;

class UserNotifications
{
    /**
     * @var Guard
     */
    private $guard;

    public function __construct(Guard $guard)
    {
        $this->guard = $guard;
    }

    public function handle(Request $request, Closure $next)
    {
        if ($this->guard->guest()) {
            return redirect('login');
        }
        /** @var Student $student */
        $student = $this->guard->user();
        if ($student->isStudent() && !$student->hasCurrentWorkplaceLearningPeriod() && !str_contains($request->url(),
                ['period/', 'profile', 'profiel'])) {
            $request->session()->flash('no-internship',
                __('notifications.generic.nointernshipactive', ['profile-url' => route('profile')]));
            if ($student->educationProgram->educationprogramType->isActing()) {
                return redirect()->action('ActingWorkplaceLearningController@create');
            }

            return redirect()->action('ProducingWorkplaceLearningController@create');
        }

        return $next($request);
    }
}
