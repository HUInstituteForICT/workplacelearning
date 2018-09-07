<?php

namespace App\Http\Middleware;

use App\Student;
use Closure;
use Illuminate\Validation\UnauthorizedException;

class CheckUserLevel
{
    /**
     * @var Student
     */
    private $user;

    public function __construct(Student $user)
    {
        $this->user = $user;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @throws \Illuminate\Validation\UnauthorizedException
     */
    public function handle($request, Closure $next)
    {
        if (1 !== $this->user->getUserLevel()) {
            throw new UnauthorizedException('Insufficient permissions');
        }

        return $next($request);
    }
}
