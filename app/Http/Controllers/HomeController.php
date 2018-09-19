<?php

namespace App\Http\Controllers;

use App\Http\Requests\BugReportRequest;
use App\Mail\FeedbackGiven;
use App\Repository\Eloquent\LikeRepository;
use App\Student;
use App\Tips\EvaluatedTipInterface;
use App\Tips\Services\ApplicableTipFetcher;
use Illuminate\Mail\Mailer;
use Illuminate\Routing\Redirector;

class HomeController extends Controller
{
    /**
     * @var Redirector
     */
    private $redirector;

    public function __construct(Redirector $redirector)
    {
        $this->redirector = $redirector;
    }

    public function showHome()
    {
        return view('pages.home');
    }

    /* Placeholder Templates */
    public function showProducingTemplate(Student $student, ApplicableTipFetcher $applicableTipFetcher, LikeRepository $likeRepository)
    {
        if ($student->hasCurrentWorkplaceLearningPeriod() && $student->getCurrentWorkplaceLearningPeriod()->hasLoggedHours()) {
            $applicableEvaluatedTips = collect($applicableTipFetcher->fetchForCohort($student->getCurrentWorkplaceLearningPeriod()->cohort));

            /* @var Student $student */
            $applicableEvaluatedTips->each(function (EvaluatedTipInterface $evaluatedTip) use ($student, $likeRepository): void {
                $likeRepository->loadForTipByStudent($evaluatedTip->getTip(), $student);
            });

            $evaluatedTip = $applicableEvaluatedTips->count() > 0 ? $applicableEvaluatedTips->random(null) : null;
        }

        return view('pages.producing.home', ['evaluatedTip' => $evaluatedTip ?? null]);
    }

    public function showActingTemplate(Student $student, ApplicableTipFetcher $applicableTipFetcher, LikeRepository $likeRepository)
    {
        if ($student->hasCurrentWorkplaceLearningPeriod() && $student->getCurrentWorkplaceLearningPeriod()->hasLoggedHours()) {
            $applicableEvaluatedTips = collect($applicableTipFetcher->fetchForCohort($student->getCurrentWorkplaceLearningPeriod()->cohort));

            /* @var Student $student */
            $applicableEvaluatedTips->each(function (EvaluatedTipInterface $evaluatedTip) use ($student, $likeRepository): void {
                $likeRepository->loadForTipByStudent($evaluatedTip->getTip(), $student);
            });

            $evaluatedTip = $applicableEvaluatedTips->count() > 0 ? $applicableEvaluatedTips->random(null) : null;
        }

        return view('pages.acting.home', ['evaluatedTip' => $evaluatedTip ?? null]);
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
        Mailer $mailer,
        Student $student
    ): \Illuminate\Http\RedirectResponse {
        $mailer->send(
            new FeedbackGiven(
                $student,
                $request->get('feedback_subject'),
                $request->get('feedback_description')
            )
        );

        return $this->redirector->route('home')->with('success', __('general.bugreport-sent'));
    }
}
