<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserNotifications
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::guest()) {
            return redirect('login');
        }
        if (!Auth::user()->hasCurrentWorkplaceLearningPeriod()) {
            $request->session()->flash('notification',
                __('notifications.generic.nointernshipactive', ['profile-url' => route('profile')]));
        }

        return $next($request);
    }
}
