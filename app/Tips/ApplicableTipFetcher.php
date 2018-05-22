<?php


namespace App\Tips;


use App\Cohort;
use App\Tips\DataCollectors\Collector;

class ApplicableTipFetcher
{

    public function fetchForCohort(Cohort $cohort, Collector $collector): array
    {
        $cohort->load('tips.coupledStatistics.statistic');

        $applicableTips = $cohort->tips->filter(function (Tip $tip) use ($collector) {
            return $tip->showInAnalysis && $tip->isApplicable($collector);
        });

        return $applicableTips->all();
    }
}