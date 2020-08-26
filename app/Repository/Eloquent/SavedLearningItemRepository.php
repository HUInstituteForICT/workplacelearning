<?php

declare(strict_types=1);

namespace App\Repository\Eloquent;
use App\Services\EvidenceFileHandler;
use App\SavedLearningItem;
use App\Services\CurrentUserResolver;

class SavedLearningItemRepository
{
    public function all()
    {
        return SavedLearningItem::all();
    }

    public function findByStudentnr(int $id)
    {
        return SavedLearningItem::where('student_id', $id)->get();
    }

    public function save(SavedLearningItem $savedLearningItem): bool
    {
        return $savedLearningItem->save();
    }

    public function itemExists($category, $item_id, $student_id): bool
    {
        return SavedLearningItem::where([
            'category' => $category,
            'item_id' => $item_id,
            'student_id' => $student_id
        ])->count() > 0;
    }
}
