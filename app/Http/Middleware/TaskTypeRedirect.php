<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskTypeRedirect
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::guest()) {
            return redirect('login');
        }

        if ('Acting' == Auth::user()->educationprogram()->first()->educationprogramtype()->first()->eptype_name) {
            switch ($request->route()->getName()) {
                case 'home':
                case 'default':
                    return redirect()->route('home-acting');
                    break;
                case 'process':
                    return redirect()->route('process-acting');
                    break;
                case 'analysis':
                    return redirect()->route('analysis-acting-choice');
                    break;
                case 'progress':
                    return redirect()->route('progress-acting', ['page' => $request->page]);
                    break;
                case 'period':
                    return redirect()->route('period-acting');
                    break;
                case 'period-edit':
                    return redirect()->route('period-acting-edit', ['id' => $request->id]);
                    break;
            }
        } else {
            // Assume the user follows an EP of type 'Producing'
            switch ($request->route()->getName()) {
                case 'home':
                case 'default':
                    return redirect()->route('home-producing');
                    break;
                case 'process':
                    return redirect()->route('process-producing');
                    break;
                case 'analysis':
                    return redirect()->route('analysis-producing-choice');
                    break;
                case 'progress':
                    return redirect()->route('progress-producing', ['page' => $request->page]);
                    break;
                case 'period':
                    return redirect()->route('period-producing');
                    break;
                case 'period-edit':
                    return redirect()->route('period-producing-edit', ['id' => $request->id]);
                    break;
            }
        }

        return $next($request);
    }
}
