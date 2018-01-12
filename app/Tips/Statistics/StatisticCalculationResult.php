<?php

namespace App\Tips\Statistics;

class StatisticCalculationResult
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

    /**
     * @return float
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @return string
     */
    public function getEntityName()
    {
        return $this->entityName;
    }

    /**
     * Set that this statistic passed
     */
    public function passes()
    {
        $this->passed = true;
    }

    /**
     * Set that this statistic failed
     */
    public function failed()
    {
        $this->passed = false;
    }

    /**
     * Check if this statistic passed
     * @return bool
     */
    public function hasPassed()
    {
        return $this->passed;
    }
}