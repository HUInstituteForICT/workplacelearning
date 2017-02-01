<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Competence extends Model {
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
        'educationprogram_id'
    ];

    public function educationProgram() {
        return $this->belongsTo('App\EducationProgram');
    }

    public function learningActivityActing() {
        return $this->belongsTo('App\learningActivityActing');
    }
}
