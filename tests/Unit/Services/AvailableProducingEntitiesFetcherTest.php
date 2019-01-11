<?php

namespace App\Services;

use App\Repository\Eloquent\CategoryRepository;
use App\Repository\Eloquent\ChainRepository;
use App\Repository\Eloquent\DifficultyRepository;
use App\Repository\Eloquent\ResourcePersonRepository;
use App\Repository\Eloquent\StatusRepository;
use App\Student;

class AvailableProducingEntitiesFetcherTest extends \PHPUnit\Framework\TestCase
{
    public function testGetEntities(): void
    {
        $student = $this->createMock(Student::class);

        $currentUserResolver = $this->createMock(CurrentUserResolver::class);
        $currentUserResolver->expects(self::once())->method('getCurrentUser')->willReturn($student);

        $resourcePersonRepository = $this->createMock(ResourcePersonRepository::class);
        $resourcePersonRepository->expects(self::once())->method('resourcePersonsAvailableForStudent')->with($student)->willReturn([]);

        $categoryRepository = $this->createMock(CategoryRepository::class);
        $categoryRepository->expects(self::once())->method('categoriesAvailableForStudent')->with($student)->willReturn([]);

        $difficultyRepository = $this->createMock(DifficultyRepository::class);
        $difficultyRepository->expects(self::once())->method('all')->willReturn([]);

        $statusRepository = $this->createMock(StatusRepository::class);
        $statusRepository->expects(self::once())->method('all')->willReturn([]);

        $chainRepository = $this->createMock(ChainRepository::class);
        $chainRepository->expects(self::once())->method('chainsAvailableForStudent')->with($student)->willReturn([]);

        $availableProducingEntitiesFetcher = new AvailableProducingEntitiesFetcher($currentUserResolver,
            $resourcePersonRepository, $categoryRepository, $difficultyRepository, $statusRepository, $chainRepository);

        $entities = $availableProducingEntitiesFetcher->getEntities();

        $this->assertSame([
            'resourcePersons' => [],
            'categories'      => [],
            'difficulties'    => [],
            'statuses'        => [],
            'chains'          => [],
        ], $entities);
    }
}
