<?php

declare(strict_types=1);

namespace App\Repository\Eloquent;

use App\ResourceMaterial;
use App\Student;

class ResourceMaterialRepository
{
    public function get(int $id): ResourceMaterial
    {
        return ResourceMaterial::findOrFail($id);
    }

    public function save(ResourceMaterial $resourceMaterial): bool
    {
        return $resourceMaterial->save();
    }

    /**
     * @return ResourceMaterial[]
     */
    public function resourceMaterialsAvailableForStudent(Student $student): array
    {
        return $student->getCurrentWorkplaceLearningPeriod()->resourceMaterial()
            ->orWhere('wplp_id', '=', '0')
            ->get()->all();
    }
}
