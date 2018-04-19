<?php


namespace App\Tips;


use App\EducationProgramType;
use App\Tips\Statistics\CustomStatistic;
use App\Tips\Statistics\PredefinedStatistic;
use App\Tips\Statistics\PredefinedStatisticHelper;
use App\Tips\Statistics\StatisticVariable;

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
     * @return CustomStatistic
     * @throws \Exception
     */
    public function createStatistic(array $data) {
        $statistic = new CustomStatistic();

        $statistic->statisticVariableOne = new StatisticVariable;
        $statistic->statisticVariableTwo = new StatisticVariable;
        $this->updateStatistic($statistic, $data);

        return $statistic;

    }

    /**
     * @param CustomStatistic $statistic
     * @param array $data
     * @return CustomStatistic
     * @throws \Exception
     */
    public function updateStatistic(CustomStatistic $statistic, array $data)
    {
        $variableOne = $this->statisticVariableService->updateStatisticVariable($data['statisticVariableOne'], $statistic->statisticVariableOne);
        $variableTwo = $this->statisticVariableService->updateStatisticVariable($data['statisticVariableTwo'], $statistic->statisticVariableTwo);

        $variableOne->save();
        $variableTwo->save();


        $statistic->name = $data['name'];

        $educationProgramType = (new EducationProgramType)->where('eptype_name', '=', $variableOne->type)->first();

        $statistic->educationProgramType()->associate($educationProgramType);
        $statistic->operator = $this->getOperator($data['operator']);

        $statistic->statisticVariableOne()->associate($variableOne);
        $statistic->statisticVariableTwo()->associate($variableTwo);

        $statistic->save();

        return $statistic;
    }

    public function createPredefinedStatistic($methodName) {
        $statistic = new PredefinedStatistic();

        if(PredefinedStatisticHelper::isActingMethod($methodName)) {
            $statistic->educationProgramType()->associate((new EducationProgramType)->where('eptype_name', '=', 'Acting')->firstOrFail());
            $statistic->name = collect(PredefinedStatisticHelper::getData())->first(function($annotation) use($methodName) {
                return $methodName === $annotation['method'] && $annotation['epType'] === 'Acting';
            })['name'];
        } elseif(PredefinedStatisticHelper::isProducingMethod($methodName)) {
            $statistic->educationProgramType()->associate((new EducationProgramType)->where('eptype_name', '=', 'Producing')->firstOrFail());
            $statistic->name = collect(PredefinedStatisticHelper::getData())->first(function($annotation) use($methodName) {
                return $methodName === $annotation['method']  && $annotation['epType'] === 'Producing';
            })['name'];
        } else {
            throw new \Exception("Method not found in a PredefinedStatisticCollector: {$methodName}");
        }

        $statistic->save();

        return $statistic;
    }

    private function getOperator($operator) {
        if(!isset(CustomStatistic::OPERATORS[$operator])) {
            throw new \Exception("Operator with id {$operator} not found in Statistic::OPERATORS");
        }

        return $operator;
    }

}