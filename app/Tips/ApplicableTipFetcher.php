<?php


namespace App\Tips;


use App\Cohort;

class ApplicableTipFetcher
{

    /**
     * @var TipEvaluator
     */
    private $tipEvaluator;

    public function __construct(TipEvaluator $tipEvaluator)
    {
        $this->tipEvaluator = $tipEvaluator;
    }

    /**
     * @param Cohort $cohort
     * @return EvaluatedStatisticTip[]
     */
    public function fetchForCohort(Cohort $cohort): array
    {
        $cohort->load('tips.coupledStatistics.statistic');


        $applicableEvaluatedTips = $cohort->tips->map(function (Tip $tip) {
            return $this->tipEvaluator->evaluate($tip);
        })->filter(function(EvaluatedTip $evaluatedTip) {
            return $evaluatedTip->isPassing();
        });

        return $applicableEvaluatedTips->all();
    }
}