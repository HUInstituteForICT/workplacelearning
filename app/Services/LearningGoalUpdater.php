<?php

declare(strict_types=1);

namespace App\Services;

use App\Repository\Eloquent\LearningGoalRepository;

class LearningGoalUpdater
{
    /**
     * @var LearningGoalRepository
     */
    private $learningGoalRepository;

    public function __construct(LearningGoalRepository $learningGoalRepository)
    {
        $this->learningGoalRepository = $learningGoalRepository;
    }

    public function updateLearningGoals(array $learningGoals): bool
    {
        foreach ($learningGoals as $id => $data) {
            $learningGoal = $this->learningGoalRepository->get($id);
            $this->learningGoalRepository->update($learningGoal,
                ['learninggoal_label' => $data['label'], 'description' => $data['description']]);
        }

        return true;
    }
}
