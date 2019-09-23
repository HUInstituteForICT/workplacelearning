<?php

declare(strict_types=1);

namespace App\Repository\Eloquent;

use App\Status;

class StatusRepository
{
    public function get(int $id): Status
    {
        return Status::findOrFail($id);
    }

    public function all(): array
    {
        return Status::all()->all();
    }
}
