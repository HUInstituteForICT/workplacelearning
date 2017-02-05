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
        if($request->route()->getName() == "process"){
            if(Auth::user()->educationprogram()->first()->educationprogramtype()->first()->eptype_name == "Acting"){
                return redirect()->route('process-acting');
            } else {
                return redirect()->route('process-producing');
            }
        }
        if($request->route()->getName() == "progress"){
            if(Auth::user()->educationprogram()->first()->educationprogramtype()->first()->eptype_name == "Acting"){
                //return redirect()->route('progress-acting', ['page' => 1]);
            } else {
                return redirect()->route('progress-producing', ['page' => 1]);
            }
        }
        return $next($request);
    }

}