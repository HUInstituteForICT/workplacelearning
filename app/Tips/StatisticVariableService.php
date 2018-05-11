<?php


namespace App\Tips;


use App\Tips\Statistics\StatisticVariable;

class StatisticVariableService
{

    public function updateStatisticVariable(array $data, StatisticVariable $variable)
    {
        $variable->filters = $data['filters'];

        return $variable;
    }
}