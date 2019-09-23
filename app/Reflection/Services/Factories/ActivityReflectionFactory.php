<?php

namespace App\Reflection\Services\Factories;

use App\Reflection\Models\ActivityReflection;
use App\Interfaces\LearningActivityInterface;
use App\Reflection\Repository\Eloquent\ActivityReflectionRepository;
use function get_class;

class ActivityReflectionFactory
{
    /**
     * @var ActivityReflectionRepository
     */
    private $repository;
    /**
     * @var ActivityReflectionFieldFactory
     */
    private $fieldFactory;

    public function __construct(ActivityReflectionRepository $repository, ActivityReflectionFieldFactory $fieldFactory)
    {
        $this->repository = $repository;
        $this->fieldFactory = $fieldFactory;
    }

    public function create(array $data, LearningActivityInterface $learningActivity): ActivityReflection
    {
        $reflection = new ActivityReflection();

        $reflection->reflection_type = $data['type'];

        $reflection->learning_activity_type = ActivityReflection::LEARNING_ACTIVITY_TYPE[get_class($learningActivity)];

        $reflection->learningActivity()->associate($learningActivity);

        $this->repository->save($reflection);

        foreach ($data['field'] as $field => $value) {
            $this->fieldFactory->create($field, $value, $reflection);
        }

        return $reflection;
    }
}
