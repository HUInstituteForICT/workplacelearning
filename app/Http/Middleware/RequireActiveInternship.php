<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Exceptions\UnexpectedUser;
use App\Services\CurrentUserResolver;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

class RequireActiveInternship
{
    /**
     * @var Redirector
     */
    private $redirector;
    /**
     * @var CurrentUserResolver
     */
    private $currentUserResolver;

    public function __construct(CurrentUserResolver $currentUserResolver, Redirector $redirector)
    {
        $this->redirector = $redirector;
        $this->currentUserResolver = $currentUserResolver;
    }

    public function handle(Request $request, Closure $next)
    {
        try {
            $student = $this->currentUserResolver->getCurrentUser();

            if (!$student->hasCurrentWorkplaceLearningPeriod()) {
                return $this->redirector->route('profile')->withErrors([__('notifications.internship-required')]);
            }

            return $next($request);
        } catch (UnexpectedUser $exception) {
            return $this->redirector->route('login');
        }
    }
}
