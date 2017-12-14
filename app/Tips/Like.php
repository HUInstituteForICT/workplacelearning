<?php


namespace App\Tips;

use App\Student;

/**
 * @property int $student_id
 * @property int $tip_id
 * @property Student $student
 * @property Tip $tip
 */
class Like extends \Eloquent
{
    public $timestamps = false;

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'student_id');
    }

    public function tip() {
        return $this->belongsTo(Tip::class);
    }
}