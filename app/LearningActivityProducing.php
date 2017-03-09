<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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
        return $this->hasOne('App\ResourcePerson', 'rp_id', 'res_person_id');
    }

    public function resourceMaterial() {
        return $this->hasOne('App\ResourceMaterial', 'rm_id', 'res_material_id');
    }

    public function category(){
        return $this->hasOne('App\Category', 'category_id', 'category_id');
    }

    public function difficulty() {
        return $this->hasOne('App\Difficulty', 'difficulty_id', 'difficulty_id');
    }

    public function getDifficulty() {
        return $this->difficulty()->first()->difficulty_label;
    }

    public function getCategory(){
        return $this->category()->first()->category_label;
    }

    public function getDurationString(){
        switch($this->duration){
            case 0.25: return "15 min";
            case 0.5 : return "30 min";
            case 0.75: return "45 min";
            default: return $this->duration." uur";
        }
    }

    public function getResourceDetail() {
        if ($this->res_material_id) {
            return $this->resourceMaterial()
                ->first()
                ->rm_label . ': ' . $this->res_material_detail;
        } else if ($this->res_person_id) {
            return 'Persoon: ' . $this->resourcePerson()->first()->person_label;
        } else {
            return 'Alleen';
        }
    }

    public function getPrevousLearningActivity(){
        return LearningActivityProducing::where('lap_id', $this->prev_lap_id)->first();
    }

    public function getNextLearningActivity(){
        return LearningActivityProducing::where('prev_lap_id', $this->lap_id)->first();
    }

    public function getStatus(){
        $st = DB::table("status")->where('status_id', $this->status_id)->first();
        return $st->status_label;
    }

    public function getFeedback(){
        return Feedback::where('learningactivity_id', $this->lap_id)->first();
    }

    // Note: DND, object comparison
    public function __toString() {
        return $this->lap_id;
    }
}
