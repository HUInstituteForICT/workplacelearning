<?php


namespace App\Tips;


class StatisticService
{
    /**
     * @param array $data
     * @return Statistic
     */
    public function createStatistic(array $data) {

        $variableOne = $this->createStatisticVariable($data['statisticVariableOne'], $data['statisticVariableOneParameter']);
        $variableTwo = $this->createStatisticVariable($data['statisticVariableTwo'], $data['statisticVariableTwoParameter']);

        $statistic = new Statistic();

        $statistic->name = $data['name'];

        $statistic->educationProgramType = Statistic::EDUCATION_PROGRAM_TYPE_ACTING;
        $statistic->tip_id = 1;
        $statistic->operator = $data['operator'];

        $statistic->save();

        $statistic->statisticVariableOne()->save($variableOne);
        $statistic->statisticVariableTwo()->save($variableTwo);

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