<?php


namespace App\Http\Middleware;


use App\Http\View\Composers\ReflectionBetaComposer;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class ViewComposerMiddleware
{

    public function handle(Request $request, Closure $next)
    {
        View::composer('*', ReflectionBetaComposer::class);

        return $next($request);
    }
}