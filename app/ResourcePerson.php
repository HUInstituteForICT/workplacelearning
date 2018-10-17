<?php
/**
 * This file (Samenwerkingsverband.php) was created on 06/06/2016 at 15:22.
 * (C) Max Cassee
 * This project was commissioned by HU University of Applied Sciences.
 */

namespace App;

use App\Interfaces\HasLabelProperty;
use App\Traits\TranslatableEntity;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ResourcePerson.
 *
 * @property int    $rp_id
 * @property string $person_label
 * @property int    $wplp_id
 * @property int    $ep_id
 */
class ResourcePerson extends Model implements HasLabelProperty
{
    use TranslatableEntity;

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
        'wplp_id',
        'cohort_id',
    ];

    public function cohort()
    {
        return $this->belongsTo(Cohort::class, 'cohort_id', 'id');
    }

    public function workplaceLearningPeriod()
    {
        return $this->belongsTo(\App\WorkplaceLearningPeriod::class, 'wplp_id', 'wplp_id');
    }

    public function learningActivityProducing()
    {
        return $this->belongsTo(\App\LearningActivityProducing::class, 'rp_id', 'res_person_id');
    }

    public function educationProgram()
    {
        return $this->belongsTo(\App\EducationProgram::class, 'ep_id', 'ep_id');
    }

    // Relations for query builder
    public function getRelationships()
    {
        return ['cohort', 'workplaceLearningPeriod', 'learningActivityProducing', 'educationProgram'];
    }

    public function getLabel(): string
    {
        return $this->person_label ?? 'Unknown person';
    }
}
