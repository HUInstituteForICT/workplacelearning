<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;

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
        if (null === Auth::user()->getCurrentWorkplaceLearningPeriod()) {
            $request->session()->flash('notification', str_replace('%s', route('period'), Lang::get('notifications.generic.nointernshipactive')));
        }

        return $next($request);
    }
}
