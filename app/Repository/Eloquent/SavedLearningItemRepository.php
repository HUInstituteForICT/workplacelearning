<?php

declare(strict_types=1);

namespace App\Repository\Eloquent;
use App\Services\EvidenceFileHandler;
use App\SavedLearningItem;
use App\Services\CurrentUserResolver;

class SavedLearningItemRepository
{
    /**
     * @var CurrentUserResolver
     */
    private $currentUserResolver;
    /**
     * @var EvidenceFileHandler
     */
    private $evidenceFileHandler;

    public function __construct(EvidenceFileHandler $evidenceFileHandler)
    {
        $this->evidenceFileHandler = $evidenceFileHandler;
    }

    public function all()
    {
        return SavedLearningItem::all();
    }

    public function save(SavedLearningItem $savedLearningItem): bool
    {
        return $savedLearningItem->save();
    }

    public static function itemExists($category, $item_id, $student_id): bool
    {
        return SavedLearningItem::where([
            'category' => $category,
            'item_id' => $item_id,
            'student_id' => $student_id
        ])->count() > 0;
    }
}