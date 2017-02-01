<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class LearningGoal extends Model {
    // Override the table used for the User Model
    protected $table = 'learninggoal';
    // Disable using created_at and updated_at columns
    public $timestamps = false;
    // Override the primary key column
    protected $primaryKey = 'learninggoal_id';

    // Default
    protected $fillable = [
        'learninggoal_id',
        'learninggaol_label',
        'wplp_id'
    ];

    public function workplaceLearningPeriod() {
        return $this->hasOne('App\workplaceLearningPeriod', 'wplp_id', 'wplp_id');
    }

    public function learningActivityActing() {
        return $this->belongsTo('App\learningActivityActing', 'learngingoal_id', 'learninggoal_id');
    }
}
