<?php

namespace App\Tips\Models;

use Illuminate\Database\Eloquent\Model;
use Nanigans\SingleTableInheritance\SingleTableInheritanceTrait;

/**
 * App\Tips\Models\Statistic.
 *
 * @property int                                                                             $id                        The id of the statistic
 * @property string                                                                          $name                      The name of this statistic
 * @property string                                                                          $education_program_type    the education program type of this statistic. Some data is only available for certain types, therefore a distinction is necessary.
 * @property TipCoupledStatistic                                                             $pivot
 * @property string                                                                          $type
 * @property int|null                                                                        $operator
 * @property string|null                                                                     $select_type
 * @property int|null                                                                        $statistic_variable_one_id
 * @property int|null                                                                        $statistic_variable_two_id
 * @property \Illuminate\Database\Eloquent\Collection|\App\Tips\Models\TipCoupledStatistic[] $coupledStatistics
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tips\Models\Statistic whereEducationProgramType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tips\Models\Statistic whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tips\Models\Statistic whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tips\Models\Statistic whereOperator($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tips\Models\Statistic whereSelectType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tips\Models\Statistic whereStatisticVariableOneId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tips\Models\Statistic whereStatisticVariableTwoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tips\Models\Statistic whereType($value)
 * @mixin \Eloquent
 * @property string|null $className
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tips\Models\Statistic newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tips\Models\Statistic newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tips\Models\Statistic query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tips\Models\Statistic whereClassName($value)
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
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function coupledStatistics()
    {
        return $this->hasMany(TipCoupledStatistic::class, 'statistic_id');
    }
}
