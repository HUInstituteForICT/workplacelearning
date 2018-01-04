<?php


namespace App\Tips;


use App\Tips\Statistics\StatisticCalculationResult;

/**
 * @property StatisticVariable|HasStatisticVariableValue $statisticVariableOne
 * @property StatisticVariable|HasStatisticVariableValue $statisticVariableTwo
 * @property integer $operator the operator that will be used for the two statisticVariables
 */
class Statistic extends RootStatistic
{
    protected static $persisted = ['operator', 'statistic_variable_one_id', 'statistic_variable_two_id'];


    /* Operators used for calculations */
    const OPERATOR_ADD = 0;
    const OPERATOR_SUBTRACT = 1;
    const OPERATOR_MULTIPLY = 2;
    const OPERATOR_DIVIDE = 3;

    const OPERATORS = [
        self::OPERATOR_ADD      => ["type" => Statistic::OPERATOR_ADD, "label" => "+"],
        self::OPERATOR_SUBTRACT => ["type" => Statistic::OPERATOR_SUBTRACT, "label" => "-"],
        self::OPERATOR_MULTIPLY => ["type" => Statistic::OPERATOR_MULTIPLY, "label" => "*"],
        self::OPERATOR_DIVIDE   => ["type" => Statistic::OPERATOR_DIVIDE, "label" => "/"],
    ];



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
     * @return StatisticCalculationResult
     * @throws \Exception
     */
    public function calculate()
    {
        $this->load(['statisticVariableOne', 'statisticVariableTwo']);
        $this->injectDependenciesIntoStatisticVariable($this->statisticVariableOne);
        $this->injectDependenciesIntoStatisticVariable($this->statisticVariableTwo);

        try {
            switch ($this->operator) {
                case self::OPERATOR_ADD:
                    return new StatisticCalculationResult($this->statisticVariableOne->getValue() + $this->statisticVariableTwo->getValue(),$this->name);
                case self::OPERATOR_SUBTRACT:
                    return new StatisticCalculationResult($this->statisticVariableOne->getValue() - $this->statisticVariableTwo->getValue() ,$this->name);
                case self::OPERATOR_MULTIPLY:
                    return new StatisticCalculationResult($this->statisticVariableOne->getValue() * $this->statisticVariableTwo->getValue(),$this->name);
                case self::OPERATOR_DIVIDE:
                    return new StatisticCalculationResult($this->statisticVariableOne->getValue() / $this->statisticVariableTwo->getValue(),$this->name);
            }
        } catch (\DivisionByZeroError $exception) {
            return new StatisticCalculationResult(0 ,$this->name);
        } catch(\ErrorException $exception) {
            if($exception->getMessage() === "Division by zero") {
                return new StatisticCalculationResult(0 ,$this->name);
            }
        }

        throw new \Exception("Missing Statistic operator for calculation");
    }

    private function injectDependenciesIntoStatisticVariable($statisticVariable)
    {
        if ($statisticVariable instanceof CollectedDataStatisticVariable
            || $statisticVariable instanceof StatisticStatisticVariable) {
            $statisticVariable->setDataCollector($this->dataCollector);
        }

        return $statisticVariable;
    }


    /**
     * Get the expression of this statistic as a string
     * Used for display purposes
     *
     * @return string
     */
    public function getStatisticCalculationExpression() {
        $expression = "";
        $expression .= "{$this->statisticVariableOne->name} ";
        if($this->statisticVariableOne instanceof CollectedDataStatisticVariable && $this->statisticVariableOne->dataUnitParameterValue !== null) {
            $expression .= "({$this->statisticVariableOne->dataUnitParameterValue}) ";
        }

        $expression .= self::OPERATORS[$this->operator]['label'] . " ";

        $expression .= "{$this->statisticVariableTwo->name} ";
        if($this->statisticVariableTwo instanceof CollectedDataStatisticVariable && $this->statisticVariableTwo->dataUnitParameterValue !== null) {
            $expression .= "({$this->statisticVariableTwo->dataUnitParameterValue}) ";
        }

        return $expression;
    }

}