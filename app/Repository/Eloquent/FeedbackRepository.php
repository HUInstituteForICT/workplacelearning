<?php

declare(strict_types=1);

namespace App\Repository\Eloquent;

use App\Feedback;

class FeedbackRepository
{
    public function get(int $id): Feedback
    {
        return Feedback::findOrFail($id);
    }

    public function save(Feedback $feedback): bool
    {
        return $feedback->save();
    }
}
