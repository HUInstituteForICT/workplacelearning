<?php

namespace App\Http\Controllers;

use App\Mail\FeedbackGiven;
use App\Repository\Eloquent\LikeRepository;
use App\Student;
use App\Tips\ApplicableTipFetcher;
use App\Tips\EvaluatedTip;
use App\WorkplaceLearningPeriod;
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
    public function showProducingTemplate(Request $request, ApplicableTipFetcher $applicableTipFetcher, LikeRepository $likeRepository)
    {

        /** @var WorkplaceLearningPeriod $workplaceLearningPeriod */
        $workplaceLearningPeriod = $request->user()->getCurrentWorkplaceLearningPeriod();

        if ($workplaceLearningPeriod !== null) {

            $applicableEvaluatedTips = collect($applicableTipFetcher->fetchForCohort($workplaceLearningPeriod->cohort));

            /** @var Student $student */
            $student = $request->user();
            $applicableEvaluatedTips->each(function (EvaluatedTip $evaluatedTip) use ($student, $likeRepository) {
                $likeRepository->loadForTipByStudent($evaluatedTip->getTip(), $student);
            });

            $evaluatedTip = $applicableEvaluatedTips->count() > 0 ? $applicableEvaluatedTips->random(null) : null;
        }



        return view('pages.producing.home', ['evaluatedTip' => $evaluatedTip ?? null]);
    }

    public function showActingTemplate(Request $request, ApplicableTipFetcher $applicableTipFetcher, LikeRepository $likeRepository)
    {
        /** @var WorkplaceLearningPeriod $workplaceLearningPeriod */
        $workplaceLearningPeriod = $request->user()->getCurrentWorkplaceLearningPeriod();

        if ($workplaceLearningPeriod !== null) {
            $applicableEvaluatedTips = collect($applicableTipFetcher->fetchForCohort($workplaceLearningPeriod->cohort));

            /** @var Student $student */
            $student = $request->user();
            $applicableEvaluatedTips->each(function (EvaluatedTip $evaluatedTip) use ($student, $likeRepository) {
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
            'uitleg'    => 'required|max:800|min:5',
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
