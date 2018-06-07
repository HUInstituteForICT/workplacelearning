<?php


namespace App\Tips;


use App\Tips\Statistics\Resultable;

class TipEvaluator
{

    /**
     * @var StatisticCalculator
     */
    private $statisticCalculator;
    /**
     * @var PeriodMomentCalculator
     */
    private $periodMomentCalculator;

    public function __construct(StatisticCalculator $statisticCalculator, PeriodMomentCalculator $periodMomentCalculator)
    {
        $this->statisticCalculator = $statisticCalculator;
        $this->periodMomentCalculator = $periodMomentCalculator;
    }

    public function evaluate(Tip $tip): EvaluatedTip
    {
        if (!$tip->showInAnalysis) {
            return new EvaluatedStatisticTip($tip, [], false);
        }

        if($tip->trigger === 'statistic') {
            return $this->evaluateStatisticTip($tip);
        }

        if($tip->trigger === 'moment') {
            return $this->evaluateMomentTip($tip);
        }

    }

    private function evaluateStatisticTip(Tip $tip): EvaluatedTip
    {
        /** @var Resultable[] $tipCoupledStatisticResults */
        $tipCoupledStatisticResults = [];

        $passes = $tip->coupledStatistics->every(function (TipCoupledStatistic $tipCoupledStatistic) use (&$tipCoupledStatisticResults) {

            $tipCoupledStatisticResults[$tipCoupledStatistic->id] = $this->statisticCalculator->calculate($tipCoupledStatistic->statistic);

            return $this->coupledStatisticPasses($tipCoupledStatistic, $tipCoupledStatisticResults[$tipCoupledStatistic->id]);
        });


        return new EvaluatedStatisticTip($tip, $tipCoupledStatisticResults, $passes);
    }

    private function evaluateMomentTip(Tip $tip): EvaluatedTip
    {
        $percentage = $this->periodMomentCalculator->getMomentAsPercentage();
        return new EvaluatedMomentTip($tip, $percentage, $tip->rangeStart <= $percentage && $percentage <= $tip->rangeEnd);
    }

    private function coupledStatisticPasses(TipCoupledStatistic $tipCoupledStatistic, Resultable $resultable): bool
    {
        // By default statistic fails unless one of the calculations passes. Mark the results that passed so we can loop over them later
        $resultable->doThresholdComparison(
            $tipCoupledStatistic->threshold,
            $tipCoupledStatistic->comparison_operator
        );

        return $resultable->hasPassed();
    }

}