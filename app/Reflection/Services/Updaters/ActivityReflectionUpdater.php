<?php


namespace App\Reflection\Services\Updaters;


use App\Reflection\Models\ActivityReflection;
use App\Reflection\Repository\Eloquent\ActivityReflectionRepository;
use Exception;

class ActivityReflectionUpdater
{
    /**
     * @var ActivityReflectionRepository
     */
    private $repository;
    /**
     * @var ActivityReflectionFieldUpdater
     */
    private $fieldUpdater;

    public function __construct(ActivityReflectionRepository $repository, ActivityReflectionFieldUpdater $fieldUpdater)
    {
        $this->repository = $repository;
        $this->fieldUpdater = $fieldUpdater;
    }


    public function update(ActivityReflection $activityReflection, array $data): bool
    {
        /**
         * The current implementation only allows for the updating of the fields of a reflection
         * To keep integrity of the reflection's type we loop over each field and find a field in the data with the same name
         * And then update the field with that value
         * Any additional fields are discarded as they do not belong to the reflection type
         */

        foreach ($activityReflection->fields as $field) {
            if (!isset($data['field'][$field->name])) {
                // If the field is not available in the data, skip
                continue;
            }

            $this->fieldUpdater->update($field, $data['field'][$field->name]);
        }

        return true;
    }
}