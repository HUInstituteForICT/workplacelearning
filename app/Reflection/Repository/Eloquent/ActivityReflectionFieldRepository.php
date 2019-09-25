<?php

declare(strict_types=1);

namespace App\Reflection\Repository\Eloquent;

use App\Reflection\Models\ActivityReflectionField;

class ActivityReflectionFieldRepository
{
    public function get(int $id): ActivityReflectionField
    {
        return ActivityReflectionField::findOrFail($id);
    }

    public function save(ActivityReflectionField $activityReflectionField): bool
    {
        return $activityReflectionField->save();
    }

    public function delete(ActivityReflectionField $activityReflectionField): bool
    {
        return $activityReflectionField->delete();
    }
}
