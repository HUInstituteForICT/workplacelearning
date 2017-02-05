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
        dump($request->route());
        die();

        if($request->getRequestUri() == "/leerproces"){
            if(Auth::user()->educationprogram()->first()->educationprogramtype()->first()->eptype_name == "Acting"){
                return redirect("acting");
            } else {
                return redirect("producing");
            }
        }
        if($request->getRequestUri() == "/analyse"){
            if(Auth::user()->educationprogram()->first()->educationprogramtype()->first()->eptype_name == "Acting"){
                return redirect("/analyse-acting");
            } else {
                return redirect("/analyse-producing");
            }
        }
        return $next($request);
    }

}