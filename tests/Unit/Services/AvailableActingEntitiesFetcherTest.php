<?php

declare(strict_types=1);

namespace Test\Unit\Services;

use App\Interfaces\ProgressRegistrySystemServiceInterface;
use App\Repository\Eloquent\CompetenceDescriptionRepository;
use App\Repository\Eloquent\CompetenceRepository;
use App\Repository\Eloquent\LearningGoalRepository;
use App\Repository\Eloquent\ResourceMaterialRepository;
use App\Repository\Eloquent\ResourcePersonRepository;
use App\Repository\Eloquent\TimeslotRepository;
use App\Services\AvailableActingEntitiesFetcher;
use App\Services\CurrentUserResolver;
use App\Student;
use Tests\TestCase;

class AvailableActingEntitiesFetcherTest extends TestCase
{
    public function testGetEntities(): void
    {
        $student = $this->createMock(Student::class);

        $currentUserResolver = $this->createMock(CurrentUserResolver::class);
        $currentUserResolver->expects(self::once())->method('getCurrentUser')->willReturn($student);

        $resourcePersonRepository = $this->createMock(ResourcePersonRepository::class);
        $resourcePersonRepository->expects(self::once())->method('resourcePersonsAvailableForStudent')->with($student);

//        $timeslotRepository = $this->createMock(TimeslotRepository::class);
//        $timeslotRepository->expects(self::once())->method('timeslotsAvailableForStudent')->with($student);

        $progressRegistrySystemService = $this->createMock(ProgressRegistrySystemServiceInterface::class);
        $progressRegistrySystemService->expects(self::once())->method('getTimeslotsAvailableForStudent')->with($student);

        $resourceMaterialRepository = $this->createMock(ResourceMaterialRepository::class);
        $resourceMaterialRepository->expects(self::once())->method('resourceMaterialsAvailableForStudent')->with($student);

        $learningGoalRepository = $this->createMock(LearningGoalRepository::class);
        $learningGoalRepository->expects(self::once())->method('learningGoalsAvailableForStudent')->with($student);

        $competenceRepository = $this->createMock(CompetenceRepository::class);
        $competenceRepository->expects(self::once())->method('competenciesAvailableToStudent')->with($student);

        $competenceDescriptionRepository = $this->createMock(CompetenceDescriptionRepository::class);
        $competenceDescriptionRepository->expects(self::once())->method('applicableCompetenceDescriptionForStudent')->with($student);

        $availableActingEntitiesFetcher = new AvailableActingEntitiesFetcher($currentUserResolver,
            $resourcePersonRepository, $progressRegistrySystemService,
            $resourceMaterialRepository, $learningGoalRepository,
            $competenceRepository, $competenceDescriptionRepository);

        $expectedKeys = [
            'resourcePersons',
            'timeslots',
            'resourceMaterials',
            'learningGoals',
            'competencies',
            'competenceDescription',
        ];

        $result = $availableActingEntitiesFetcher->getEntities();
        $actualKeys = array_keys($result);
        array_walk($actualKeys, function (string $key) use ($expectedKeys) {
            $this->assertContains($key, $expectedKeys);
        });
    }
}
