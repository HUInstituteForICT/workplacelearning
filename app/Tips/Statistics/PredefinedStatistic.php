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

    protected $appends = ['valueParameterDescription'];


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

    public function getValueParameterDescriptionAttribute()
    {
        $data = ($this->educationProgramType->eptype_id === 1 ? PredefinedStatisticHelper::getActingData() : PredefinedStatisticHelper::getProducingData());
        foreach ($data as $entry) {
            if ($entry['name'] === $this->name) {
                return $entry['valueParameterDescription'];
            }
        }
    }

}