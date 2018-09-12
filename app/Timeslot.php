<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int                     $timeslot_id
 * @property string                  $timeslot_text
 * @property WorkplaceLearningPeriod $workplaceLearningPeriod
 */
class Timeslot extends Model
{
    // Override the table used for the User Model
    protected $table = 'timeslot';
    // Disable using created_at and updated_at columns
    public $timestamps = false;
    // Override the primary key column
    protected $primaryKey = 'timeslot_id';

    // Default
    protected $fillable = [
        'timeslot_id',
        'timeslot_text',
        'edprog_id',
        'wplp_id',
        'cohort_id',
    ];

    public function cohort(): BelongsTo
    {
        return $this->belongsTo(Cohort::class, 'cohort_id', 'id');
    }

    public function educationProgram(): BelongsTo
    {
        return $this->belongsTo(EducationProgram::class, 'edprog_id', 'ep_id');
    }

    public function learningActivitiesActing(): HasMany
    {
        return $this->hasMany(LearningActivityActing::class, 'timeslot_id', 'timeslot_id');
    }

    public function workplaceLearningPeriod(): BelongsTo
    {
        return $this->belongsTo(WorkplaceLearningPeriod::class, 'wplp_id');
    }

    // Relations for query builder
    public function getRelationships(): array
    {
        return ['cohort', 'educationProgram'];
    }
}
