<?php

namespace App\Repository\Eloquent;

use App\Evidence;

class EvidenceRepository
{
    public function get(int $id): Evidence
    {
        return Evidence::findOrFail($id);
    }

    public function delete(Evidence $evidence): bool
    {
        throw new \Exception('todo');
    }
}
