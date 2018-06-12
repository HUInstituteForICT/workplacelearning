<?php


namespace App\Repository\Eloquent;

use App\Repository\StudentTipViewRepositoryInterface;
use App\Student;
use App\Tips\StudentTipView;
use App\Tips\Tip;

class StudentTipViewRepository implements StudentTipViewRepositoryInterface
{
    public function createForTip(Tip $tip, Student $student)
    {
        if($tip->studentTipViews()->where('student_id', '=', $student->student_id)->count() === 0) {
            $studentTipView = new StudentTipView();
            $studentTipView->student_id = $student->student_id;

            $tip->studentTipViews()->save($studentTipView);
        }
    }

    public function save(StudentTipView $studentTipView)
    {
        $studentTipView->save();
    }
}
