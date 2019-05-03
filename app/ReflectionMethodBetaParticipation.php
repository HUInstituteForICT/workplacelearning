<?php


namespace App;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property bool participates
 * @property Student student
 */
class ReflectionMethodBetaParticipation extends Model
{



    public function student():BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id', 'student_id');
    }
}