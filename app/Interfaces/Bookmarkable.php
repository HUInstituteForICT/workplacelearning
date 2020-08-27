<?php
declare(strict_types=1);

namespace App\Interfaces;


use App\SavedLearningItem;

interface Bookmarkable
{
    public function bookmark(): SavedLearningItem;
}
