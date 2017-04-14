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

class ProducingAnalysisController extends Controller {

    public function showChoiceScreen(){
        if(Auth::user()->getCurrentWorkplaceLearningPeriod() == null) return redirect()->route('home')->withErrors(["Je kan deze pagina niet bekijken zonder actieve stage."]);
        if(!Auth::user()->getCurrentWorkplaceLearningPeriod()->hasLoggedHours()) return redirect()->route('home')->withErrors(["Je hebt nog geen uren geregistreerd voor deze stage."]);


        return view('pages.producing.analysis.choice')
            ->with('numdays', (new ProducingAnalysisCollector())->getFullWorkingDays("all", "all"));
    }

    public function showDetail(Request $request, $year, $month){
        // If no data or not enough data, redirect to analysis choice page
        if(Auth::user()->getCurrentWorkplaceLearningPeriod() == null) return redirect()->route('analyse-producing-choice')->with('error', 'Je hebt geen actieve stage ingesteld!');

        if(($year != "all" && $month != "all")
            && (0 == preg_match('/^(20)([0-9]{2})$/', $year) || 0 == preg_match('/^([0-1]{1}[0-9]{1})$/', $month))
        ) return redirect()->route('analysis-producing-choice');

        $producingAnalysis = new ProducingAnalysis(new ProducingAnalysisCollector(), $year, $month);

        $task_chains = $producingAnalysis->analysisCollector->getTaskChainsByDate(25, $year, $month);
        if(count($task_chains) == 0) return redirect()->route('analysis-producing-choice')->withErrors(['Je hebt geen activiteiten ingevuld voor deze maand.']);

        // Analysis array
        $analysisData = $producingAnalysis->analysisData;


        return view('pages.producing.analysis.detail')
            ->with('producingAnalysis', $producingAnalysis)
            ->with('analysis', $analysisData)
            ->with('chains', $task_chains)
            ->with('year', $year)
            ->with('monthno', $month);
    }


}
