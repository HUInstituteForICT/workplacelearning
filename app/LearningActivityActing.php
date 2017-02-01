<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class LearningActivityActing extends Model {
    // Override the table used for the User Model
    protected $table = 'learningactivityacting';
    // Disable using created_at and updated_at columns
    public $timestamps = false;
    // Override the primary key column
    protected $primaryKey = 'laa_id';

    // Default
    protected $fillable = [
        'laa_id',
        'wplp_id',
        'date',
        'timeslot_id',
        'situation',
        'lessonslearned',
        'support_wp',
        'support_ed',
        'res_person_id',
        'res_material_id',
        'res_material_detail',
        'learninggoal_id'
    ];

    public function learningGoal() {
        return $this->hasOne('App\LearningGoal', 'learninggoal_id', 'learninggoal_id');
    }

    public function competence() {
        return $this->hasOne('App\Competence', 'competence_id', 'competence_id');
    }

    public function activityForCompetence() {
        $this->hasOne('App\activityForCompetence', 'learningactivity_id', 'laa_id');
    }
}
