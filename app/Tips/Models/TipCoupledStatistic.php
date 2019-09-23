<?php

namespace App\Tips\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Tips\Models\TipCoupledStatistic.
 *
 * @property int       $id
 * @property int       $tip_id
 * @property Tip       $tip
 * @property int       $statistic_id
 * @property int       $comparison_operator
 * @property float     $threshold
 * @property Statistic $statistic
 * @property mixed     $condition
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tips\Models\TipCoupledStatistic whereComparisonOperator($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tips\Models\TipCoupledStatistic whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tips\Models\TipCoupledStatistic whereStatisticId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tips\Models\TipCoupledStatistic whereThreshold($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tips\Models\TipCoupledStatistic whereTipId($value)
 * @mixin \Eloquent
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tips\Models\TipCoupledStatistic newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tips\Models\TipCoupledStatistic newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tips\Models\TipCoupledStatistic query()
 */
class TipCoupledStatistic extends Model
{
    const COMPARISON_OPERATOR_LESS_THAN = 0;
    const COMPARISON_OPERATOR_GREATER_THAN = 1;

    const COMPARISON_OPERATORS = [
        self::COMPARISON_OPERATOR_LESS_THAN    => ['type' => self::COMPARISON_OPERATOR_LESS_THAN, 'label' => '<'],
        self::COMPARISON_OPERATOR_GREATER_THAN => ['type' => self::COMPARISON_OPERATOR_GREATER_THAN, 'label' => '>'],
    ];
    public $timestamps = false;
    public $fillable = ['tip_id', 'statistic_id', 'comparison_operator', 'threshold'];
    protected $table = 'tip_coupled_statistic';
    public $appends = ['condition'];
    protected $hidden = ['statistic_id'];

    public function getConditionAttribute()
    {
        return $this->condition();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function statistic()
    {
        return $this->belongsTo(Statistic::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tip()
    {
        return $this->belongsTo(Tip::class);
    }

    /**
     * Get the condition for when this TipCoupledStatistic passes.
     *
     * @return string
     */
    public function condition()
    {
        if ($this->statistic instanceof PredefinedStatistic) {
            $expression = __('statistics.predefined-stats.'.$this->statistic->name).' ';
        } else {
            $expression = $this->statistic->name.' ';
        }

        $expression .= self::COMPARISON_OPERATORS[$this->comparison_operator]['label'].' ';
        $expression .= $this->threshold;

        return $expression;
    }
}
