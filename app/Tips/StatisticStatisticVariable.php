<?php


namespace App\Tips;

/**
 * @property Statistic $nestedStatistic
 */
class StatisticStatisticVariable extends StatisticVariable implements HasStatisticVariableValue
{
    protected static $singleTableType = "statisticstatistic";

    protected static $persisted = ['statistic_id'];

    /** @var DataCollectorContainer */
    private $dataCollector;


    /**
     * Set the dataCollector
     *
     * @param DataCollectorContainer $dataCollector
     */
    public function setDataCollector(DataCollectorContainer $dataCollector)
    {
        $this->dataCollector = $dataCollector;
    }

    public function nestedStatistic()
    {
        return $this->hasOne(Statistic::class, 'statistic_id');
    }

    /**
     * Get the value of the variable by calculating the nested statistic
     */
    public function getValue()
    {
        $this->nestedStatistic->setDataCollector($this->dataCollector);
        return $this->nestedStatistic->calculate();
    }
}