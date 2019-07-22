<?php

namespace App\Tips\Services;

use App\Tips\Models\CustomStatistic;
use App\Tips\Models\PredefinedStatistic;
use App\Tips\Models\StatisticVariable;
use App\Tips\Statistics\Predefined\PredefinedStatisticInterface;

class StatisticService
{
    /** @var StatisticVariableService $statisticVariableService */
    private $statisticVariableService;

    public function __construct(StatisticVariableService $statisticVariableService)
    {
        $this->statisticVariableService = $statisticVariableService;
    }

    /**
     * @return CustomStatistic
     *
     * @throws \Exception
     */
    public function createStatistic(array $data): CustomStatistic
    {
        $statistic = new CustomStatistic();

        $statistic->statisticVariableOne = new StatisticVariable();
        $statistic->statisticVariableTwo = new StatisticVariable();
        $this->updateStatistic($statistic, $data);

        return $statistic;
    }

    /**
     * @return CustomStatistic
     *
     * @throws \Exception
     */
    public function updateStatistic(CustomStatistic $statistic, array $data): CustomStatistic
    {
        $variableOne = $this->statisticVariableService->updateStatisticVariable($data['statisticVariableOne'],
            $statistic->statisticVariableOne);
        $variableTwo = $this->statisticVariableService->updateStatisticVariable($data['statisticVariableTwo'],
            $statistic->statisticVariableTwo);

        $variableOne->save();
        $variableTwo->save();

        $statistic->name = $data['name'];

        $statistic->education_program_type = $data['education_program_type'];
        $statistic->select_type = $data['select_type'];
        $statistic->operator = self::getOperator($data['operator']);

        $statistic->statisticVariableOne()->associate($variableOne);
        $statistic->statisticVariableTwo()->associate($variableTwo);

        $statistic->save();

        return $statistic;
    }

    public function createPredefinedStatistic(string $predefinedStatisticClassName): PredefinedStatistic
    {
        $statistic = new PredefinedStatistic();

        /** @var PredefinedStatisticInterface $predefinedStatistic */
        $predefinedStatistic = new $predefinedStatisticClassName;

        $statistic->education_program_type = strtolower($predefinedStatistic->getEducationProgramType());
        $statistic->name = $predefinedStatistic->getName();
        $statistic->className = $predefinedStatisticClassName;

        $statistic->save();

        return $statistic;
    }

    /**
     * @return mixed
     * @throws \RuntimeException
     */
    private static function getOperator(int $operator)
    {
        if (!isset(CustomStatistic::OPERATORS[$operator])) {
            throw new \RuntimeException("Operator with id {$operator} not found in Statistic::OPERATORS");
        }

        return $operator;
    }
}
