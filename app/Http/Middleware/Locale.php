<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthManager;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class Locale
{
    /** @var AuthManager|Auth $authManager */
    private $authManager;

    public function __construct(AuthManager $authManager)
    {
        $this->authManager = $authManager;
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
        if ($this->authManager->check()) {
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
