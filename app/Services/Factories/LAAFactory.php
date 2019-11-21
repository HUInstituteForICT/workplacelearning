<?php

declare(strict_types=1);

namespace App\Services\Factories;

use App\LearningActivityActing;
use App\LearningGoal;
use App\Reflection\Services\Factories\ActivityReflectionFactory;
use App\Repository\Eloquent\LearningActivityActingRepository;
use App\ResourceMaterial;
use App\ResourcePerson;
use App\Services\CurrentUserResolver;
use App\Timeslot;
use Carbon\Carbon;

class LAAFactory
{
    /**
     * @var TimeslotFactory
     */
    private $timeslotFactory;
    /**
     * @var ResourcePersonFactory
     */
    private $resourcePersonFactory;
    /**
     * @var ResourceMaterialFactory
     */
    private $resourceMaterialFactory;
    /**
     * @var CurrentUserResolver
     */
    private $currentUserResolver;
    /**
     * @var LearningActivityActingRepository
     */
    private $learningActivityActingRepository;

    /**
     * @var ActivityReflectionFactory
     */
    private $activityReflectionFactory;

    public function __construct(
        LearningActivityActingRepository $learningActivityActingRepository,
        TimeslotFactory $timeslotFactory,
        ResourcePersonFactory $resourcePersonFactory,
        ResourceMaterialFactory $resourceMaterialFactory,
        CurrentUserResolver $currentUserResolver,
        ActivityReflectionFactory $activityReflectionFactory
    ) {
        $this->timeslotFactory = $timeslotFactory;
        $this->resourcePersonFactory = $resourcePersonFactory;
        $this->resourceMaterialFactory = $resourceMaterialFactory;
        $this->currentUserResolver = $currentUserResolver;
        $this->activityReflectionFactory = $activityReflectionFactory;
        $this->learningActivityActingRepository = $learningActivityActingRepository;
    }

    public function createLAA(array $data): LearningActivityActing
    {
        $student = $this->currentUserResolver->getCurrentUser();
        $activityActing = new LearningActivityActing();

        $activityActing->resourcePerson()->associate($this->getResourcePerson($data));

        if ($data['res_material'] !== 'none') {
            $activityActing->resourceMaterial()->associate($this->getResourceMaterial($data));
        }

        $activityActing->timeslot()->associate($this->getTimeslot($data));
        $activityActing->workplaceLearningPeriod()->associate($student->getCurrentWorkplaceLearningPeriod());
        $activityActing->learningGoal()->associate((new LearningGoal())::find($data['learning_goal']));

        $activityActing->date = Carbon::parse($data['date'])->format('Y-m-d');
        $activityActing->situation = $data['description'];

        // Are null if not used by user, still process it
        $activityActing->lessonslearned = $data['learned'];
        $activityActing->support_wp = $data['support_wp'];
        $activityActing->support_ed = $data['support_ed'];

        $activityActing->res_material_detail = $data['res_material_detail'];
        $this->learningActivityActingRepository->save($activityActing);

        // Needs to be after save because the relation is many-to-many, thus association table is used
        $activityActing->competence()->sync($data['competence']);

        // Check if a reflection is attached and if so process it
        if (isset($data['reflection'])) {
            $this->activityReflectionFactory->create($data['reflection'], $activityActing);
            $this->learningActivityActingRepository->save($activityActing);
        }

        return $activityActing;
    }

    private function getResourcePerson(array $data): ResourcePerson
    {
        if ($data['res_person'] === 'new') {
            return $this->resourcePersonFactory->createResourcePerson($data['new_rp']);
        }

        return (new ResourcePerson())->find($data['res_person']);
    }

    private function getResourceMaterial(array $data): ResourceMaterial
    {
        if ($data['res_material'] === 'new') {
            return $this->resourceMaterialFactory->createResourceMaterial($data['new_rm']);
        }

        return (new ResourceMaterial())->find($data['res_material']);
    }

    private function getTimeslot(array $data): Timeslot
    {
        if ($data['timeslot'] === 'new') {
            return $this->timeslotFactory->createTimeslot($data['new_timeslot']);
        }

        return (new Timeslot())->find($data['timeslot']);
    }
}
