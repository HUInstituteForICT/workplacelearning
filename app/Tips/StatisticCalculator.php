<?php


namespace App\Tips;


use App\Tips\DataCollectors\Collector;
use App\Tips\Statistics\CustomStatistic;
use App\Tips\Statistics\PredefinedStatistic;
use App\Tips\Statistics\Resultable;
use App\Tips\Statistics\Statistic;
use App\Tips\Statistics\StatisticCalculationResult;
use DivisionByZeroError;

class StatisticCalculator
{
    /**
     * @var Collector
     */
    private $collector;

    public function __construct(Collector $collector)
    {

        $this->collector = $collector;
    }

    /**
     * @throws \ErrorException
     * @param Statistic $statistic
     * @return Resultable
     */
    public function calculate(Statistic $statistic): Resultable
    {
        if ($statistic instanceof CustomStatistic) {
            return $this->calculateCustomStatistic($statistic);
        }

        if ($statistic instanceof PredefinedStatistic) {
            return $this->calculatePredefinedStatistic($statistic);
        }
    }

    /**
     * @throws \ErrorException
     * @param CustomStatistic $statistic
     * @return Resultable
     */
    private function calculateCustomStatistic(CustomStatistic $statistic): Resultable
    {
        $statistic->load(['statisticVariableOne', 'statisticVariableTwo']);


        $valueVarOne = $this->collector->getValueForVariable($statistic->statisticVariableOne, $statistic->education_program_type);
        $valueVarTwo = $this->collector->getValueForVariable($statistic->statisticVariableTwo, $statistic->education_program_type);

        try {
            switch ($statistic->operator) {
                case CustomStatistic::OPERATOR_ADD:
                    return new StatisticCalculationResult($valueVarOne + $valueVarTwo, $statistic->name);
                case CustomStatistic::OPERATOR_SUBTRACT:
                    return new StatisticCalculationResult($valueVarOne - $valueVarTwo, $statistic->name);
                case CustomStatistic::OPERATOR_MULTIPLY:
                    return new StatisticCalculationResult($valueVarOne * $valueVarTwo, $statistic->name);
                case CustomStatistic::OPERATOR_DIVIDE:
                    return new StatisticCalculationResult($valueVarOne / $valueVarTwo, $statistic->name);
            }
        } catch (DivisionByZeroError $exception) {
            return new StatisticCalculationResult(0, $statistic->name);
        } catch (\ErrorException $exception) {
            if ($exception->getMessage() === 'Division by zero') {
                return new StatisticCalculationResult(0, $statistic->name);
            }
            throw $exception; // unexpected exception, bubble up
        }
        throw new \RuntimeException('Could not calculate customs statistic for some reason');
    }

    private function calculatePredefinedStatistic(PredefinedStatistic $statistic): Resultable
    {
        /** @var StatisticCalculationResult $result */
        $method = $this->getPredefinedMethodName($statistic->name)['method'];
        return $this->collector->predefinedStatisticCollector->{$method}();
    }

    private function getPredefinedMethodName(string $name)
    {
        return collect((new CollectorDataAggregator($this->collector->predefinedStatisticCollector))->getInformation())
            ->first(function (array $annotation) use ($name) {
                return $annotation['name'] === $name;
            });

    }
}