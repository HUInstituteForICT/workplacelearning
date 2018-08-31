<?php

namespace App\Http\Middleware;

use App\Student;
use Closure;
use Illuminate\Validation\UnauthorizedException;

class CheckUserLevel
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     *
     * @throws \Illuminate\Validation\UnauthorizedException
     */
    public function handle($request, Closure $next)
    {
        /** @var Student $user */
        $user = $request->user();
        if (1 !== $user->getUserLevel()) {
            throw new UnauthorizedException('Insufficient permissions');
        }

        return $next($request);
    }
}
