<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Student;
use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Routing\Route;

class TaskTypeRedirect
{
    /**
     * @var Guard
     */
    private $guard;
    /**
     * @var Redirector
     */
    private $redirector;

    public function __construct(Guard $guard, Redirector $redirector)
    {
        $this->guard = $guard;
        $this->redirector = $redirector;
    }

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if ($this->guard->guest()) {
            return $this->redirector->to('login');
        }

        /** @var Student $student */
        $student = $this->guard->user();

        $route = $request->route();
        if (!$route instanceof Route) {
            return $next($request);
        }

        if ($student->isTeacher()) {
            return $this->redirector->route('home-teacher');
        }

        if ($student->isAdmin() && $route->getName() === 'default') {
            return $this->redirector->route('home-admin');
        }

        if ($student->educationProgram->educationprogramType->isActing()) {
            switch ($route->getName()) {
                case 'home':
                case 'default':
                    return $this->redirector->route('home-acting');
                case 'process':
                    return $this->redirector->route('process-acting');
                case 'analysis':
                    return $this->redirector->route('analysis-acting-choice');
                case 'progress':
                    return $this->redirector->route('progress-acting');
                case 'period':
                    return $this->redirector->route('period-acting');
                case 'period-edit':
                    return $this->redirector->route('period-acting-edit', ['id' => $route->parameter('id')]);
            }
        }

        if ($student->educationProgram->educationprogramType->isProducing()) {
            // Assuming the user is Student or Admin and follows an EP of type 'Producing'
            switch ($route->getName()) {
                case 'home':
                case 'default':
                    return $this->redirector->route('home-producing');
                case 'process':
                    return $this->redirector->route('process-producing');
                case 'analysis':
                    return $this->redirector->route('analysis-producing-choice');
                case 'progress':
                    return $this->redirector->route('progress-producing');
                case 'period':
                    return $this->redirector->route('period-producing');
                case 'period-edit':
                    return $this->redirector->route('period-producing-edit', ['id' => $route->parameter('id')]);
            }
        }

        return $next($request);
    }
}
