<?php


namespace App\Http\View\Composers;


use App\Exceptions\UnexpectedUser;
use App\Reflection\Repository\Eloquent\ReflectionMethodBetaParticipationRepository;
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
        // getCurrentUser will throw if no user is found so catch that and assume not in beta because logged out
        try {
            $student = $this->currentUserResolver->getCurrentUser();
        } catch (UnexpectedUser $unexpectedUser) {
            return false;
        }

        if (!$student->hasCurrentWorkplaceLearningPeriod()) {
            return false;
        }
        if ($student->educationProgram->educationprogramType->isProducing()) {
            return false;
        }

        return $this->betaParticipationRepository->doesStudentParticipate($student);
    }
}