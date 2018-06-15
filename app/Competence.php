<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * @property int $competence_id
 * @property string $competence_label
 */
class Competence extends Model
{
    // Override the table used for the User Model
    protected $table = 'competence';
    // Disable using created_at and updated_at columns
    public $timestamps = false;
    // Override the primary key column
    protected $primaryKey = 'competence_id';

    // Default
    protected $fillable = [
        'competence_id',
        'competence_label',
        'educationprogram_id',
        'cohort_id'
    ];

    public function cohort() {
        return $this->belongsTo(Cohort::class, 'cohort_id', 'id');
    }

    public function educationProgram()
    {
        return $this->belongsTo(\App\EducationProgram::class, 'educationprogram_id', 'ep_id');
    }

    public function learningActivityActing()
    {
        return $this->belongsToMany(\App\LearningActivityActing::class, 'activityforcompetence', 'competence_id', 'learningactivity_id');
    }
}
