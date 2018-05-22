<?php


namespace App\Tips;


use App\Tips\Statistics\StatisticCalculationResult;
use App\Tips\Statistics\StatisticCalculationResultCollection;

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


        $tipCoupledStatisticResults = [];

        $passes = $tip->coupledStatistics->every(function (TipCoupledStatistic $tipCoupledStatistic) use (&$tipCoupledStatisticResults) {

            $tipCoupledStatisticResults[$tipCoupledStatistic->id] = $this->statisticCalculator->calculate($tipCoupledStatistic->statistic);

            return $this->coupledStatisticPasses($tipCoupledStatistic, $tipCoupledStatisticResults[$tipCoupledStatistic->id]);
        });


        return new EvaluatedTip($tip, $tipCoupledStatisticResults, $passes);
    }

    private function coupledStatisticPasses(TipCoupledStatistic $tipCoupledStatistic, StatisticCalculationResult $calculationResult):bool
    {
        // By default statistic fails unless one of the calculations passes. Mark the calculations that passed so we can loop over them later
        if ((int)$tipCoupledStatistic->comparison_operator === TipCoupledStatistic::COMPARISON_OPERATOR_LESS_THAN) {
            if ($calculationResult->getResult() < $tipCoupledStatistic->threshold) {
                $calculationResult->passes();
            } else {
                $calculationResult->failed();
            }
        } elseif ((int)$tipCoupledStatistic->comparison_operator === TipCoupledStatistic::COMPARISON_OPERATOR_GREATER_THAN) {
            if ($calculationResult->getResult() > $tipCoupledStatistic->threshold) {
                $calculationResult->passes();
            } else {
                $calculationResult->failed();
            }
        } else {
            throw new \RuntimeException("Unknown comparison operator with enum value {$tipCoupledStatistic->comparison_operator}");
        }

        return $calculationResult->hasPassed();
    }

    public function getResults(): array
    {
        return $this->cachedResultsCollection;
    }

}