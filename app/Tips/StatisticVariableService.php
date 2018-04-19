<?php


namespace App\Tips;


use App\Tips\Statistics\StatisticVariable;

class StatisticVariableService
{

    public function createStatisticVariable(array $data)
    {

        $variable = new StatisticVariable;
        $variable->type = $data['type'];
        $variable->selectType = $data['selectType'];
        $variable->filters = $data['filters'];

        return $variable;
    }

}