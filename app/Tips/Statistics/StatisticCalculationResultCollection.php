<?php

namespace App\Tips\Statistics;

class StatisticCalculationResultCollection
{
    /** @var StatisticCalculationResult[] $results Array of Statistic results of a statistic calculation */
    private $results = [];

    /**
     * @return StatisticCalculationResult[]
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     * Add a new Statistic result
     * @param StatisticCalculationResult $result
     */
    public function addResult(StatisticCalculationResult $result)
    {
        $this->results[] = $result;
    }

    /**
     * @return StatisticCalculationResult
     * @throws \Exception
     */
    public function firstResult()
    {
        if (count($this->results) === 0) {
            throw new \Exception("Statistic calculation results collection is empty");
        }

        return $this->results[0];
    }

}