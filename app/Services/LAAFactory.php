<?php

namespace App\Services;

use App\LearningActivityActing;
use App\LearningGoal;
use App\ResourceMaterial;
use App\ResourcePerson;
use App\Student;
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
     * @var Student
     */
    private $student;

    public function __construct(
        TimeslotFactory $timeslotFactory,
        ResourcePersonFactory $resourcePersonFactory,
        ResourceMaterialFactory $resourceMaterialFactory,
        Student $student
    ) {
        $this->timeslotFactory = $timeslotFactory;
        $this->resourcePersonFactory = $resourcePersonFactory;
        $this->resourceMaterialFactory = $resourceMaterialFactory;
        $this->student = $student;
    }

    public function createLAA(array $data): LearningActivityActing
    {
        $activityActing = new LearningActivityActing();

        $activityActing->resourcePerson()->associate($this->getResourcePerson($data));

        if ($data['res_material'] !== 'none') {
            $activityActing->resourceMaterial()->associate($this->getResourceMaterial($data));
        }

        $activityActing->timeslot()->associate($this->getTimeslot($data));
        $activityActing->workplaceLearningPeriod()->associate($this->student->getCurrentWorkplaceLearningPeriod());
        $activityActing->learningGoal()->associate((new LearningGoal())->find($data['learning_goal']));

        $activityActing->date = Carbon::parse($data['date'])->format('Y-m-d');
        $activityActing->situation = $data['description'];
        $activityActing->lessonslearned = $data['learned'];
        $activityActing->support_wp = $data['support_wp'];
        $activityActing->support_ed = $data['support_ed'];
        $activityActing->res_material_detail = $data['res_material_detail'];
        $activityActing->save();

        // Needs to be after save because the relation is many-to-many for some reason, thus association table is used
        $activityActing->competence()->sync($data['competence']);

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
