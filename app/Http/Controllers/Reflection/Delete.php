<?php


namespace App\Http\Controllers\Reflection;


use App\ActivityReflection;
use App\Repository\Eloquent\ActivityReflectionRepository;

class Delete
{

    /**
     * @var ActivityReflectionRepository
     */
    private $repository;

    public function __construct(ActivityReflectionRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(ActivityReflection $activityReflection)
    {
        $this->repository->delete($activityReflection);
    }
}