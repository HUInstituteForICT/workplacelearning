<?php


namespace App\Tips;

/**
 * @property string dataUnitName
 */
class CollectedDataStatisticVariable extends StatisticVariable implements HasStatisticVariableValue
{
    public $timestamps = false;
    protected static $singleTableType = "collecteddatastatistic";

    protected static $persisted = ['dataUnitName'];

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

    /**
     * Get the value of the StatisticVariable
     *
     * @return float|int
     */
    public function getValue()
    {
        return $this->dataCollector->getDataUnit($this->dataUnitName);
    }

}