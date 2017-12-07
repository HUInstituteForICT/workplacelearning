<?php


namespace App\Tips;


use Illuminate\Database\Eloquent\Model;
use Nanigans\SingleTableInheritance\SingleTableInheritanceTrait;

/**
 * @property string $name
 */
class StatisticVariable extends Model
{
    use SingleTableInheritanceTrait;

    protected static $singleTableTypeField = 'type';
    protected static $persisted = ['statistic_id', 'name'];
    protected static $singleTableSubclasses = [StatisticStatisticVariable::class, CollectedDataStatisticVariable::class];
    protected $table = "statistic_variables";

    public $timestamps = false;

    public function getType()
    {
        return static::$singleTableType;
    }

}