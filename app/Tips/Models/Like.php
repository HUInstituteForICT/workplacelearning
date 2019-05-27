<?php

namespace App\Tips\Models;

use App\Student;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Tips\Models\Like.
 *
 * @property int     $student_id
 * @property int     $tip_id
 * @property Student $student
 * @property Tip     $tip
 * @property int     $type
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tips\Models\Like whereStudentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tips\Models\Like whereTipId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tips\Models\Like whereType($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tips\Models\Like newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tips\Models\Like newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tips\Models\Like query()
 */
class Like extends Model
{
    public $timestamps = false;

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'student_id');
    }

    public function tip()
    {
        return $this->belongsTo(Tip::class);
    }
}
