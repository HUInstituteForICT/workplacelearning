<?php

declare(strict_types=1);
/**
 * This file (ActingAnalysisController.php) was created on 08/31/2016 at 14:15.
 * (C) Max Cassee
 * This project was commissioned by HU University of Applied Sciences.
 */

namespace App\Http\Controllers;

use App\Analysis\Acting\ActingAnalysis;
use App\Analysis\Acting\ActingAnalysisCollector;
use App\Interfaces\ProgressRegistrySystemServiceInterface;
use App\Repository\Eloquent\SavedLearningItemRepository;
use App\Services\CurrentPeriodResolver;
use App\Tips\Services\ApplicableTipFetcher;
use App\Tips\Services\TipPicker;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\App;
use IntlDateFormatter;

class ActingAnalysisController
{
    /**
     * @var CurrentPeriodResolver
     */
    private $currentPeriodResolver;

    /**
     * @var Redirector
     */
    private $redirector;

//    private $savedLearningItemRepository;

    /**
     * @var ProgressRegistrySystemServiceInterface
     */
    private $progressRegistrySystemService;

    public function __construct(
        CurrentPeriodResolver $currentPeriodResolver,
        Redirector $redirector,
        ProgressRegistrySystemServiceInterface $progressRegistrySystemService
//        SavedLearningItemRepository $savedLearningItemRepository
    ) {
        $this->currentPeriodResolver = $currentPeriodResolver;
        $this->redirector = $redirector;
        $this->progressRegistrySystemService = $progressRegistrySystemService;
//        $this->savedLearningItemRepository = $savedLearningItemRepository;
    }

    public function showChoiceScreen()
    {
        $period = $this->currentPeriodResolver->getPeriod();
        // Check if for the workplace the user has hours registered
        if (!$period->hasLoggedHours()) {
            return $this->redirector->route('home-acting')->withErrors(__('notifications.generic.nointernshipregisteredactivities'));
        }

        $start = $period->startdate->modify('first day of this month')->format('Y-m-d');
        $end = $period->enddate->format('Y-m-d');

        return view('pages.acting.analysis.choice', [
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
        TipPicker $tipPicker
    ) {
        $period = $this->currentPeriodResolver->getPeriod();

        if ($period->learningActivityActing->count() === 0) {
            return $this->redirector->route('home-acting')
                ->withErrors([__('analysis.no-activity')]);
        }

        // The analysis for the charts etc.
        // Create Chart classes for each chart displayed
        $analysis = new ActingAnalysis(new ActingAnalysisCollector($year, $month));

        $applicableEvaluatedTips = $applicableTipFetcher->fetchForCohort($period->cohort);

        $evaluatedTips = $tipPicker->pick($applicableEvaluatedTips, 3);
        $tipPicker->markTipsViewed($evaluatedTips);


        $savedTips = [];
        foreach ($evaluatedTips as $tip) {
            $savedTips[$tip->getTip()->id] = $this->progressRegistrySystemService->savedLearningItemExists('tip', $tip->getTip()->id,
                $period->student->student_id);
        }

        return view('pages.acting.analysis.detail', [
            'evaluatedTips'  => $evaluatedTips,
            'actingAnalysis' => $analysis,
            'savedTips'      => $savedTips,
        ]);
    }
}
