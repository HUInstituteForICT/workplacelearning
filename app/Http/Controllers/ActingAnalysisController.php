<?php
/**
 * This file (ActingAnalysisController.php) was created on 08/31/2016 at 14:15.
 * (C) Max Cassee
 * This project was commissioned by HU University of Applied Sciences.
 */

namespace App\Http\Controllers;

use App\Analysis\Acting\ActingAnalysis;
use App\Analysis\Acting\ActingAnalysisCollector;
use App\Cohort;
use App\Repository\LikeRepositoryInterface;
use App\Student;
use App\Tips\ApplicableTipFetcher;
use App\Tips\DataCollectors\Collector;
use App\Tips\EvaluatedStatisticTip;
use App\Tips\EvaluatedTip;
use App\Tips\Tip;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;

class ActingAnalysisController extends Controller
{

    public function showChoiceScreen()
    {
        // Check if user has active workplace
        if (Auth::user()->getCurrentWorkplaceLearningPeriod() == null) {
            return redirect()->route('home')->withErrors([Lang::get('notifications.generic.nointernshipactive')]);
        }
        // Check if for the workplace the user has hours registered
        if (!Auth::user()->getCurrentWorkplaceLearningPeriod()->hasLoggedHours()) {
            return redirect()->route('home')->withErrors([Lang::get('notifications.generic.nointernshipregisteredactivities')]);
        }


        return view('pages.acting.analysis.choice');
    }

    /**
     * @param Request $request
     * @param $year
     * @param $month
     * @param ApplicableTipFetcher $applicableTipFetcher
     * @param LikeRepositoryInterface $likeRepository
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function showDetail(
        Request $request,
        $year,
        $month,
        ApplicableTipFetcher $applicableTipFetcher,
        LikeRepositoryInterface $likeRepository
    ) {
        if (Auth::user()->getCurrentWorkplaceLearningPeriod() === null || Auth::user()->getCurrentWorkplaceLearningPeriod()->getLastActivity(1)->count() === 0) {
            return redirect()->route('home-acting')
                ->withErrors([Lang::get('analysis.no-activity')]);
        }

        // Check valid date options
        if (($year != "all" && $month != "all")
            && (0 == preg_match('/^(20)([0-9]{2})$/', $year) || 0 == preg_match('/^([0-1]{1}[0-9]{1})$/', $month))
        ) {
            return redirect()->route('analysis-producing-choice');
        }


        // The analysis for the charts etc.
        $analysis = new ActingAnalysis(new ActingAnalysisCollector($year, $month));

        if ($year === "all" || $month === "all") {
            $year = null;
            $month = null;
        }

        $workplaceLearningPeriod = $request->user()->getCurrentWorkplaceLearningPeriod();

        /** @var Cohort $cohort */
        $cohort = $workplaceLearningPeriod->cohort;

        $applicableEvaluatedTips = collect($applicableTipFetcher->fetchForCohort($cohort));

        /** @var Student $student */
        $student = $request->user();
        $applicableEvaluatedTips->each(function (EvaluatedTip $evaluatedTip) use ($student, $likeRepository) {
            $likeRepository->loadForTipByStudent($evaluatedTip->getTip(), $student);
        });


        return view('pages.acting.analysis.detail')
            ->with('evaluatedTips', $applicableEvaluatedTips)
            ->with('actingAnalysis', $analysis);
    }
}
