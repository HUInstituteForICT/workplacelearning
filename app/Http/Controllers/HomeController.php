<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\BugReportRequest;
use App\Mail\FeedbackGiven;
use App\Repository\Eloquent\LikeRepository;
use App\Repository\Eloquent\SavedLearningItemRepository;
use App\Services\CurrentUserResolver;
use App\Tips\EvaluatedTipInterface;
use App\Tips\Services\ApplicableTipFetcher;
use Illuminate\Contracts\View\View;
use Illuminate\Mail\Mailer;
use Illuminate\Routing\Redirector;

class HomeController extends Controller
{
    /**
     * @var Redirector
     */
    private $redirector;
    /**
     * @var CurrentUserResolver
     */
    private $currentUserResolver;

    public function __construct(Redirector $redirector, CurrentUserResolver $currentUserResolver)
    {
        $this->redirector = $redirector;

        $this->currentUserResolver = $currentUserResolver;
    }

    public function showHome(): View
    {
        return view('pages.home');
    }

    /* Placeholder Templates */
    public function showProducingTemplate(ApplicableTipFetcher $applicableTipFetcher, LikeRepository $likeRepository, SavedLearningItemRepository $savedLearningItemRepository)
    {
        $student = $this->currentUserResolver->getCurrentUser();

        if ($student->hasCurrentWorkplaceLearningPeriod() && $student->getCurrentWorkplaceLearningPeriod()->hasLoggedHours()) {
            $applicableEvaluatedTips = collect($applicableTipFetcher->fetchForCohort($student->getCurrentWorkplaceLearningPeriod()->cohort));

            $applicableEvaluatedTips->each(function (EvaluatedTipInterface $evaluatedTip) use ($student, $likeRepository): void {
                $likeRepository->loadForTipByStudent($evaluatedTip->getTip(), $student);
            });

            $evaluatedTip = $applicableEvaluatedTips->count() > 0 ? $applicableEvaluatedTips->random(null) : null;
            $itemExists = $savedLearningItemRepository->itemExists('tip', $evaluatedTip->getTip()->id, $student->student_id);
        }

        return view('pages.producing.home', [
            'evaluatedTip' => $evaluatedTip ?? null,
            'itemExists' => $itemExists ?? false
        ]);
    }

    public function showActingTemplate(ApplicableTipFetcher $applicableTipFetcher, LikeRepository $likeRepository, SavedLearningItemRepository $savedLearningItemRepository)
    {
        $student = $this->currentUserResolver->getCurrentUser();
        if ($student->hasCurrentWorkplaceLearningPeriod() && $student->getCurrentWorkplaceLearningPeriod()->hasLoggedHours()) {
            $applicableEvaluatedTips = collect($applicableTipFetcher->fetchForCohort($student->getCurrentWorkplaceLearningPeriod()->cohort));

            $applicableEvaluatedTips->each(function (EvaluatedTipInterface $evaluatedTip) use ($student, $likeRepository): void {
                $likeRepository->loadForTipByStudent($evaluatedTip->getTip(), $student);
            });

            $evaluatedTip = $applicableEvaluatedTips->count() > 0 ? $applicableEvaluatedTips->random(null) : null;
            $itemExists = $savedLearningItemRepository->itemExists('tip', $evaluatedTip->getTip()->id, $student->student_id);
        }

        return view('pages.acting.home', [
            'evaluatedTip' => $evaluatedTip ?? null,
            'itemExists' => $itemExists ?? false
        ]);
    }

    public function showAdminTemplate()
    {
        return view('pages.admin.home');
    }

    public function showTeacherTemplate()
    {
        return view('pages.teacher.home');
    }

    public function showDefault(): \Illuminate\Http\RedirectResponse
    {
        return $this->redirector->route('home');
    }

    public function showBugReport()
    {
        return view('pages.bugreport');
    }

    public function createBugReport(
        BugReportRequest $request,
        Mailer $mailer
    ): \Illuminate\Http\RedirectResponse {
        $mailer->send(
            new FeedbackGiven(
                $this->currentUserResolver->getCurrentUser(),
                $request->get('feedback_subject'),
                $request->get('feedback_description')
            )
        );

        return $this->redirector->route('home')->with('success', __('general.bugreport-sent'));
    }
}
