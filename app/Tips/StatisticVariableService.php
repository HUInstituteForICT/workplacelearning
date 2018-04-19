<?php


namespace App\Tips;


use App\Tips\Statistics\StatisticVariable;

class StatisticVariableService
{

    public function updateStatisticVariable(array $data, StatisticVariable $variable
    )
    {


        $variable->type = $data['type'];
        $variable->selectType = $data['selectType'];
        $variable->filters = $data['filters'];

        return $variable;
    }


    public function getStatisticVariableValue(StatisticVariable $statisticVariable)
    {

    }
}