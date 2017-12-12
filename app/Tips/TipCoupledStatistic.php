<?php


namespace App\Tips;


use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @property int $id
 * @property int $tip_id
 * @property int $statistic_id
 * @property int $comparison_operator
 * @property float $threshold
 * @property boolean $multiplyBy100
 */
class TipCoupledStatistic extends Pivot
{
    const COMPARISON_OPERATOR_LESS_THAN = 0;
    const COMPARISON_OPERATOR_GREATER_THAN = 1;

    const COMPARISON_OPERATORS = [
        self::COMPARISON_OPERATOR_LESS_THAN    => ['type' => self::COMPARISON_OPERATOR_LESS_THAN, 'label' => '<'],
        self::COMPARISON_OPERATOR_GREATER_THAN => ['type' => self::COMPARISON_OPERATOR_GREATER_THAN, 'label' => '>'],
    ];
    public $timestamps = false;
    protected $fillable = ['tip_id', 'statistic_id', 'comparison_operator', 'threshold', 'multiplyBy100'];
    protected $table = 'tip_coupled_statistic';

}