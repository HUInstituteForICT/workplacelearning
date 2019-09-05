<?php

namespace App\Http\Middleware;

use App\Exceptions\UnexpectedUser;
use App\Services\CurrentUserResolver;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Validation\UnauthorizedException;

class RequiresAdminLevel
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
     * @param Closure $next
     * @return RedirectResponse|mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            $user = $this->currentUserResolver->getCurrentUser();

            if (!$user->isAdmin()) {
                return $this->redirector->route('home');
            }
        } catch (UnexpectedUser $exception) {
            return $this->redirector->route('login');
        }

        return $next($request);
    }
}
