<?php

namespace App\Tips\Services;

use App\Tips\Models\StatisticVariable;

class StatisticVariableService
{
    public function updateStatisticVariable(array $data, StatisticVariable $variable)
    {
        $variable->filters = $data['filters'];

        return $variable;
    }
}
