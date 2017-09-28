<?php
/**
 * This file (Samenwerkingsverband.php) was created on 06/06/2016 at 15:22.
 * (C) Max Cassee
 * This project was commissioned by HU University of Applied Sciences.
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class ResourcePerson extends Model
{
    // Override the table used for the User Model
    protected $table = 'resourceperson';
    // Disable using created_at and updated_at columns
    public $timestamps = false;
    // Override the primary key column
    protected $primaryKey = 'rp_id';

    // Default
    protected $fillable = [
        'rp_id',
        'person_label',
        'ep_id',
        'wplp_id'
    ];

    public function cohort() {
        return $this->belongsTo(Cohort::class, 'cohort_id', 'id');
    }

    public function workplaceLearningPeriod()
    {
        return $this->belongsTo(\App\WorkplaceLearningPeriod::class);
    }

    public function learningActivityProducing()
    {
        return $this->belongsTo(\App\LearningActivityProducing::class);
    }

    public function educationProgram()
    {
        return $this->belongsTo(\App\EducationProgram::class);
    }
}
