<?php


namespace App\Tips;

/**
 * @property string $dataUnitMethod The method to call for the collected data
 * @property string $dataUnitParameterValue The value for optional filtering
 */
class CollectedDataStatisticVariable extends StatisticVariable implements HasStatisticVariableValue
{
    public $timestamps = false;
    protected static $singleTableType = "collecteddatastatistic";

    protected static $persisted = ['dataUnitMethod', 'dataUnitParameterValue'];

    /** @var DataCollectorContainer */
    private $dataCollector;


    /**
     * Set the dataCollector
     *
     * @param $dataCollector
     */
    public function setDataCollector(DataCollectorContainer $dataCollector)
    {
        $this->dataCollector = $dataCollector;
    }

    /**
     * Get the value of the StatisticVariable
     *
     * @return float|int
     */
    public function getValue()
    {
        $dataUnit = new DataUnit($this->dataUnitMethod, $this->dataUnitParameterValue);

        return $this->dataCollector->getDataUnit($dataUnit);
    }

}