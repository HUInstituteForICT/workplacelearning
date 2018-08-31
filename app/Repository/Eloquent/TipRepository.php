<?php

namespace App\Repository\Eloquent;

use App\Repository\TipRepositoryInterface;
use App\Tips\Models\Tip;

class TipRepository implements TipRepositoryInterface
{
    public function get(int $id): Tip
    {
        /** @var Tip $tip */
        $tip = (new Tip())->findOrFail($id);

        return $tip;
    }

    public function save(Tip $tip)
    {
        $tip->save();
    }
}
