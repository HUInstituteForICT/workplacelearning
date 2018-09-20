<?php

namespace App\Services;

use App\ResourceMaterial;

class ResourceMaterialFactory
{
    /**
     * @var CurrentPeriodResolver
     */
    private $currentPeriodResolver;

    public function __construct(CurrentPeriodResolver $currentPeriodResolver)
    {
        $this->currentPeriodResolver = $currentPeriodResolver;
    }

    public function createResourceMaterial(string $label): ResourceMaterial
    {
        $resourceMaterial = new ResourceMaterial();
        $resourceMaterial->rm_label = $label;
        $resourceMaterial->workplaceLearningPeriod()->associate($this->currentPeriodResolver->getPeriod());
        $resourceMaterial->save();

        return $resourceMaterial;
    }
}
