<?php

namespace App\Tips\Models;


use Illuminate\Database\Eloquent\Model;
use Nanigans\SingleTableInheritance\SingleTableInheritanceTrait;

/**
 * @property integer $id The id of the statistic
 * @property string $name The name of this statistic
 * @property string $education_program_type the education program type of this statistic. Some data is only available for certain types, therefore a distinction is necessary.
 * @property TipCoupledStatistic $pivot
 */
class Statistic extends Model
{
    use SingleTableInheritanceTrait;

    protected $table = 'statistics';

    protected static $singleTableTypeField = 'type';

    protected static $singleTableSubclasses = [CustomStatistic::class, PredefinedStatistic::class];

    protected static $persisted = ['name', 'education_program_type'];


    // Disable timestamps
    public $timestamps = false;

    /**
     * @throws \Exception
     */
    public function calculate()
    {
        throw new \RuntimeException('Should be called in subclass');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function coupledStatistics() {
        return $this->hasMany(TipCoupledStatistic::class, 'statistic_id');
    }

}