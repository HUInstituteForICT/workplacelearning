<?php


namespace App\Tips;


use Illuminate\Database\Eloquent\Model;

/**
 * @property StatisticVariable|HasStatisticVariableValue $statisticVariableOne
 * @property StatisticVariable|HasStatisticVariableValue $statisticVariableTwo
 * @property integer operator the operator that will be used for the two statisticVariables

 */
class Statistic extends Model
{
    /* Operators used for calculations */
    const OPERATOR_ADD = 0;
    const OPERATOR_SUBTRACT = 1;
    const OPERATOR_MULTIPLY = 2;
    const OPERATOR_DIVIDE = 3;

    // Disable timestamps
    public $timestamps = false;

    // Injected into StatisticVariables that use a dataCollector
    private $dataCollector;
    private $year;
    private $month;

    /**
     * Relation to first statisticVariable of this statistic
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function statisticVariableOne()
    {
        return $this->hasOne(StatisticVariable::class);
    }

    /**
     * Relation to second statisticVariable of this statistic
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function statisticVariableTwo()
    {
        return $this->hasOne(StatisticVariable::class);
    }

    /**
     * Calculate the value of this statistic
     *
     * @return float|int
     * @throws \Exception
     */
    public function calculate()
    {
        $this->statisticVariableOne = $this->injectDependenciesIntoStatisticVariable($this->statisticVariableOne);
        $this->statisticVariableTwo = $this->injectDependenciesIntoStatisticVariable($this->statisticVariableTwo);

        try {
            switch ($this->operator) {
                case self::OPERATOR_ADD:
                    return $this->statisticVariableOne->getValue() + $this->statisticVariableTwo->getValue();
                case self::OPERATOR_SUBTRACT:
                    return $this->statisticVariableOne->getValue() - $this->statisticVariableTwo->getValue();
                case self::OPERATOR_MULTIPLY:
                    return $this->statisticVariableOne->getValue() * $this->statisticVariableTwo->getValue();
                case self::OPERATOR_DIVIDE:
                    return $this->statisticVariableOne->getValue() / $this->statisticVariableTwo->getValue();
            }
        } catch (\DivisionByZeroError $exception) {
            return 0;
        }

        throw new \Exception("Missing Statistic operator for calculation");
    }

    private function injectDependenciesIntoStatisticVariable($statisticVariable)
    {
        if ($statisticVariable instanceof CollectedDataStatisticVariable
            || $statisticVariable instanceof StatisticStatisticVariable) {
            $statisticVariable->setDataCollector($this->dataCollector, $this->year, $this->month);
        }

        return $statisticVariable;
    }

    /**
     * Set the dataCollector used by certain StatisticVariables
     *
     * @param $dataCollector
     * @param $year
     * @param $month
     */
    public function setDataCollector($dataCollector, $year, $month)
    {
        $this->dataCollector = $dataCollector;
        $this->year = $year;
        $this->month = $month;
    }

}