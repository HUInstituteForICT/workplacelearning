<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;

class LearningActivityProducing extends Model
{
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

    public function previousLearningActivityProducing() {
        return $this->hasOne(LearningActivityProducing::class, 'prev_lap_id', 'lap_id');
    }

    public function nextLearningActivityProducing() {
        return $this->belongsTo(LearningActivityProducing::class, 'lap_id', 'prev_lap_id');
    }

    public function workplaceLearningPeriod()
    {
        return $this->belongsTo(\App\WorkplaceLearningPeriod::class, 'wplp_id', 'wplp_id');
    }

    public function feedback()
    {
        return $this->hasOne(\App\Feedback::class, 'learningactivity_id', 'lap_id');
    }

    public function resourcePerson()
    {
        return $this->hasOne(\App\ResourcePerson::class, 'rp_id', 'res_person_id');
    }

    public function resourceMaterial()
    {
        return $this->hasOne(\App\ResourceMaterial::class, 'rm_id', 'res_material_id');
    }

    public function category()
    {
        return $this->hasOne(\App\Category::class, 'category_id', 'category_id');
    }

    public function difficulty()
    {
        return $this->hasOne(\App\Difficulty::class, 'difficulty_id', 'difficulty_id');
    }

    public function getDifficulty()
    {
        return Lang::get('general.'.strtolower($this->difficulty->difficulty_label));
    }

    public function getCategory()
    {
        // Note the translation below. This means never trust the return value to refer to anything in the system
        return __($this->category()->first()->category_label);
    }

    public function getDurationString()
    {
        switch ($this->duration) {
            case 0.25:
                return "15 min";
            case 0.5:
                return "30 min";
            case 0.75:
                return "45 min";
            case ($this->duration < 1):
                return round($this->duration * 60) . " min";
            default:
                return $this->duration." " . Lang::get('general.hour');
        }
    }

    public function getResourceDetail()
    {
        // Note the translation(s) below. This means never trust the return value to refer to anything in the system
        if ($this->res_material_id) {
            return __($this->resourceMaterial()
                ->first()
                ->rm_label) . ': ' . $this->res_material_detail;
        } else if ($this->res_person_id) {
            return Lang::get('activity.producing.person').': ' . __($this->resourcePerson()->first()->person_label);
        } else {
            return __('activity.alone');
        }
    }

    public function getPrevousLearningActivity()
    {
        return LearningActivityProducing::where('lap_id', $this->prev_lap_id)->first();
    }

    public function getNextLearningActivity()
    {
        return LearningActivityProducing::where('prev_lap_id', $this->lap_id)->first();
    }

    public function status() {
        return $this->hasOne(Status::class, 'status_id', 'status_id');
    }

    public function getStatus()
    {
        $st = DB::table("status")->where('status_id', $this->status_id)->first();
        return Lang::get('general.'. strtolower($st->status_label));
    }

    public function getFeedback()
    {
        return Feedback::where('learningactivity_id', $this->lap_id)->first();
    }

    // Note: DND, object comparison
    public function __toString()
    {
        return $this->lap_id;
    }
}
