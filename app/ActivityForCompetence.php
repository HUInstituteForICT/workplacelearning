<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ActivityForCompetence extends Model {
    // Override the table used for the User Model
    protected $table = 'activityforcompetence';
    // Disable using created_at and updated_at columns
    public $timestamps = false;
    // Override the primary key column
    protected $primaryKey = 'afc_id';

    // Default
    protected $fillable = [
        'afc_id',
        'competence_id',
        'learningactivity_id'
    ];

    public function competence() {
        return $this->BelongsToMany('App\Competence');
    }

    public function learningActivityActing() {
        return $this->belongsTo('App\LearningActivityActing', 'laa_id', 'learningactivity_id');
    }
}
