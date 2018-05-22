<?php
namespace App\Repository;

use App\Student;
use App\Tips\Like;
use App\Tips\Tip;

interface LikeRepositoryInterface
{
    public function get(int $id): Like;

    public function save(Like $like);

    public function loadForTipByStudent(Tip $tip, Student $student);
}