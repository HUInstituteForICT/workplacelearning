<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskTypeRedirect {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next){

        if(Auth::guest()) return redirect('login');
        if($request->route()->getName() == "home" || $request->route()->getName() == "default"){
            if(Auth::user()->educationprogram()->first()->educationprogramtype()->first()->eptype_name == "Acting"){
                return redirect()->route('home-acting');
            } else {
                return redirect()->route('home-producing');
            }
        }
        if($request->route()->getName() == "process"){
            if(Auth::user()->educationprogram()->first()->educationprogramtype()->first()->eptype_name == "Acting"){
                return redirect()->route('process-acting');
            } else {
                return redirect()->route('process-producing');
            }
        }
        if($request->route()->getName() == "analysis"){
            if(Auth::user()->educationprogram()->first()->educationprogramtype()->first()->eptype_name == "Acting"){
                return redirect()->route('analysis-acting-choice');
            } else {
                return redirect()->route('analysis-producing-choice');
            }
        }
        if($request->route()->getName() == "progress"){
            if(Auth::user()->educationprogram()->first()->educationprogramtype()->first()->eptype_name == "Acting"){
                return redirect()->route('progress-acting', ['page' => 1]);
            } else {
                return redirect()->route('progress-producing', ['page' => 1]);
            }
        }
        if($request->route()->getName() == "period"){
            if(Auth::user()->educationprogram()->first()->educationprogramtype()->first()->eptype_name == "Acting"){
                return redirect()->route('period-acting', ['page' => 1]);
            } else {
                return redirect()->route('period-producing', ['page' => 1]);
            }
        }
        if($request->route()->getName() == "period-edit"){
            if(Auth::user()->educationprogram()->first()->educationprogramtype()->first()->eptype_name == "Acting"){
                return redirect()->route('period-acting-edit', ['page' => 1]);
            } else {
                return redirect()->route('period-producing-edit', ['page' => 1]);
            }
        }
        return $next($request);
    }
}
