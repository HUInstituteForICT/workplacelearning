<?php

namespace App\Tips\Models;

use App\Tips\Statistics\Predefined\PredefinedStatisticInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

/**
 * App\Tips\Models\PredefinedStatistic.
 *
 * @property string      $name
 * @property string      $className
 * @property int         $id
 * @property string      $type
 * @property int|null    $operator
 * @property string      $education_program_type
 * @property string|null $select_type
 * @property int|null    $statistic_variable_one_id
 * @property int|null    $statistic_variable_two_id
 * @property mixed       $value_parameter_description
 *
 * @method static Builder|PredefinedStatistic whereEducationProgramType($value)
 * @method static Builder|PredefinedStatistic whereId($value)
 * @method static Builder|PredefinedStatistic whereName($value)
 * @method static Builder|PredefinedStatistic whereOperator($value)
 * @method static Builder|PredefinedStatistic whereSelectType($value)
 * @method static Builder|PredefinedStatistic whereStatisticVariableOneId($value)
 * @method static Builder|PredefinedStatistic whereStatisticVariableTwoId($value)
 * @method static Builder|PredefinedStatistic whereType($value)
 * @mixin \Eloquent
 *
 * @property Collection|TipCoupledStatistic[] $coupledStatistics
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tips\Models\PredefinedStatistic newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tips\Models\PredefinedStatistic newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tips\Models\PredefinedStatistic query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tips\Models\PredefinedStatistic whereClassName($value)
 */
class PredefinedStatistic extends Statistic
{
    protected static $singleTableType = 'predefinedstatistic';

    protected static $persisted = ['name', 'className'];

    protected $appends = ['valueParameterDescription'];

    public function getValueParameterDescriptionAttribute(): string
    {
        /** @var PredefinedStatisticInterface $statisticClass */
        $statisticClass = new $this->className();

        return $statisticClass->getResultDescription();

//        $data = PredefinedStatisticHelper::getData(); // ($this->educationProgramType->eptype_id === 1 ? PredefinedStatisticHelper::getData() : PredefinedStatisticHelper::getProducingData());
//        foreach ($data as $entry) {
//            if ($entry['name'] === $this->name) {
//                return $entry['valueParameterDescription'];
//            }
//        }
    }
}
