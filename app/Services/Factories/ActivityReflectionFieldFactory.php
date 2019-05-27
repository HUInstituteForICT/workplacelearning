<?php


namespace App\Services\Factories;


use App\ActivityReflection;
use App\ActivityReflectionField;
use App\Repository\Eloquent\ActivityReflectionFieldRepository;

class ActivityReflectionFieldFactory
{
    /**
     * @var ActivityReflectionFieldRepository
     */
    private $repository;

    public function __construct(ActivityReflectionFieldRepository $repository)
    {
        $this->repository = $repository;
    }

    public function create(
        string $name,
        string $value,
        ActivityReflection $activityReflection
    ): ActivityReflectionField {

        $field = new ActivityReflectionField();
        $field->name = $name;
        $field->value = $value;

        $field->activityReflection()->associate($activityReflection);

        $this->repository->save($field);

        return $field;
    }
}