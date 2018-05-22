<?php


namespace App\Tips;


use App\Tips\Statistics\PredefinedStatistic;
use App\Tips\Statistics\StatisticCalculationResult;

class EvaluatedTip
{
    /**
     * @var Tip
     */
    private $tip;
    /**
     * @var array
     */
    private $calculationResults;
    /**
     * @var bool
     */
    private $passes;

    public function __construct(Tip $tip, array $calculationResults, bool $passes)
    {
        $this->tip = $tip;
        $this->calculationResults = $calculationResults;
        $this->passes = $passes;
    }

    public function getTip(): Tip
    {
        return $this->tip;
    }

    public function getCalculationResult(int $coupledStatisticId): StatisticCalculationResult
    {
        return $this->calculationResults[$coupledStatisticId];
    }

    public function isPassing(): bool
    {
        return $this->passes;
    }


    public function getTipText(): string
    {
        $tip = $this->tip;

        $tipText = $tip->tipText;

        $tip->coupledStatistics->each(function (TipCoupledStatistic $tipCoupledStatistic) use (&$tipText) {

            /** @var StatisticCalculationResult $result */
            $result = $this->getCalculationResult($tipCoupledStatistic->id);

            if ($result->hasPassed()) {
                $percentageValue = number_format($result->getResult() * 100) . '%';
                $tipText = str_replace(":statistic-{$tipCoupledStatistic->id}", $percentageValue, $tipText);
//                        number_format($calculationResult->getResult(), 3) . '%';
            }

            // TODO IMPLEMENT
//            if ($tipCoupledStatistic->statistic instanceof PredefinedStatistic) {
//                $entityName = array_map(function (StatisticCalculationResult $calculationResult) {
//                    return $calculationResult->getEntityName();
//                }, $this->getCalculationResult($tipCoupledStatistic->id));
//                $tipText = str_replace(":statistic-name-{$tipCoupledStatistic->id}",
//                    implode(', ', $entityNames), $tipText);
//
//            }
//            $tipText = str_replace(":statistic-{$tipCoupledStatistic->id}", implode(', ', $percentageValues), $tipText);

        });

        return $tipText;
    }

}