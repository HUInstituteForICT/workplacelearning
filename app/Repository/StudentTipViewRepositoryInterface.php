<?php

declare(strict_types=1);

namespace App\Repository;

use App\Student;
use App\Tips\Models\StudentTipView;
use App\Tips\Models\Tip;

interface StudentTipViewRepositoryInterface
{
    public function createForTip(Tip $tip, Student $student);

    public function save(StudentTipView $tip);
}
