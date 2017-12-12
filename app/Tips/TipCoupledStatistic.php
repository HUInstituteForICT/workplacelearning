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
 * @property Statistic $statistic
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
    public $fillable = ['comparison_operator', 'threshold', 'multiplyBy100'];
    protected $table = 'tip_coupled_statistic';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function statistic() {
        return $this->belongsTo(Statistic::class);
    }

    public function ifExpression() {
        $expression = $this->statistic->name . ' ';
        $expression .= self::COMPARISON_OPERATORS[$this->comparison_operator]['label'] . ' ';
        $expression .= $this->threshold;
        return $expression;
    }

    public function passes($calculatedValue) {
        if($this->comparison_operator === self::COMPARISON_OPERATOR_LESS_THAN) {
            return $calculatedValue < $this->threshold;
        } elseif ($this->comparison_operator === self::COMPARISON_OPERATOR_GREATER_THAN) {
            return $calculatedValue > $this->threshold;
        }

        throw new \Exception("Unknown comparison operator with enum value {$this->comparison_operator}");
    }

}