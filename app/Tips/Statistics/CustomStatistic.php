<?php


namespace App\Tips\Statistics;


use DivisionByZeroError;
use Illuminate\Support\Facades\Auth;

/**
 * @property StatisticVariable $statisticVariableOne
 * @property StatisticVariable $statisticVariableTwo
 * @property integer $operator the operator that will be used for the two statisticVariables
 */
class CustomStatistic extends Statistic
{
    const OPERATOR_ADD = 0;
    const OPERATOR_SUBTRACT = 1;


    /* Operators used for calculations */
    const OPERATOR_MULTIPLY = 2;
    const OPERATOR_DIVIDE = 3;
    const OPERATORS = [
        self::OPERATOR_ADD      => ["type" => CustomStatistic::OPERATOR_ADD, "label" => "+"],
        self::OPERATOR_SUBTRACT => ["type" => CustomStatistic::OPERATOR_SUBTRACT, "label" => "-"],
        self::OPERATOR_MULTIPLY => ["type" => CustomStatistic::OPERATOR_MULTIPLY, "label" => "*"],
        self::OPERATOR_DIVIDE   => ["type" => CustomStatistic::OPERATOR_DIVIDE, "label" => "/"],
    ];
    protected static $singleTableType = 'customstatistic';
    protected static $persisted = ['operator', 'statistic_variable_one_id', 'statistic_variable_two_id', 'select_type'];

    protected $hidden = ['statistic_variable_one_id', 'statistic_variable_two_id'];

    /**
     * Relation to first statisticVariable of this statistic
     * BelongsTo relation because the statistic should be the owning side
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function statisticVariableOne()
    {
        return $this->belongsTo(StatisticVariable::class, 'statistic_variable_one_id');
    }

    /**
     * Relation to second statisticVariable of this statistic
     * BelongsTo relation because the statistic should be the owning side
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function statisticVariableTwo()
    {
        return $this->belongsTo(StatisticVariable::class, 'statistic_variable_two_id');
    }

    /**
     * Calculate the value of this statistic
     *
     * @return StatisticCalculationResultCollection
     * @throws \Exception
     */
    public function calculate()
    {
        $this->load(['statisticVariableOne', 'statisticVariableTwo']);

        $this->statisticVariableOne->setCollector($this->collector);
        $this->statisticVariableTwo->setCollector($this->collector);
        $this->statisticVariableOne->statistic = $this;
        $this->statisticVariableTwo->statistic = $this;

        $resultCollection = new StatisticCalculationResultCollection();
        try {
            switch ($this->operator) {
                case self::OPERATOR_ADD:
                    $resultCollection->addResult(new StatisticCalculationResult($this->statisticVariableOne->getValue() + $this->statisticVariableTwo->getValue(),
                        $this->name));
                    break;
                case self::OPERATOR_SUBTRACT:
                    $resultCollection->addResult(new StatisticCalculationResult($this->statisticVariableOne->getValue() - $this->statisticVariableTwo->getValue(),
                        $this->name));
                    break;
                case self::OPERATOR_MULTIPLY:
                    $resultCollection->addResult(new StatisticCalculationResult($this->statisticVariableOne->getValue() * $this->statisticVariableTwo->getValue(),
                        $this->name));
                    break;
                case self::OPERATOR_DIVIDE:
                    $resultCollection->addResult(new StatisticCalculationResult($this->statisticVariableOne->getValue() / $this->statisticVariableTwo->getValue(),
                        $this->name));
                    break;
            }



        } catch (\DivisionByZeroError $exception) {
            $resultCollection->addResult(new StatisticCalculationResult(0, $this->name));
        } catch (\ErrorException $exception) {
            if($exception->getMessage() === 'Division by zero') {
                $resultCollection->addResult(new StatisticCalculationResult(0, $this->name));
            }
        }

        return $resultCollection;
    }


    /**
     * Get the expression of this statistic as a string
     * Used for display purposes
     *
     * @return string
     */
    public function getStatisticCalculationExpression()
    {
        $expression = "Build again...?";

        $expression .= self::OPERATORS[$this->operator]['label'] . " ";

        return $expression;
    }

}