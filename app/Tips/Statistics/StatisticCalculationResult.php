<?php

namespace App\Tips\Statistics;

class StatisticCalculationResult
{
    /** @var float $result Numeric result of a statistic calculation */
    private $result;

    /** @var string $entityName The name of entity instance of the calculation*/
    private $entityName;

    public function __construct($result, $entityName = null)
    {
        $this->result = $result;
        $this->entityName = $entityName;
    }

    /**
     * @return float
     */
    public function getResult(): float
    {
        return $this->result;
    }

    /**
     * @return string
     */
    public function getEntityName(): string
    {
        return $this->entityName;
    }
}