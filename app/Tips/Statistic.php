<?php


namespace App\Tips;


use App\EducationProgramType;
use Illuminate\Database\Eloquent\Model;

/**
 * @property StatisticVariable|HasStatisticVariableValue $statisticVariableOne
 * @property StatisticVariable|HasStatisticVariableValue $statisticVariableTwo
 * @property integer $id The id of the statistic
 * @property integer $operator the operator that will be used for the two statisticVariables
 * @property string $name The name of this statistic
 * @property EducationProgramType $educationProgramType the education program type of this statistic. Some data is only available for certain types, therefore a distinction is necessary.
 */
class Statistic extends Model
{
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

    // Disable timestamps
    public $timestamps = false;

    // Injected into StatisticVariables that use a dataCollector
    private $dataCollector;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tips() {
        return $this->belongsToMany(Tip::class, 'tip_coupled_statistic')
            ->using(TipCoupledStatistic::class)
            ->withPivot(['comparison_operator', 'threshold', 'multiplyBy100']);
    }

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
     * Relation to the EducationProgramType
     * Certain data is only available to certain education program types (acting, producing)
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function educationProgramType() {
        return $this->belongsTo(EducationProgramType::class, 'education_program_type_id', 'eptype_id');
    }

    /**
     * Calculate the value of this statistic
     *
     * @return float|int
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
        } catch(\ErrorException $exception) {
            if($exception->getMessage() === "Division by zero") {
                return 0;
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
     * Set the dataCollector used by certain StatisticVariables
     *
     * @param DataCollectorContainer $dataCollector
     */
    public function setDataCollector($dataCollector)
    {
        $this->dataCollector = $dataCollector;
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