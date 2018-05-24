<?php


namespace App\Tips;


use App\Tips\Statistics\Resultable;
use App\Tips\Statistics\StatisticCalculationResult;

class TipEvaluator
{
    /**
     * The result will be newly cached after every call to the "isApplicable". This to counteract recalculating on a "getTipText" call.
     * We can safely assume the "getTipText" call immediately follows the "isApplicable" call.
     * @var StatisticCalculationResult[] $cachedResultsCollection
     */
    private $cachedResultsCollection;

    /**
     * @var StatisticCalculator
     */
    private $statisticCalculator;

    public function __construct(StatisticCalculator $statisticCalculator)
    {
        $this->statisticCalculator = $statisticCalculator;
    }

    public function evaluate(Tip $tip): EvaluatedTip
    {
        if (!$tip->showInAnalysis) {
            return new EvaluatedTip($tip, [], false);
        }


        /** @var Resultable[] $tipCoupledStatisticResults */
        $tipCoupledStatisticResults = [];

        $passes = $tip->coupledStatistics->every(function (TipCoupledStatistic $tipCoupledStatistic) use (&$tipCoupledStatisticResults) {

            $tipCoupledStatisticResults[$tipCoupledStatistic->id] = $this->statisticCalculator->calculate($tipCoupledStatistic->statistic);

            return $this->coupledStatisticPasses($tipCoupledStatistic, $tipCoupledStatisticResults[$tipCoupledStatistic->id]);
        });


        return new EvaluatedTip($tip, $tipCoupledStatisticResults, $passes);
    }

    private function coupledStatisticPasses(TipCoupledStatistic $tipCoupledStatistic, Resultable $resultable): bool
    {
        // By default statistic fails unless one of the calculations passes. Mark the results that passed so we can loop over them later
        $resultable->doThresholdComparison(
            (int)$tipCoupledStatistic->threshold,
            (int)$tipCoupledStatistic->comparison_operator
        );

        return $resultable->hasPassed();
    }

    public function getResults(): array
    {
        return $this->cachedResultsCollection;
    }

}