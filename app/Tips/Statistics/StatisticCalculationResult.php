<?php

namespace App\Tips\Statistics;

use App\Tips\Models\TipCoupledStatistic;

class StatisticCalculationResult implements Resultable
{
    /** @var float $result Numeric result of a statistic calculation */
    private $result;

    /** @var string $entityName The name of entity instance of the calculation */
    private $entityName;

    /** @var bool $passed Whether or not this statistic calculation result passed */
    private $passed = false;

    public function __construct($result, $entityName = null)
    {
        $this->result = $result;
        $this->entityName = $entityName;
    }

    /**
     * Check if this statistic passed.
     */
    public function hasPassed(): bool
    {
        return $this->passed;
    }

    public function getResultString(): string
    {
        return number_format($this->result * 100).'%';
    }

    public function doThresholdComparison(float $threshold, int $operator): void
    {
        if (TipCoupledStatistic::COMPARISON_OPERATOR_LESS_THAN === $operator) {
            $this->passed = $this->result < $threshold;
        } elseif (TipCoupledStatistic::COMPARISON_OPERATOR_GREATER_THAN === $operator) {
            $this->passed = $this->result > $threshold;
        }
    }

    public function getName(): string
    {
        return $this->entityName;
    }
}
