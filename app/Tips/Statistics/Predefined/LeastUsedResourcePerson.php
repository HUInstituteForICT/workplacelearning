<?php

declare(strict_types=1);

namespace App\Tips\Statistics\Predefined;

use App\ResourcePerson;
use App\Tips\Statistics\InvalidStatisticResult;
use App\Tips\Statistics\Resultable;
use App\Tips\Statistics\StatisticCalculationResult;

class LeastUsedResourcePerson extends BasePredefinedStatistic
{
    public function getName(): string
    {
        return 'Percentage the least used resource person is used';
    }

    public function getResultDescription(): string
    {
        return 'The resource person\'s label';
    }

    public function calculate(): Resultable
    {
        $leastUsedPerson = $this->wherePeriod(
            $this->learningPeriod->learningActivityActing()
                ->selectRaw('res_person_id, COUNT(res_person_id) as count')
                ->groupBy('res_person_id')
                ->orderBy('count', 'ASC')
                ->limit(1)
                ->getBaseQuery()
        )->first();

        $totalActivities = $this->wherePeriod($this->learningPeriod->learningActivityActing()->getBaseQuery())->count();

        if ($totalActivities === 0 || !$leastUsedPerson) {
            return new InvalidStatisticResult();
        }

        $resourcePerson = ResourcePerson::find($leastUsedPerson->res_person_id);

        $percentage = $leastUsedPerson->count / $totalActivities;

        return new StatisticCalculationResult($percentage, $resourcePerson->localizedLabel());
    }

    public function getEducationProgramType(): string
    {
        return self::ACTING_TYPE;
    }
}
