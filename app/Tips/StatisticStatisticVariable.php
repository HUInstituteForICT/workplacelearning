<?php


namespace App\Tips;

/**
 * @property Statistic nestedStatistic
 */
class StatisticStatisticVariable extends StatisticVariable implements HasStatisticVariableValue
{
    protected static $singleTableType = "statisticstatistic";

    protected static $persisted = ['statistic_id'];

    /** @var DataCollector */
    private $dataCollector;
    private $year;
    private $month;

    /**
     * Set the dataCollector
     *
     * @param $dataCollector
     * @param $year
     * @param $month
     */
    public function setDataCollector(DataCollector $dataCollector, $year, $month)
    {
        $this->dataCollector = $dataCollector;
        $this->year = $year;
        $this->month = $month;
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
        return $this->nestedStatistic->calculate();
    }
}