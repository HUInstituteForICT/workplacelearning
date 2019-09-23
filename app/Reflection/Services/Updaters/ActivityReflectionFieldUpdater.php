<?php

namespace App\Reflection\Services\Updaters;

use App\Reflection\Models\ActivityReflectionField;
use App\Reflection\Repository\Eloquent\ActivityReflectionFieldRepository;

class ActivityReflectionFieldUpdater
{
    /**
     * @var ActivityReflectionFieldRepository
     */
    private $repository;

    public function __construct(ActivityReflectionFieldRepository $repository)
    {
        $this->repository = $repository;
    }

    public function update(ActivityReflectionField $field, string $value): bool
    {
        $field->value = $value;

        return $this->repository->save($field);
    }
}
