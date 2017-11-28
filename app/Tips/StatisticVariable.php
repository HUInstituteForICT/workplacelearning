<?php


namespace App\Tips;


use Illuminate\Database\Eloquent\Model;
use Nanigans\SingleTableInheritance\SingleTableInheritanceTrait;


class StatisticVariable extends Model
{
    use SingleTableInheritanceTrait;

    protected static $singleTableTypeField = 'type';
    protected static $persisted = ['name', 'statistic_id'];
    protected static $singleTableSubclasses = [StatisticStatisticVariable::class, CollectedDataStatisticVariable::class];
    protected $table = "statistic_variables";

    /**
     * The statistic this variable is a part of for the calculation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function statistic()
    {
        return $this->belongsTo(Statistic::class, 'statistic_id');
    }

}