<?php

declare(strict_types=1);
/**
 * This file (ProducingAnalysisController.php) was created on 08/31/2016 at 14:15.
 * (C) Max Cassee
 * This project was commissioned by HU University of Applied Sciences.
 */

namespace App\Http\Controllers;

use App\Analysis\Producing\ProducingAnalysis;
use App\Analysis\Producing\ProducingAnalysisCollector;
use App\Interfaces\ProgressRegistrySystemServiceInterface;
use App\Repository\Eloquent\SavedLearningItemRepository;
use App\Services\CurrentPeriodResolver;
use App\Tips\Services\ApplicableTipFetcher;
use App\Tips\Services\TipPicker;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\App;
use IntlDateFormatter;

class ProducingAnalysisController
{
    /**
     * @var CurrentPeriodResolver
     */
    private $currentPeriodResolver;
    /**
     * @var Redirector
     */
    private $redirector;

    /**
     * @var ProgressRegistrySystemServiceInterface
     */
    private $progressRegistrySystemService;

//    private $savedLearningItemRepository;



    public function __construct(CurrentPeriodResolver $currentPeriodResolver, Redirector $redirector, ProgressRegistrySystemServiceInterface $progressRegistrySystemService)
    {
        $this->currentPeriodResolver = $currentPeriodResolver;
        $this->redirector = $redirector;
//        $this->savedLearningItemRepository = $savedLearningItemRepository;
        $this->progressRegistrySystemService = $progressRegistrySystemService;
    }

    public function showChoiceScreen(ProducingAnalysisCollector $producingAnalysisCollector)
    {
        $period = $this->currentPeriodResolver->getPeriod();

        // Check if for the workplace the user has hours registered
        if (!$period->hasLoggedHours()) {
            return $this->redirector->route('home-producing')->withErrors([__('notifications.generic.nointernshipregisteredactivities')]);
        }

        $start = $period->startdate->modify('first day of this month')->format('Y-m-d');
        $end = $period->enddate->format('Y-m-d');

        return view('pages.producing.analysis.choice', [
            'period'    => $period,
            'numdays'   => $producingAnalysisCollector->getFullWorkingDays('all', 'all'),
            'start'     => strtotime($start),
            'end'       => strtotime($end),
            'formatter' => new IntlDateFormatter(
                App::getLocale(),
                IntlDateFormatter::GREGORIAN,
                IntlDateFormatter::NONE,
                null,
                null,
                'MMMM YYYY'
            ),
        ]);
    }

    public function showDetail(
        $year,
        $month,
        ApplicableTipFetcher $applicableTipFetcher,
        TipPicker $tipPicker,
        ProducingAnalysis $producingAnalysis
    ) {
        // Create new Analysis for the producing student
        $producingAnalysis->buildData($year, $month);

        $period = $this->currentPeriodResolver->getPeriod();

        $applicableEvaluatedTips = $applicableTipFetcher->fetchForCohort($period->cohort);

        $evaluatedTips = $tipPicker->pick($applicableEvaluatedTips, 3);
        $tipPicker->markTipsViewed($evaluatedTips);

        $savedTips = [];
        foreach ($evaluatedTips as $tip) {
            $savedTips[$tip->getTip()->id] = $this->progressRegistrySystemService->savedLearningItemExists('tip', $tip->getTip()->id,
                $period->student->student_id);
        }

        return view('pages.producing.analysis.detail', [
            'evaluatedTips'     => $evaluatedTips,
            'producingAnalysis' => $producingAnalysis,
            'analysis'          => $producingAnalysis->analysisData,
            'savedTips'         => $savedTips
        ]);
    }
}
