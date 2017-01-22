<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Workplace;
use App\WorkplaceLearningPeriod;

class LearningActivityProducing extends Model{
    // Override the table used for the User Model
    protected $table = 'learningactivityproducing';
    // Disable using created_at and updated_at columns
    public $timestamps = false;
    // Override the primary key column
    protected $primaryKey = 'lap_id';

    // Default
    protected $fillable = [
        'lap_id',
        'wplp_id',
        'duration',
        'description',
        'date',
        'prev_lap_id',
        'res_person_id',
        'res_material_id',
        'res_material_detail',
        'category_id',
        'difficulty_id',
        'status_id'
    ];

    public function workplaceLearningPeriod(){
        return $this->belongsTo('App\WorkplaceLearningPeriod', 'wplp_id', 'wplp_id');
    }

    public function feedback(){
        return $this->hasOne('App\Feedback');
    }

    public function resourcePerson() {
        return $this->hasOne('App\ResourcePerson');
    }

    // Note: DND, object comparison
    public function __toString() {
        return $this->lap_id;
    }
}
