<?php


namespace App\Tips\Statistics;

use App\Tips\CollectorDataAggregator;


/**
 * @property string $name
 */
class PredefinedStatistic extends Statistic
{
    protected static $singleTableType = 'predefinedstatistic';

    protected static $persisted = ['name'];

    protected $appends = ['valueParameterDescription'];

    public function getValueParameterDescriptionAttribute()
    {
        $data = PredefinedStatisticHelper::getData();// ($this->educationProgramType->eptype_id === 1 ? PredefinedStatisticHelper::getData() : PredefinedStatisticHelper::getProducingData());
        foreach ($data as $entry) {
            if ($entry['name'] === $this->name) {
                return $entry['valueParameterDescription'];
            }
        }
    }

}