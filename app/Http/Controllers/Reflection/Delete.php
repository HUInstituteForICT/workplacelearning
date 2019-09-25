<?php

declare(strict_types=1);

namespace App\Http\Controllers\Reflection;

use App\Reflection\Models\ActivityReflection;
use App\Reflection\Repository\Eloquent\ActivityReflectionRepository;

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

        return back();
    }
}
