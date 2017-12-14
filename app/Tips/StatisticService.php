<?php


namespace App\Tips;


use App\EducationProgramType;
use App\Tips\Statistics\PredefinedStatisticHelper;

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

    public function createPredefinedStatistic($methodName) {
        $statistic = new PredefinedStatistic();

        if(PredefinedStatisticHelper::isActingMethod($methodName)) {
            $statistic->educationProgramType()->associate((new EducationProgramType)->where('eptype_name', '=', 'Acting')->firstOrFail());
            $statistic->name = collect(PredefinedStatisticHelper::getActingData())->first(function($annotation) use($methodName) {
                return $methodName === $annotation['method'];
            })['name'];
        } elseif(PredefinedStatisticHelper::isProducingMethod($methodName)) {
            $statistic->educationProgramType()->associate((new EducationProgramType)->where('eptype_name', '=', 'Producing')->firstOrFail());
            $statistic->name = collect(PredefinedStatisticHelper::getProducingData())->first(function($annotation) use($methodName) {
                return $methodName === $annotation['method'];
            })['name'];
        } else {
            throw new \Exception("Method not found in a PredefinedStatisticCollector: {$methodName}");
        }

        $statistic->save();

        return $statistic;
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