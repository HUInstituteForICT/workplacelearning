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
use App\Tips\ActingCollector;
use App\Tips\CollectedDataStatisticVariable;
use App\Tips\CollectibleDataAggregator;
use App\Tips\DataCollectorContainer;
use App\Tips\DataUnitParser;
use App\Tips\Statistic;
use App\Tips\Tip;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

    public function showDetail(Request $request, $year, $month)
    {
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

        if($year === "all" || $month === "all") {
            $year = null;
            $month = null;
        }

        $var1 = new CollectedDataStatisticVariable();
        $var1->dataUnitMethod = "activitiesWithTimeslot";
        $var1->dataUnitParameterValue = "Dan ja toch";

        $var2 = new CollectedDataStatisticVariable();
        $var2->dataUnitMethod = "totalLearningActivities";

        $stat = new Statistic();
        $stat->statisticVariableOne = $var1;
        $stat->statisticVariableTwo = $var2;
        $stat->operator = Statistic::OPERATOR_DIVIDE;


        $ccCollector = new ActingCollector($year, $month, Auth::user()->getCurrentWorkplaceLearningPeriod());
        dump($ccCollector->totalLearningActivities());
        $dataCollector = new DataCollectorContainer($ccCollector);

        $stat->setDataCollector($dataCollector);
        dump($stat->calculate());

        $tip = new Tip();
        $tip->multiplyBy100 = true;
        $tip->tipText = "Percentage leermomenten in tijdslot/categorie Dan ja toch: :percentage%";
        $tip->threshold = 0.1;
        $tip->statistic = $stat;
        dump($tip->isApplicable());
        dump($tip->getTipText());

        dump((new CollectibleDataAggregator($ccCollector))->getInformation());


        die();



        //dump($dataCollector->getDataUnit("activitiesWithRP[person_label=Alleen]"));
        die();

        // TODO: add month year filter so we can show monthly stuff etc
//        $analysis = new ActingAnalysis(new ActingAnalysisCollector($year, $month));

        return view('pages.acting.analysis.detail')
            ->with('actingAnalysis', $analysis);
    }
}
