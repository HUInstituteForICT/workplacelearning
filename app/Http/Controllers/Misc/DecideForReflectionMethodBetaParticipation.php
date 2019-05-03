<?php


namespace App\Http\Controllers\Misc;


use App\Repository\Eloquent\ReflectionMethodBetaParticipationRepository;
use App\Services\CurrentUserResolver;
use Illuminate\Http\Request;

class DecideForReflectionMethodBetaParticipation
{
    /**
     * @var ReflectionMethodBetaParticipationRepository
     */
    private $reflectionMethodBetaParticipationRepository;
    /**
     * @var CurrentUserResolver
     */
    private $currentUserResolver;


    public function __construct(
        ReflectionMethodBetaParticipationRepository $reflectionMethodBetaParticipationRepository,
        CurrentUserResolver $currentUserResolver
    ) {
        $this->reflectionMethodBetaParticipationRepository = $reflectionMethodBetaParticipationRepository;
        $this->currentUserResolver = $currentUserResolver;
    }

    public function __invoke(Request $request, bool $participates)
    {
        $student = $this->currentUserResolver->getCurrentUser();
        if (!$this->reflectionMethodBetaParticipationRepository->hasStudentDecided($student)) {
            $this->reflectionMethodBetaParticipationRepository->decideForStudent($student, $participates);

            if ($participates) {
                $request->session()->flash('success', __('misc.reflection-beta-participate-success-text'));
            }
        }

        return redirect()->back();
    }


}