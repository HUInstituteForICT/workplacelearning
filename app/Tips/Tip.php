<?php


namespace App\Tips;


use App\Cohort;
use App\Student;
use App\Tips\Statistics\Statistic;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;


/**
 * Class Tip
 *
 * @property string $name Name of the tip
 * @property boolean $showInAnalysis Whether or not the tip should be displayed in analyses
 * @property integer $id ID of the tip
 * @property Statistic[]|Collection $coupledStatistics of the tip
 * @property string $tipText The text including placeholders used for displaying the tip
 * @property Cohort[]|Collection $enabledCohorts
 * @property Like[]|Collection $likes
 * @property string $trigger
 * @property int $rangeStart
 * @property int $rangeEnd
 */
class Tip extends Model
{

    public $timestamps = false;


    /**
     * The likes this Tip has given by Students
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function likesByStudent(Student $student)
    {
        return $this->hasMany(Like::class)->where('student_id', '=', $student->student_id);
    }

    /**
     * The cohorts this Tip is enabled for
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function enabledCohorts()
    {
        return $this->belongsToMany(Cohort::class);
    }

    /**
     * The coupled statistics used for this tip
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function coupledStatistics()
    {
        return $this->hasMany(TipCoupledStatistic::class);
    }

    public function dislikedByStudent(Student $student)
    {
        return $this->likes()->where('student_id', '=', $student->student_id)
            ->where('type', '=', -1)
            ->count() > 0;
    }

    public function likedByStudent(Student $student)
    {
        return $this->likes()->where('student_id', '=', $student->student_id)
                ->where('type', '=', 1)
                ->count() > 0;
    }
}