<?php
/**
 * This file (ProducingAnalysisController.php) was created on 08/31/2016 at 14:15.
 * (C) Max Cassee
 * This project was commissioned by HU University of Applied Sciences.
 */

namespace App\Http\Controllers;

use App\Chart;
use App\LearningActivityProducing;
use App\Analysis\Producing\ProducingAnalysis;
use App\Analysis\Producing\ProducingAnalysisCollector;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;

class ProducingAnalysisController extends Controller
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


        return view('pages.producing.analysis.choice')
            ->with('numdays', (new ProducingAnalysisCollector())->getFullWorkingDays("all", "all"));
    }

    public function showDetail(Request $request, $year, $month)
    {
        // If no data or not enough data, redirect to analysis choice page
        if (Auth::user()->getCurrentWorkplaceLearningPeriod() == null) {
            return redirect()->route('analyse-producing-choice')->with('error', Lang::get('notifications.generic.nointernshipactive'));
        }

        // Check valid date options
        if (($year != "all" && $month != "all")
            && (0 == preg_match('/^(20)([0-9]{2})$/', $year) || 0 == preg_match('/^([0-1]{1}[0-9]{1})$/', $month))
        ) {
            return redirect()->route('analysis-producing-choice');
        }

        // Create new Analysis for the producing student
        $producingAnalysis = new ProducingAnalysis(new ProducingAnalysisCollector(), $year, $month);

        // If there are no chains, there are no activities therefore redirect user somewhere else
        if (count($producingAnalysis->chains()) == 0) {
            return redirect()->route('analysis-producing-choice')->withErrors([Lang::get('notifications.generic.nointernshiphoursmonth')]);
        }

        // Get the raw data of the analysis, used in the view
        $analysisData = $producingAnalysis->analysisData;

        return view('pages.producing.analysis.detail')
            ->with('producingAnalysis', $producingAnalysis)
            ->with('analysis', $analysisData)
            ->with('year', $year)
            ->with('monthno', $month);
    }
}
