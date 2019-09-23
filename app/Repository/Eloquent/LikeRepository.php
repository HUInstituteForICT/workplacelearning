<?php

declare(strict_types=1);

namespace App\Repository\Eloquent;

use App\Repository\LikeRepositoryInterface;
use App\Student;
use App\Tips\Models\Like;
use App\Tips\Models\Tip;

class LikeRepository implements LikeRepositoryInterface
{
    public function get(int $id): Like
    {
        /** @var Like $like */
        $like = (new \App\Tips\Models\Like())->findOrFail($id);

        return $like;
    }

    public function save(Like $like): void
    {
        $like->save();
    }

    public function loadForTipByStudent(Tip $tip, Student $student): void
    {
        $tip->likes = $tip->likes()->where('student_id', '=', $student->student_id)->get();
    }
}
