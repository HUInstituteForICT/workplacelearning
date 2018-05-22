<?php


namespace App\Tips\Statistics;

use App\Tips\CollectorDataAggregator;


/**
 * @property string name
 */
class PredefinedStatistic extends Statistic
{
    protected static $singleTableType = 'predefinedstatistic';

    protected static $persisted = ['name'];

    protected $appends = ['valueParameterDescription'];

}