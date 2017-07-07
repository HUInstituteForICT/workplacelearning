<?php
/**
 * This file (ActingAnalysisController.php) was created on 08/31/2016 at 14:15.
 * (C) Max Cassee
 * This project was commissioned by HU University of Applied Sciences.
 */

namespace App\Http\Controllers;

use App\Analysis\Acting\ActingAnalysis;
use App\Analysis\Acting\ActingAnalysisCollector;
use App\LearningActivityActing;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ActingAnalysisController extends Controller
{

    public function show()
    {
        if (Auth::user()->getCurrentWorkplaceLearningPeriod() === null || Auth::user()->getCurrentWorkplaceLearningPeriod()->getLastActivity(1)->count() === 0)
            return redirect()->route('home-acting')
                    ->withErrors(['Helaas, wij kunnen geen analyse uitvoeren als er nog geen activiteiten zijn ingevoerd.']);

        // TODO: add month year filter so we can show monthly stuff etc
        $analysis = new ActingAnalysis(new ActingAnalysisCollector());

        return view('pages.acting.analysis.choice')
            ->with('actingAnalysis', $analysis);
    }
}
