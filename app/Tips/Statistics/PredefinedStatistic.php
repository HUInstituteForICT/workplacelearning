<?php


namespace App\Tips\Statistics;

use App\Tips\CollectorDataAggregator;


/**
 * @property string name
 */
class PredefinedStatistic extends Statistic
{
    protected static $singleTableType = 'predefinedstatistic';

    protected static $persisted = ['name', 'education_program_type_id'];


    /**
     * @return StatisticCalculationResult
     */
    public function calculate()
    {
        /** @var StatisticCalculationResult $result */
        $method = $this->getMethod($this->name)['method'];
        $result = $this->dataCollectorContainer->getPredefinedCollector()->{$method}();

        return $result;
    }

    private function getMethod($name)
    {
        return collect((new CollectorDataAggregator($this->dataCollectorContainer->getPredefinedCollector()))->getInformation())
            ->first(function (array $annotation) use ($name) {
                return $annotation['name'] === $name;
            });
    }

}