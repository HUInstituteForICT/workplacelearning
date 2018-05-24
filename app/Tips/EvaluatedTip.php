<?php


namespace App\Tips;


use App\Tips\Statistics\PredefinedStatistic;
use App\Tips\Statistics\Resultable;
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

    public function getResultable(int $coupledStatisticId): Resultable
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
            $result = $this->getResultable($tipCoupledStatistic->id);

            if ($result->hasPassed()) {
                $tipText = str_replace(":statistic-{$tipCoupledStatistic->id}", $result->getResultString(), $tipText);
            }

            if ($tipCoupledStatistic->statistic instanceof PredefinedStatistic) {
                $nameString = $result->getName(); // Could be multiple names separated by commas

                $tipText = str_replace(":statistic-name-{$tipCoupledStatistic->id}", $nameString, $tipText);
            }
        });

        return $tipText;
    }

}