<?php


namespace App\Tips;

/**
 * @property Statistic $nestedStatistic
 * @property integer $nested_statistic_id
 */
class StatisticStatisticVariable extends StatisticVariable implements HasStatisticVariableValue
{
    protected static $singleTableType = "statisticstatistic";

    protected static $persisted = ['nested_statistic_id'];

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
        return $this->belongsTo(Statistic::class, 'nested_statistic_id');
    }

    /**
     * Get the value of the variable by calculating the nested statistic
     */
    public function getValue()
    {
        $this->nestedStatistic->setDataCollector($this->dataCollector);
        $value = $this->nestedStatistic->calculate();
        return $value;
    }
}