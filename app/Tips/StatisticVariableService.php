<?php


namespace App\Tips;


class StatisticVariableService
{
    public function __construct()
    {

    }

    public function createStatisticVariable(array $data, $parameter = null) {
        switch($data['type']) {
            case (new CollectedDataStatisticVariable())->getType():
                return $this->createCollectedDataStatisticVariable($data['method'], $parameter);
            case (new StatisticStatisticVariable())->getType():
                return $this->createStatisticStatisticVariable((new Statistic)->find($data['id']));
        }

        throw new \Exception("Unknown statistic variable type: {$data['type']}");
    }

    private function createStatisticStatisticVariable(Statistic $statistic) {
        $variable = new StatisticStatisticVariable();
        $variable->nestedStatistic()->associate($statistic);

        return $variable;
    }

    /**
     * @param string $name
     * @param string $method
     * @param string $parameter
     * @return CollectedDataStatisticVariable
     */
    private function createCollectedDataStatisticVariable($method, $parameter) {
        $variable = new CollectedDataStatisticVariable();
        $variable->dataUnitMethod = $method;
        $variable->dataUnitParameterValue = $parameter;

        return $variable;
    }
}