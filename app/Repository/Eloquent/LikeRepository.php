<?php
namespace App\Repository\Eloquent;

use App\Repository\LikeRepositoryInterface;
use App\Student;
use App\Tips\Like;
use App\Tips\Tip;

class LikeRepository implements LikeRepositoryInterface
{

    public function get(int $id): Like
    {
        /** @var Like $like */
        $like = (new \App\Tips\Like)->findOrFail($id);
        return $like;
    }

    public function save(Like $like)
    {
        $like->save();
    }

    public function loadForTipByStudent(Tip $tip, Student $student)
    {
        $tip->likes = $tip->likes()->where('student_id', '=', $student->student_id)->get();
    }
}