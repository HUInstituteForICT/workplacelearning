<?php

declare(strict_types=1);

namespace App\Tips\Services;

use App\Cohort;
use App\Tips\EvaluatedTip;
use App\Tips\EvaluatedTipInterface;
use App\Tips\Models\Tip;

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
     * @return EvaluatedTip[]
     */
    public function fetchForCohort(Cohort $cohort): array
    {
        $cohort->load('tips.coupledStatistics.statistic');

        $applicableEvaluatedTips = $cohort->tips->map(function (Tip $tip) {
            return $this->tipEvaluator->evaluate($tip);
        })->filter(function (EvaluatedTipInterface $evaluatedTip) {
            return $evaluatedTip->isPassing();
        });

        return $applicableEvaluatedTips->all();
    }
}
