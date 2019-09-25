<?php

declare(strict_types=1);

namespace App\Services;

use App\Repository\Eloquent\CategoryRepository;
use App\Repository\Eloquent\ChainRepository;
use App\Repository\Eloquent\DifficultyRepository;
use App\Repository\Eloquent\ResourcePersonRepository;
use App\Repository\Eloquent\StatusRepository;

class AvailableProducingEntitiesFetcher
{
    /**
     * @var CurrentUserResolver
     */
    private $currentUserResolver;
    /**
     * @var ResourcePersonRepository
     */
    private $resourcePersonRepository;
    /**
     * @var CategoryRepository
     */
    private $categoryRepository;
    /**
     * @var DifficultyRepository
     */
    private $difficultyRepository;
    /**
     * @var StatusRepository
     */
    private $statusRepository;
    /**
     * @var ChainRepository
     */
    private $chainRepository;

    public function __construct(
        CurrentUserResolver $currentUserResolver,
        ResourcePersonRepository $resourcePersonRepository,
        CategoryRepository $categoryRepository,
        DifficultyRepository $difficultyRepository,
        StatusRepository $statusRepository,
        ChainRepository $chainRepository
    ) {
        $this->currentUserResolver = $currentUserResolver;
        $this->resourcePersonRepository = $resourcePersonRepository;
        $this->categoryRepository = $categoryRepository;
        $this->difficultyRepository = $difficultyRepository;
        $this->statusRepository = $statusRepository;
        $this->chainRepository = $chainRepository;
    }

    public function getEntities(): array
    {
        $student = $this->currentUserResolver->getCurrentUser();

        $resourcePersons = $this->resourcePersonRepository->resourcePersonsAvailableForStudent($student);
        $categories = $this->categoryRepository->categoriesAvailableForStudent($student);
        $difficulties = $this->difficultyRepository->all();
        $statuses = $this->statusRepository->all();
        $chains = $this->chainRepository->chainsAvailableForStudent($student);

        return [
            'resourcePersons' => $resourcePersons,
            'categories'      => $categories,
            'difficulties'    => $difficulties,
            'statuses'        => $statuses,
            'chains'          => $chains,
        ];
    }
}
