<?php

namespace App\Repository\Eloquent;

use App\Workplace;

class WorkplaceRepository
{
    public function get(int $id): Workplace
    {
        return Workplace::findOrFail($id);
    }

    public function save(Workplace $workplace): bool
    {
        return $workplace->save();
    }
}
