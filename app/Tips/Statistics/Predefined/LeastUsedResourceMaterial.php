<?php

namespace App\Tips\Statistics\Predefined;

use App\ResourceMaterial;
use App\Tips\Statistics\InvalidStatisticResult;
use App\Tips\Statistics\Resultable;
use App\Tips\Statistics\StatisticCalculationResult;

class LeastUsedResourceMaterial extends BasePredefinedStatistic
{
    public function getName(): string
    {
        return 'Percentage the least used resource material is used';
    }

    public function getResultDescription(): string
    {
        return 'The resource material\'s label';
    }

    public function calculate(): Resultable
    {
        $leastUsedPerson = $this->wherePeriod(
            $this->learningPeriod->learningActivityActing()
                ->selectRaw('res_material_id, COUNT(res_material_id) as count')
                ->groupBy('res_material_id')
                ->orderBy('count', 'ASC')
                ->whereNotNull('res_material_id')
                ->limit(1)
                ->getBaseQuery()
        )->first();

        $totalActivities = $this->wherePeriod($this->learningPeriod->learningActivityActing()->getBaseQuery())->count();

        if ($totalActivities === 0 || !$leastUsedPerson) {
            return new InvalidStatisticResult();
        }

        $resourceMaterial = ResourceMaterial::find($leastUsedPerson->res_material_id);

        $percentage = $leastUsedPerson->count / $totalActivities;

        return new StatisticCalculationResult($percentage, $resourceMaterial->rm_label);
    }

    public function getEducationProgramType(): string
    {
        return self::ACTING_TYPE;
    }
}
