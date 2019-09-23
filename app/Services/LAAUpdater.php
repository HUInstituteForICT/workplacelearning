<?php

declare(strict_types=1);

namespace App\Services;

use App\LearningActivityActing;
use App\Reflection\Services\Factories\ActivityReflectionFactory;
use App\Reflection\Services\Updaters\ActivityReflectionUpdater;
use App\Repository\Eloquent\LearningActivityActingRepository;
use Carbon\Carbon;

class LAAUpdater
{
    /**
     * @var ActivityReflectionUpdater
     */
    private $activityReflectionUpdater;
    /**
     * @var ActivityReflectionFactory
     */
    private $activityReflectionFactory;
    /**
     * @var LearningActivityActingRepository
     */
    private $learningActivityActingRepository;

    public function __construct(
        ActivityReflectionUpdater $activityReflectionUpdater,
        ActivityReflectionFactory $activityReflectionFactory,
        LearningActivityActingRepository $learningActivityActingRepository
    ) {
        $this->activityReflectionUpdater = $activityReflectionUpdater;
        $this->activityReflectionFactory = $activityReflectionFactory;

        $this->learningActivityActingRepository = $learningActivityActingRepository;
    }

    public function update(LearningActivityActing $learningActivityActing, array $data): bool
    {
        $learningActivityActing->date = Carbon::parse($data['date'])->format('Y-m-d');
        $learningActivityActing->situation = $data['description'];

        $learningActivityActing->lessonslearned = $data['learned'];
        $learningActivityActing->support_wp = $data['support_wp'];
        $learningActivityActing->support_ed = $data['support_ed'];

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

        // If a reflection already exists the user is trying to update its contents
        if (isset($data['reflection'])) {
            if ($learningActivityActing->reflection) {
                $this->activityReflectionUpdater->update($learningActivityActing->reflection, $data['reflection']);
            } else {
                // If no reflection exists (manually removed or creating afterwards) we create one instead
                $reflection = $this->activityReflectionFactory->create($data['reflection'], $learningActivityActing);
            }
        }

        return $this->learningActivityActingRepository->save($learningActivityActing);
    }
}
