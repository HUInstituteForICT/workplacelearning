<?php

declare(strict_types=1);

namespace App\Repository;

use App\Tips\Models\Tip;

interface TipRepositoryInterface
{
    public function get(int $id): Tip;

    public function save(Tip $tip);
}
