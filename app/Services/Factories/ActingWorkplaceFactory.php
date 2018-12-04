<?php

namespace App\Services\Factories;

use App\Services\CurrentUserResolver;

class ActingWorkplaceFactory
{
    /**
     * @var WorkplaceFactory
     */
    private $workplaceFactory;
    /**
     * @var WorkplaceLearningPeriodFactory
     */
    private $workplaceLearningPeriodFactory;
    /**
     * @var LearningGoalFactory
     */
    private $learningGoalFactory;
    /**
     * @var CurrentUserResolver
     */
    private $currentUserResolver;

    public function __construct(
        WorkplaceFactory $workplaceFactory,
        WorkplaceLearningPeriodFactory $workplaceLearningPeriodFactory,
        LearningGoalFactory $learningGoalFactory,
        CurrentUserResolver $currentUserResolver
    ) {
        $this->workplaceFactory = $workplaceFactory;
        $this->workplaceLearningPeriodFactory = $workplaceLearningPeriodFactory;
        $this->learningGoalFactory = $learningGoalFactory;
        $this->currentUserResolver = $currentUserResolver;
    }

    public function createEntities(array $data): void
    {
        $workplace = $this->workplaceFactory->createWorkplace($data);

        $workplaceLearningPeriodData = array_merge(
            $data,
            ['workplace_id' => $workplace->wp_id]
        );

        $workplaceLearningPeriod = $this->workplaceLearningPeriodFactory->createWorkplaceLearningPeriod($workplaceLearningPeriodData);

        $this->createDefaultLearningGoals($workplaceLearningPeriod->wplp_id);

        if (isset($data['isActive']) && (int) $data['isActive'] === 1) {
            $student = $this->currentUserResolver->getCurrentUser();
            $student->setActiveWorkplaceLearningPeriod($workplaceLearningPeriod);
        }
    }

    private function createDefaultLearningGoals(int $workplaceLearningPeriodId): void
    {
        // Create an "unplanned" learning moment
        $data = [
            'label'       => __('general.default.learninggoal_label'),
            'description' => __('general.default.learninggoal_desc'),
            'wplp_id'     => $workplaceLearningPeriodId,
        ];
        $this->learningGoalFactory->createLearningGoal($data);

        // Create 4 other "default" learning goals
        for ($i = 1; $i < 4; ++$i) {
            $data = [
                'label'       => sprintf(__('activity.learningquestion').' %s', $i),
                'description' => sprintf(__('general.default.learninggoal_desc_placeholder').' %s', $i),
                'wplp_id'     => $workplaceLearningPeriodId,
            ];
            $this->learningGoalFactory->createLearningGoal($data);
        }
    }
}
