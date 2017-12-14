<?php


namespace App\Tips;

use App\Tips\Statistics\StatisticCalculationResult;


/**
 * @property string name
 */
class PredefinedStatistic extends RootStatistic
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
        $result = $this->dataCollector->{$method}();

        return $result;
    }

    private function getMethod($name)
    {
        return collect((new CollectorDataAggregator($this->dataCollector))->getInformation())
            ->first(function (array $annotation) use ($name) {
                return $annotation['name'] === $name;
            });
    }

}