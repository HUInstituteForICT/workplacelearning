<?php

namespace App\Services\Factories;

use App\LearningGoal;
use App\Repository\Eloquent\LearningGoalRepository;

class LearningGoalFactory
{
    /**
     * @var LearningGoalRepository
     */
    private $learningGoalRepository;

    public function __construct(LearningGoalRepository $learningGoalRepository)
    {
        $this->learningGoalRepository = $learningGoalRepository;
    }

    public function createLearningGoal(array $data): LearningGoal
    {
        $learningGoal = new LearningGoal();
        $learningGoal->learninggoal_label = $data['label'];
        $learningGoal->description = $data['description'];
        $learningGoal->wplp_id = $data['wplp_id'];

        $this->learningGoalRepository->save($learningGoal);

        return $learningGoal;
    }
}
