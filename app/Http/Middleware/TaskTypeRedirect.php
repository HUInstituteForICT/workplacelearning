<?php

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

        if ($student->educationProgram->educationprogramType->isActing()) {
            switch ($route->getName()) {
                case 'home':
                case 'default':
                    return $this->redirector->route('home-acting');
                    break;
                case 'process':
                    return $this->redirector->route('process-acting');
                    break;
                case 'analysis':
                    return $this->redirector->route('analysis-acting-choice');
                    break;
                case 'progress':
                    return $this->redirector->route('progress-acting');
                    break;
                case 'period':
                    return $this->redirector->route('period-acting');
                    break;
                case 'period-edit':
                    return $this->redirector->route('period-acting-edit', ['id' => $request->get('id')]);
                    break;
            }
        } else {
            // Assume the user follows an EP of type 'Producing'
            switch ($route->getName()) {
                case 'home':
                case 'default':
                    return $this->redirector->route('home-producing');
                    break;
                case 'process':
                    return $this->redirector->route('process-producing');
                    break;
                case 'analysis':
                    return $this->redirector->route('analysis-producing-choice');
                    break;
                case 'progress':
                    return $this->redirector->route('progress-producing');
                    break;
                case 'period':
                    return $this->redirector->route('period-producing');
                    break;
                case 'period-edit':
                    return $this->redirector->route('period-producing-edit', ['id' => $request->get('id')]);
                    break;
            }
        }

        return $next($request);
    }
}
