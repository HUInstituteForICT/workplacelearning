<?php

namespace App\Http\Middleware;

use App\Exceptions\UnexpectedUser;
use App\Services\CurrentUserResolver;
use Closure;
use Illuminate\Routing\Redirector;

class CheckUserLevel
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
     * @param \Illuminate\Http\Request $request
     *
     * @throws \Illuminate\Validation\UnauthorizedException
     */
    public function handle($request, Closure $next)
    {
        try {
            $user = $this->currentUserResolver->getCurrentUser();

            if ($user->getUserLevel() !== 1) {
                return $this->redirector->route('home');
            }
        } catch (UnexpectedUser $exception) {
            return $this->redirector->route('login');
        }

        return $next($request);
    }
}
