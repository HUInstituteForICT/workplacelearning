<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int    $timeslot_id
 * @property string $timeslot_text
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

    public function cohort()
    {
        return $this->belongsTo(Cohort::class, 'cohort_id', 'id');
    }

    public function educationProgram()
    {
        return $this->belongsTo(\App\EducationProgram::class, 'edprog_id', 'ep_id');
    }

    public function learningActivitiesActing()
    {
        return $this->hasMany(\App\LearningActivityActing::class, 'timeslot_id', 'timeslot_id');
    }

    // Relations for query builder
    public function getRelationships()
    {
        return ['cohort', 'educationProgram'];
    }
}
