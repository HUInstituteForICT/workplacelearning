<?php

namespace App\Tips;

use App\Tips\Models\CustomStatistic;
use App\Tips\Models\PredefinedStatistic;
use App\Tips\Models\Statistic;
use App\Tips\Services\StatisticValueFetcher;
use App\Tips\Statistics\InvalidStatisticResult;
use App\Tips\Statistics\Predefined\BasePredefinedStatistic;
use App\Tips\Statistics\Resultable;
use App\Tips\Statistics\StatisticCalculationResult;
use DivisionByZeroError;
use Psr\Log\LoggerInterface;

class StatisticCalculator
{
    /**
     * @var StatisticValueFetcher
     */
    private $collector;
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(StatisticValueFetcher $collector, LoggerInterface $logger)
    {
        $this->collector = $collector;
        $this->logger = $logger;
    }

    /**
     * @throws \ErrorException
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
     * @throws \RuntimeException
     */
    private function calculateCustomStatistic(CustomStatistic $statistic): Resultable
    {
        $statistic->load(['statisticVariableOne', 'statisticVariableTwo']);

        $valueVarOne = $this->collector->getValueForVariable($statistic->statisticVariableOne,
            $statistic->education_program_type);
        $valueVarTwo = $this->collector->getValueForVariable($statistic->statisticVariableTwo,
            $statistic->education_program_type);

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
            if ('Division by zero' === $exception->getMessage()) {
                return new StatisticCalculationResult(0, $statistic->name);
            }
            throw $exception; // unexpected exception, bubble up
        }
        throw new \RuntimeException('Could not calculate customs statistic for some reason');
    }

    private function calculatePredefinedStatistic(PredefinedStatistic $statistic): Resultable
    {

        /** @var BasePredefinedStatistic $predefinedStatisticClass */
        $predefinedStatisticClass = new $statistic->className;

        $predefinedStatisticClass->setYearAndMonth($this->collector->year, $this->collector->month);
        $predefinedStatisticClass->setLearningPeriod($this->collector->learningPeriod);

        try {
            return $predefinedStatisticClass->calculate();
        } catch(\Exception $exception) {
            $this->logger->error('Unable to calculate predefined statistic', [$exception]);
            return new InvalidStatisticResult();
        }

//
//        /** @var StatisticCalculationResult $result */
//        $method = $this->getPredefinedMethodName($statistic->name)['method'];
//
//        return $this->collector->predefinedStatisticCollector->{$method}();
    }
}
