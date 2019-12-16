<?php

declare(strict_types=1);

namespace App\Repository\Eloquent;

use App\SavedLearningItem;

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
}
