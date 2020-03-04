<?php

declare(strict_types=1);

namespace App\Repository\Eloquent;
use App\SavedLearningItem;
use App\Services\CurrentUserResolver;
use Illuminate\Support\Collection;

class SavedLearningItemRepository
{
    public function all()
    {
        return SavedLearningItem::all();
    }

    public function findByStudentnr(int $id): Collection
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

    public function findById($sli_id)
    {
        return SavedLearningItem::where('sli_id', '=', $sli_id)->first();
    }

    public function delete(SavedLearningItem $sli)
    {
        return $sli->delete();
    }
}
