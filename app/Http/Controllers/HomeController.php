<?php

namespace App\Http\Controllers;

use App\Mail\FeedbackGiven;
use App\Repository\Eloquent\LikeRepository;
use App\Student;
use App\Tips\EvaluatedTipInterface;
use App\Tips\Services\ApplicableTipFetcher;
use Illuminate\Http\Request;
use Illuminate\Mail\Mailer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Validator;

class HomeController extends Controller
{
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

    public function showDefault()
    {
        return redirect()->route('home');
    }

    public function showBugReport()
    {
        return view('pages.bugreport');
    }

    public function createBugReport(Request $request, Mailer $mailer)
    {
        $validator = Validator::make($request->all(), [
            'onderwerp' => 'required|max:40|min:3',
            'uitleg' => 'required|max:800|min:5',
        ]);

        if ($validator->fails()) {
            return redirect()->route('bugreport')
                ->withErrors($validator)
                ->withInput();
        }

        $mailer->send(new FeedbackGiven($request, Auth::user()));

        return redirect()->route('home')->with('success', Lang::get('general.bugreport-sent'));
    }
}
