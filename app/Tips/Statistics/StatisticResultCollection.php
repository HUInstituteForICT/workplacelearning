<?php

namespace App\Tips\Statistics;

/**
 * Class StatisticCalculationResultCollection
 * Represents a result from a statistic, can be a percentage due to the calculation performed
 * @package App\Tips\Statistics
 */
class StatisticResultCollection implements Resultable
{
    /** @var StatisticCalculationResult[] $results Array of Statistic results of a statistic calculation */
    private $results = [];

    /**
     * Add a new Statistic result
     * @param StatisticCalculationResult $result
     */
    public function addResult(StatisticCalculationResult $result)
    {
        $this->results[] = $result;
    }


    public function getResultString(): string
    {
        $percentages = array_map(function (StatisticCalculationResult $result) {
            return $result->getResultString();
        }, $this->results);

        return implode(', ', $percentages);
    }

    public function hasPassed(): bool
    {
        $passed = true;
        array_walk($this->results, function(StatisticCalculationResult $result) use(&$passed) {
            if(!$result->hasPassed()) {
                $passed = false;
            }
        });
        return $passed;
    }

    public function doThresholdComparison(int $threshold, int $operator)
    {
        array_walk($this->results, function(Resultable $resultable) use($threshold, $operator) {
            $resultable->doThresholdComparison($threshold, $operator);
        });
    }

    public function getName(): string
    {
        $names = array_map(function(Resultable $resultable) {
            return $resultable->getName();
        }, $this->results);
        return implode(', ', $names);
    }
}