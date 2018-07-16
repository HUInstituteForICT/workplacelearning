<?php


namespace App\Tips\Services;

use App\Tips\EvaluatedTip;
use App\Tips\Models\PredefinedStatistic;
use App\Tips\Models\TipCoupledStatistic;
use App\Tips\StatisticCalculator;
use App\Tips\Statistics\Resultable;
use App\Tips\TextParameter;

class StatisticTriggerEvaluator
{
    /**
     * @var StatisticCalculator
     */
    private $statisticCalculator;

    public function __construct(StatisticCalculator $statisticCalculator)
    {
        $this->statisticCalculator = $statisticCalculator;
    }

    public function evaluate(EvaluatedTip $evaluatedTip): void
    {
        $tip = $evaluatedTip->getTip();
        /** @var Resultable[] $tipCoupledStatisticResults */
        $tipCoupledStatisticResults = [];

        $passes = $tip->coupledStatistics->every(
            function (TipCoupledStatistic $tipCoupledStatistic) use (&$tipCoupledStatisticResults) {
                $result = $this->statisticCalculator->calculate($tipCoupledStatistic->statistic);
                $tipCoupledStatisticResults[$tipCoupledStatistic->id] = $result;

                return $this->doesCoupledStatisticPass($tipCoupledStatistic, $result);
            }
        );

        $this->addParameters($evaluatedTip, $tipCoupledStatisticResults);
        $evaluatedTip->addEvaluationResult($passes);
    }

    /**
     * @param Resultable[]|array $tipCoupledStatisticResults
     * @return TextParameter[]|array
     */
    private function addParameters(EvaluatedTip $evaluatedTip, array $tipCoupledStatisticResults): void
    {
        $tipCoupledStatistics = $evaluatedTip->getTip()->coupledStatistics->all();

        /**
         * For each TipCoupledStatistic create the corresponding parameter
         * If the coupledStatistic is a predefined also add the name of the predefined statistic entity (e.g. category)
         */
        array_walk(
            $tipCoupledStatistics,
            function (TipCoupledStatistic $coupledStatistic) use ($evaluatedTip, $tipCoupledStatisticResults) {
                $id = $coupledStatistic->id;
                $resultable = $tipCoupledStatisticResults[$id];

                $evaluatedTip->addTextParameter(new TextParameter(":statistic-{$id}", $resultable->getResultString()));

                if ($coupledStatistic->statistic instanceof PredefinedStatistic) {
                    $evaluatedTip->addTextParameter(new TextParameter(":statistic-name-{$id}", $resultable->getName()));
                }
            }
        );
    }

    private function doesCoupledStatisticPass(TipCoupledStatistic $tipCoupledStatistic, Resultable $resultable): bool
    {
        // By default statistic fails unless one of the calculations passes. Mark the results that passed so we can loop over them later
        $resultable->doThresholdComparison(
            $tipCoupledStatistic->threshold,
            $tipCoupledStatistic->comparison_operator
        );

        return $resultable->hasPassed();
    }
}
