<?php


namespace App\Tips;


use App\EducationProgramType;

class StatisticService
{
    /** @var StatisticVariableService $statisticVariableService */
    private $statisticVariableService;

    public function __construct(StatisticVariableService $statisticVariableService)
    {
        $this->statisticVariableService = $statisticVariableService;
    }

    /**
     * @param array $data
     * @return Statistic
     */
    public function createStatistic(array $data) {

        $variableOne = $this->statisticVariableService->createStatisticVariable($data['statisticVariableOne'], $data['statisticVariableOneParameter']);
        $variableTwo = $this->statisticVariableService->createStatisticVariable($data['statisticVariableTwo'], $data['statisticVariableTwoParameter']);

        $variableOne->save();
        $variableTwo->save();

        $statistic = new Statistic();

        $statistic->name = $data['name'];

        $statistic->educationProgramType()->associate($this->getEducationProgramType($data['educationProgramTypeId']));
        $statistic->operator = $this->getOperator($data['operator']);

        $statistic->statisticVariableOne()->associate($variableOne);
        $statistic->statisticVariableTwo()->associate($variableTwo);

        $statistic->save();

    }

    private function getOperator($operator) {
        if(!isset(Statistic::OPERATORS[$operator])) {
            throw new \Exception("Operator with id {$operator} not found in Statistic::OPERATORS");
        }

        return $operator;
    }

    private function getEducationProgramType($educationProgramTypeId) {
        $programType = (new EducationProgramType)->findOrFail($educationProgramTypeId);
        return $programType;
    }


}