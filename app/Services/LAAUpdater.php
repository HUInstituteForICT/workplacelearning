<?php

namespace App\Services;

use App\LearningActivityActing;
use App\Reflection\Repository\Eloquent\ReflectionMethodBetaParticipationRepository;
use App\Reflection\Services\Factories\ActivityReflectionFactory;
use App\Reflection\Services\Updaters\ActivityReflectionUpdater;
use Carbon\Carbon;

class LAAUpdater
{
    /**
     * @var ActivityReflectionUpdater
     */
    private $activityReflectionUpdater;
    /**
     * @var ReflectionMethodBetaParticipationRepository
     */
    private $betaParticipationRepository;
    /**
     * @var ActivityReflectionFactory
     */
    private $activityReflectionFactory;

    public function __construct(
        ActivityReflectionUpdater $activityReflectionUpdater,
        ActivityReflectionFactory $activityReflectionFactory,
        ReflectionMethodBetaParticipationRepository $betaParticipationRepository
    ) {
        $this->activityReflectionUpdater = $activityReflectionUpdater;
        $this->betaParticipationRepository = $betaParticipationRepository;
        $this->activityReflectionFactory = $activityReflectionFactory;
    }

    public function update(LearningActivityActing $learningActivityActing, array $data): bool
    {
        $learningActivityActing->date = Carbon::parse($data['date'])->format('Y-m-d');
        $learningActivityActing->situation = $data['description'];

        if (!$this->betaParticipationRepository->doesCurrentUserParticipate()) {
            $learningActivityActing->lessonslearned = $data['learned'];
            $learningActivityActing->support_wp = $data['support_wp'];
            $learningActivityActing->support_ed = $data['support_ed'];
        }

        $learningActivityActing->timeslot()->associate($data['timeslot']);
        $learningActivityActing->resourcePerson()->associate($data['res_person']);
        $learningActivityActing->learningGoal()->associate($data['learning_goal']);

        $learningActivityActing->competence()->sync($data['competence']);

        if ($data['res_material'] === 'none') {
            $learningActivityActing->resourceMaterial()->dissociate();
        } else {
            $learningActivityActing->resourceMaterial()->associate($data['res_material']);
        }
        $learningActivityActing->res_material_detail = $data['res_material_detail'];

        if ($this->betaParticipationRepository->doesCurrentUserParticipate()) {
            // If a reflection already exists the user is trying to update its contents
            if ($learningActivityActing->reflection) {
                $this->activityReflectionUpdater->update($learningActivityActing->reflection, $data['reflection']);
            } else {
                // If no reflection exists (manually removed or creating afterwards) we create one instead
                $reflection = $this->activityReflectionFactory->create($data['reflection'], $learningActivityActing);

                // If a new reflection is attached we start considering this activity as one from during the beta
                if ($reflection) {
                    $learningActivityActing->is_from_reflection_beta = true;
                }
            }
        }

        return $learningActivityActing->save();
    }
}
