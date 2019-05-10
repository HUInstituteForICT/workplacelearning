<?php


namespace App\Http\View\Composers;


use App\Repository\Eloquent\ReflectionMethodBetaParticipationRepository;
use App\Services\CurrentUserResolver;

class ReflectionBetaComposer
{
    /**
     * @var CurrentUserResolver
     */
    private $currentUserResolver;
    /**
     * @var ReflectionMethodBetaParticipationRepository
     */
    private $betaParticipationRepository;

    public function __construct(
        CurrentUserResolver $currentUserResolver,
        ReflectionMethodBetaParticipationRepository $betaParticipationRepository
    ) {
        $this->currentUserResolver = $currentUserResolver;
        $this->betaParticipationRepository = $betaParticipationRepository;
    }

    public function compose($view): void
    {
        $view->with('reflectionBetaActive', $this->getStudentReflectionBetaStatus());
    }

    private function getStudentReflectionBetaStatus(): bool
    {
        $student = $this->currentUserResolver->getCurrentUser();

        if (!$student->hasCurrentWorkplaceLearningPeriod()) {
            return false;
        }
        if ($student->educationProgram->educationprogramType->isProducing()) {
            return false;
        }

        return $this->betaParticipationRepository->doesStudentParticipate($student);
    }
}