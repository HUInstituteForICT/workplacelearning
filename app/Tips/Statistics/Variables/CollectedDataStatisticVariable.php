<?php


namespace App\Tips\Statistics\Variables;

use App\Tips\DataUnit;

/**
 * @property string $dataUnitMethod The method to call for the collected data
 * @property string $dataUnitParameterValue The value for optional filtering
 * @property string name
 */
class CollectedDataStatisticVariable extends StatisticVariable implements HasStatisticVariableValue
{
    public $timestamps = false;
    protected static $singleTableType = "collecteddatastatistic";

    protected static $persisted = ['dataUnitMethod', 'dataUnitParameterValue'];


    /**
     * Get the value of the StatisticVariable
     *
     * @return float|int
     * @throws \Exception
     */
    public function getValue()
    {
        $dataUnit = new DataUnit($this->dataUnitMethod, $this->dataUnitParameterValue);

        return $this->dataCollectorContainer->getDataUnit($dataUnit);
    }

}