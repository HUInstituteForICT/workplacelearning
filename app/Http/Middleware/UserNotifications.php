<?php

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

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if ($this->guard->guest()) {
            return redirect('login');
        }
        /** @var Student $student */
        $student = $this->guard->user();
        if (!$student->hasCurrentWorkplaceLearningPeriod()) {
            $request->session()->flash('notification',
                __('notifications.generic.nointernshipactive', ['profile-url' => route('profile')]));
        }

        return $next($request);
    }
}
