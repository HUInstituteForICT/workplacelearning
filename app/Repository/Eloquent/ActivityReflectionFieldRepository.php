<?php


namespace App\Repository\Eloquent;


use App\ActivityReflectionField;

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