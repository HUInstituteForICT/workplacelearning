<?php


namespace App\Http\Controllers\Misc\Admin;


use App\Reflection\Repository\Eloquent\ReflectionMethodBetaParticipationRepository;

class BetaParticipations
{
    /**
     * @var ReflectionMethodBetaParticipationRepository
     */
    private $betaParticipationRepository;

    public function __construct(ReflectionMethodBetaParticipationRepository $betaParticipationRepository)
    {
        $this->betaParticipationRepository = $betaParticipationRepository;
    }


    public function __invoke()
    {
        $participations = $this->betaParticipationRepository->getParticipations();

        return view('pages.admin.beta-participations', ['participations' => $participations]);
    }
}