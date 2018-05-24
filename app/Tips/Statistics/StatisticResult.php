<?php


namespace App\Tips\Statistics;

use App\Tips\TipCoupledStatistic;

/**
 * Class StatisticResult
 * Represents a generic result of a statistic. Is not necessarily a percentage-able value
 * @package App\Tips\Statistics
 */
class StatisticResult implements Resultable
{
    /** @var float $result Numeric result of a statistic calculation */
    private $result;

    /** @var string $entityName The name of entity instance of the calculation*/
    private $entityName;

    /** @var bool $passed Whether or not this statistic calculation result passed */
    private $passed = false;

    public function __construct($result, $entityName = null)
    {
        $this->result = $result;
        $this->entityName = $entityName;
    }

    public function getResultString(): string
    {
        return $this->result . '';
    }



    public function passes()
    {
        $this->passed = true;
    }

    public function failed()
    {
        $this->passed = false;
    }

    public function hasPassed(): bool
    {
        return $this->passed;
    }

    public function doThresholdComparison(int $threshold, int $operator)
    {
        if ($operator === TipCoupledStatistic::COMPARISON_OPERATOR_LESS_THAN) {
            $this->passed = $this->result < $threshold;
        } elseif ($operator === TipCoupledStatistic::COMPARISON_OPERATOR_GREATER_THAN) {
            $this->passed = $this->result > $threshold;
        }
    }

    public function getName(): string
    {
        return $this->entityName;
    }
}