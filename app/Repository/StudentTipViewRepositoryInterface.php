<?php


namespace App\Repository;

use App\Student;
use App\Tips\StudentTipView;
use App\Tips\Tip;

interface StudentTipViewRepositoryInterface
{
    public function createForTip(Tip $tip, Student $student);

    public function save(StudentTipView $tip);
}
