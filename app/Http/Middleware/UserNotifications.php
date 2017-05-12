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
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::guest()) {
            return redirect('login');
        }
        if (Auth::user()->getCurrentWorkplaceLearningPeriod() == null) {
            $request->session()->flash('notification', str_replace('%s', route('period'), Lang::get('notifications.generic.nointernshipactive')));
        }

        return $next($request);
    }
}
