<?php

declare(strict_types=1);

namespace App\Repository\Eloquent;

use App\Difficulty;

class DifficultyRepository
{
    public function get(int $id): Difficulty
    {
        return Difficulty::findOrFail($id);
    }

    public function all(): array
    {
        return Difficulty::all()->all();
    }
}
