<?php


namespace App\Tips\Statistics\Variables;

use App\Tips\DataCollectors\DataCollectorContainer;
use App\Tips\Statistics\CustomStatistic;

/**
 * @property CustomStatistic $nestedStatistic
 * @property integer $nested_statistic_id
 */
class StatisticStatisticVariable extends StatisticVariable implements HasStatisticVariableValue
{
    protected static $singleTableType = "statisticstatistic";

    protected static $persisted = ['nested_statistic_id'];

    public function nestedStatistic()
    {
        return $this->belongsTo(CustomStatistic::class, 'nested_statistic_id');
    }

    /**
     * Get the value of the variable by calculating the nested statistic
     * @throws \Exception
     */
    public function getValue()
    {
        $this->nestedStatistic->setDataCollectorContainer($this->dataCollectorContainer);
        $value = $this->nestedStatistic->calculate()->getResult();
        return $value;
    }
}