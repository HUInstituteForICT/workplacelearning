<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class Locale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // If no locale has been set, set default one
        if(!Session::has('locale')) {
            Session::put('locale', 'nl');
        }
        // If user is authenticated, force it to preference
        if(Auth::check()) {
            Session::put('locale', Auth::user()->locale);
        }

        // Set for the app
        App::setLocale(Session::get('locale'));


        return $next($request);
    }
}
