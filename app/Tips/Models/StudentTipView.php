<?php

namespace App\Tips\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Tips\Models\StudentTipView.
 *
 * @property int $student_id
 * @property int $tip_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tips\Models\StudentTipView whereStudentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tips\Models\StudentTipView whereTipId($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tips\Models\StudentTipView newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tips\Models\StudentTipView newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tips\Models\StudentTipView query()
 */
class StudentTipView extends Model
{
    public $timestamps = false;
}
