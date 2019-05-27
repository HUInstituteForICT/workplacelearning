<?php


namespace App\Repository\Eloquent;


use App\ActivityReflection;

class ActivityReflectionRepository
{
    public function get(int $id): ActivityReflection
    {
        return ActivityReflection::findOrFail($id);
    }

    public function save(ActivityReflection $activityReflection): bool
    {
        return $activityReflection->save();
    }
}