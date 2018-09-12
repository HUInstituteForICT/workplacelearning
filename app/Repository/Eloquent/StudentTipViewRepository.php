<?php

namespace App\Repository\Eloquent;

use App\Repository\StudentTipViewRepositoryInterface;
use App\Student;
use App\Tips\Models\StudentTipView;
use App\Tips\Models\Tip;

class StudentTipViewRepository implements StudentTipViewRepositoryInterface
{
    public function createForTip(Tip $tip, Student $student): void
    {
        if (0 === $tip->studentTipViews()->where('student_id', '=', $student->student_id)->count()) {
            $studentTipView = new StudentTipView();
            $studentTipView->student_id = $student->student_id;

            $tip->studentTipViews()->save($studentTipView);
        }
    }

    public function save(StudentTipView $studentTipView): void
    {
        $studentTipView->save();
    }
}
