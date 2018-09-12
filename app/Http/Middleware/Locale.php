<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\App;

class Locale
{
    /** @var Guard */
    private $auth;

    public function __construct(Guard $authManager)
    {
        $this->auth = $authManager;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     */
    public function handle($request, Closure $next)
    {
        // If no locale has been set, set default one

        if (!$request->session()->has('locale')) {
            $request->session()->put('locale', 'nl');
        }
        // If user is authenticated, force it to preference
        if ($this->auth->check()) {
            $request->session()->put('locale', $request->user()->locale);
        }

        // Set for the app
        app()->setLocale($request->session()->get('locale'));

        // Override locale if necessary
        if (!empty($_GET['l'])) {
            app()->setLocale($_GET['l']);
        }

        return $next($request);
    }
}
