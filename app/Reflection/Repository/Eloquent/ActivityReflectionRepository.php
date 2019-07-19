<?php


namespace App\Reflection\Repository\Eloquent;


use App\Reflection\Models\ActivityReflection;
use App\Reflection\Models\ActivityReflectionField;
use App\Reflection\Repository\Eloquent\ActivityReflectionFieldRepository;

class ActivityReflectionRepository
{

    /**
     * @var ActivityReflectionFieldRepository
     */
    private $reflectionFieldRepository;

    public function __construct(ActivityReflectionFieldRepository $reflectionFieldRepository)
    {
        $this->reflectionFieldRepository = $reflectionFieldRepository;
    }

    public function get(int $id): ActivityReflection
    {
        return ActivityReflection::findOrFail($id);
    }

    public function save(ActivityReflection $activityReflection): bool
    {
        return $activityReflection->save();
    }

    public function delete(ActivityReflection $activityReflection): bool
    {
        foreach($activityReflection->fields as $field) {
            $this->reflectionFieldRepository->delete($field);
        }

        return $activityReflection->delete();
    }

    /**
     * @param int[] $ids
     * @return \App\Reflection\Models\ActivityReflection[]
     */
    public function getMany(array $ids): array
    {
        return ActivityReflection::whereIn('id', $ids)->get()->all();
    }
}