<?php

namespace App\Services;

use App\Repository\Eloquent\CompetenceDescriptionRepository;
use App\Repository\Eloquent\CompetenceRepository;
use App\Repository\Eloquent\LearningGoalRepository;
use App\Repository\Eloquent\ResourceMaterialRepository;
use App\Repository\Eloquent\ResourcePersonRepository;
use App\Repository\Eloquent\TimeslotRepository;

class AvailableActingEntitiesFetcher
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
     * @var TimeslotRepository
     */
    private $timeslotRepository;
    /**
     * @var ResourceMaterialRepository
     */
    private $resourceMaterialRepository;
    /**
     * @var LearningGoalRepository
     */
    private $learningGoalRepository;
    /**
     * @var CompetenceRepository
     */
    private $competenceRepository;
    /**
     * @var CompetenceDescriptionRepository
     */
    private $competenceDescriptionRepository;

    public function __construct(
        CurrentUserResolver $currentUserResolver,
        ResourcePersonRepository $resourcePersonRepository,
        TimeslotRepository $timeslotRepository,
        ResourceMaterialRepository $resourceMaterialRepository,
        LearningGoalRepository $learningGoalRepository,
        CompetenceRepository $competenceRepository,
        CompetenceDescriptionRepository $competenceDescriptionRepository
    ) {
        $this->currentUserResolver = $currentUserResolver;
        $this->resourcePersonRepository = $resourcePersonRepository;
        $this->timeslotRepository = $timeslotRepository;
        $this->resourceMaterialRepository = $resourceMaterialRepository;
        $this->learningGoalRepository = $learningGoalRepository;
        $this->competenceRepository = $competenceRepository;
        $this->competenceDescriptionRepository = $competenceDescriptionRepository;
    }

    public function getEntities(): array
    {
        $student = $this->currentUserResolver->getCurrentUser();

        $resourcePersons = $this->resourcePersonRepository->resourcePersonsAvailableForStudent($student);
        $timeslots = $this->timeslotRepository->timeslotsAvailableForStudent($student);
        $resourceMaterials = $this->resourceMaterialRepository->resourceMaterialsAvailableForStudent($student);
        $learningGoals = $this->learningGoalRepository->learningGoalsAvailableForStudent($student);
        $competencies = $this->competenceRepository->competenciesAvailableToStudent($student);
        $competenceDescription = $this->competenceDescriptionRepository->applicableCompetenceDescriptionForStudent($student);

        return [
            'resourcePersons'       => $resourcePersons,
            'timeslots'             => $timeslots,
            'resourceMaterials'     => $resourceMaterials,
            'learningGoals'         => $learningGoals,
            'competencies'          => $competencies,
            'competenceDescription' => $competenceDescription,
        ];
    }
}
