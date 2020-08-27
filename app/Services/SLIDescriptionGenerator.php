<?php
declare(strict_types=1);

namespace App\Services;


use App\Interfaces\LearningActivityInterface;
use App\Repository\Eloquent\LearningActivityActingRepository;
use App\Repository\Eloquent\LearningActivityProducingRepository;
use App\Repository\Eloquent\TipRepository;
use App\SavedLearningItem;
use App\Tips\Services\TipEvaluator;

class SLIDescriptionGenerator
{
    private $tipEvaluator;

    private $tipRepository;

    private $actingRepository;

    private $producingRepository;

    public function __construct(TipEvaluator $evaluator, TipRepository $tipRepository, LearningActivityActingRepository $actingRepository, LearningActivityProducingRepository $producingRepository)
    {
        $this->tipEvaluator = $evaluator;
        $this->tipRepository = $tipRepository;
        $this->actingRepository = $actingRepository;
        $this->producingRepository = $producingRepository;
    }

    public function getDescriptionForSLI(SavedLearningItem $savedLearningItem): string
    {
        if($savedLearningItem->category === SavedLearningItem::CATEGORY_TIP) {
            $tip = $this->tipRepository->get($savedLearningItem->item_id);
            return $this->tipEvaluator->evaluateForChosenStudent($tip, $savedLearningItem->student)->getTipText();
        }

        return $this->getLearningActivity($savedLearningItem->category, $savedLearningItem->item_id)->getDescription();
    }

    private function getLearningActivity(string $type, int $id): LearningActivityInterface
    {
        if($type === SavedLearningItem::CATEGORY_LAA) {
            return $this->actingRepository->get($id);
        }

        // Other cases currently always LAP
        return $this->producingRepository->get($id);
    }
}
