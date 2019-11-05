<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Exceptions\UnexpectedUser;
use App\Services\CurrentUserResolver;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

class RequiresTeacherLevel
{
    /**
     * @var CurrentUserResolver
     */
    private $currentUserResolver;

    /**
     * @var Redirector
     */
    private $redirector;

    public function __construct(CurrentUserResolver $currentUserResolver, Redirector $redirector)
    {
        $this->currentUserResolver = $currentUserResolver;
        $this->redirector = $redirector;
    }

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     *
     * @return RedirectResponse|mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            $user = $this->currentUserResolver->getCurrentUser();

            if (!$user->isTeacher() && !$user->isAdmin()) {
                return $this->redirector->route('home');
            }
        } catch (UnexpectedUser $exception) {
            return $this->redirector->route('login');
        }

        return $next($request);
    }
}
