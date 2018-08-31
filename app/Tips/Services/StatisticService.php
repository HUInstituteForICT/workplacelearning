<?php

namespace App\Tips\Services;

use App\Tips\Models\CustomStatistic;
use App\Tips\Models\PredefinedStatistic;
use App\Tips\Models\StatisticVariable;
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
     *
     * @return CustomStatistic
     *
     * @throws \Exception
     */
    public function createStatistic(array $data)
    {
        $statistic = new CustomStatistic();

        $statistic->statisticVariableOne = new StatisticVariable();
        $statistic->statisticVariableTwo = new StatisticVariable();
        $this->updateStatistic($statistic, $data);

        return $statistic;
    }

    /**
     * @param CustomStatistic $statistic
     * @param array           $data
     *
     * @return CustomStatistic
     *
     * @throws \Exception
     */
    public function updateStatistic(CustomStatistic $statistic, array $data)
    {
        $variableOne = $this->statisticVariableService->updateStatisticVariable($data['statisticVariableOne'], $statistic->statisticVariableOne);
        $variableTwo = $this->statisticVariableService->updateStatisticVariable($data['statisticVariableTwo'], $statistic->statisticVariableTwo);

        $variableOne->save();
        $variableTwo->save();

        $statistic->name = $data['name'];

        $statistic->education_program_type = $data['education_program_type'];
        $statistic->select_type = $data['select_type'];
        $statistic->operator = $this->getOperator($data['operator']);

        $statistic->statisticVariableOne()->associate($variableOne);
        $statistic->statisticVariableTwo()->associate($variableTwo);

        $statistic->save();

        return $statistic;
    }

    public function createPredefinedStatistic($methodName)
    {
        $statistic = new PredefinedStatistic();

        if (PredefinedStatisticHelper::isActingMethod($methodName)) {
            $statistic->education_program_type = 'acting';
            $statistic->name = collect(PredefinedStatisticHelper::getData())->first(function ($annotation) use ($methodName) {
                return $methodName === $annotation['method'] && 'Acting' === $annotation['epType'];
            })['name'];
        } elseif (PredefinedStatisticHelper::isProducingMethod($methodName)) {
            $statistic->education_program_type = 'producing';
            $statistic->name = collect(PredefinedStatisticHelper::getData())->first(function ($annotation) use ($methodName) {
                return $methodName === $annotation['method'] && 'Producing' === $annotation['epType'];
            })['name'];
        } else {
            throw new \RuntimeException("Method not found in a PredefinedStatisticCollector: {$methodName}");
        }

        $statistic->save();

        return $statistic;
    }

    private function getOperator($operator)
    {
        if (!isset(CustomStatistic::OPERATORS[$operator])) {
            throw new \RuntimeException("Operator with id {$operator} not found in Statistic::OPERATORS");
        }

        return $operator;
    }
}
