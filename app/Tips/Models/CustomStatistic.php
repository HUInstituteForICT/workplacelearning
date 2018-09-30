<?php

namespace App\Tips\Models;

/**
 * App\Tips\Models\CustomStatistic.
 *
 * @property StatisticVariable $statisticVariableOne
 * @property StatisticVariable $statisticVariableTwo
 * @property int               $operator                  the operator that will be used for the two statisticVariables
 * @property string            $select_type
 * @property string            $type
 * @property int               $id
 * @property string            $name
 * @property string            $education_program_type
 * @property int|null          $statistic_variable_one_id
 * @property int|null          $statistic_variable_two_id
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tips\Models\CustomStatistic whereEducationProgramType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tips\Models\CustomStatistic whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tips\Models\CustomStatistic whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tips\Models\CustomStatistic whereOperator($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tips\Models\CustomStatistic whereSelectType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tips\Models\CustomStatistic whereStatisticVariableOneId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tips\Models\CustomStatistic whereStatisticVariableTwoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tips\Models\CustomStatistic whereType($value)
 * @mixin \Eloquent
 *
 * @property \Illuminate\Database\Eloquent\Collection|\App\Tips\Models\TipCoupledStatistic[] $coupledStatistics
 */
class CustomStatistic extends Statistic
{
    const OPERATOR_ADD = 0;
    const OPERATOR_SUBTRACT = 1;

    /* Operators used for calculations */
    const OPERATOR_MULTIPLY = 2;
    const OPERATOR_DIVIDE = 3;
    const OPERATORS = [
        self::OPERATOR_ADD      => ['type' => CustomStatistic::OPERATOR_ADD, 'label' => '+'],
        self::OPERATOR_SUBTRACT => ['type' => CustomStatistic::OPERATOR_SUBTRACT, 'label' => '-'],
        self::OPERATOR_MULTIPLY => ['type' => CustomStatistic::OPERATOR_MULTIPLY, 'label' => '*'],
        self::OPERATOR_DIVIDE   => ['type' => CustomStatistic::OPERATOR_DIVIDE, 'label' => '/'],
    ];
    protected static $singleTableType = 'customstatistic';
    protected static $persisted = ['operator', 'statistic_variable_one_id', 'statistic_variable_two_id', 'select_type'];

    protected $hidden = ['statistic_variable_one_id', 'statistic_variable_two_id'];

    /**
     * Relation to first statisticVariable of this statistic
     * BelongsTo relation because the statistic should be the owning side.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function statisticVariableOne()
    {
        return $this->belongsTo(StatisticVariable::class, 'statistic_variable_one_id');
    }

    /**
     * Relation to second statisticVariable of this statistic
     * BelongsTo relation because the statistic should be the owning side.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function statisticVariableTwo()
    {
        return $this->belongsTo(StatisticVariable::class, 'statistic_variable_two_id');
    }
}
