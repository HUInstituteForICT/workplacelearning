<?php


namespace App\Http\Controllers\Misc;


use App\Repository\Eloquent\ReflectionMethodBetaParticipationRepository;
use App\Services\CurrentUserResolver;
use Illuminate\Http\Request;

class LeaveBeta
{
    /**
     * @var ReflectionMethodBetaParticipationRepository
     */
    private $betaParticipationRepository;
    /**
     * @var CurrentUserResolver
     */
    private $currentUserResolver;

    public function __construct(ReflectionMethodBetaParticipationRepository $betaParticipationRepository, CurrentUserResolver $currentUserResolver)
    {
        $this->betaParticipationRepository = $betaParticipationRepository;
        $this->currentUserResolver = $currentUserResolver;
    }

    public function __invoke(Request $request)
    {
        $this->betaParticipationRepository->leaveBeta($this->currentUserResolver->getCurrentUser());

        $request->session()->flash('success', __('misc.beta-leave-success'));

        return redirect()->back();
    }
}