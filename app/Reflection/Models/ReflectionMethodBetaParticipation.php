<?php


namespace App\Reflection\Models;


use App\Student;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\ReflectionMethodBetaParticipation
 *
 * @property int $id
 * @property int $student_id
 * @property int $participates
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Student $student
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ReflectionMethodBetaParticipation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ReflectionMethodBetaParticipation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ReflectionMethodBetaParticipation query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ReflectionMethodBetaParticipation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ReflectionMethodBetaParticipation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ReflectionMethodBetaParticipation whereParticipates($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ReflectionMethodBetaParticipation whereStudentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ReflectionMethodBetaParticipation whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ReflectionMethodBetaParticipation extends Model
{



    public function student():BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id', 'student_id');
    }
}