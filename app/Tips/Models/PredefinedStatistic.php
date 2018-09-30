<?php

namespace App\Tips\Models;

use App\Tips\Statistics\PredefinedStatisticHelper;

/**
 * App\Tips\Models\PredefinedStatistic.
 *
 * @property string      $name
 * @property int         $id
 * @property string      $type
 * @property int|null    $operator
 * @property string      $education_program_type
 * @property string|null $select_type
 * @property int|null    $statistic_variable_one_id
 * @property int|null    $statistic_variable_two_id
 * @property mixed       $value_parameter_description
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tips\Models\PredefinedStatistic whereEducationProgramType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tips\Models\PredefinedStatistic whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tips\Models\PredefinedStatistic whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tips\Models\PredefinedStatistic whereOperator($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tips\Models\PredefinedStatistic whereSelectType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tips\Models\PredefinedStatistic whereStatisticVariableOneId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tips\Models\PredefinedStatistic whereStatisticVariableTwoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tips\Models\PredefinedStatistic whereType($value)
 * @mixin \Eloquent
 *
 * @property \Illuminate\Database\Eloquent\Collection|\App\Tips\Models\TipCoupledStatistic[] $coupledStatistics
 */
class PredefinedStatistic extends Statistic
{
    protected static $singleTableType = 'predefinedstatistic';

    protected static $persisted = ['name'];

    protected $appends = ['valueParameterDescription'];

    public function getValueParameterDescriptionAttribute()
    {
        $data = PredefinedStatisticHelper::getData(); // ($this->educationProgramType->eptype_id === 1 ? PredefinedStatisticHelper::getData() : PredefinedStatisticHelper::getProducingData());
        foreach ($data as $entry) {
            if ($entry['name'] === $this->name) {
                return $entry['valueParameterDescription'];
            }
        }
    }
}
