<?php
/**
 * This file (EducationProgram.php) was created on 01/20/2017 at 10:44.
 * (C) Max Cassee
 * This project was commissioned by HU University of Applied Sciences.
 */

namespace App;

use App\EducationProgramType;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * @property EducationProgramType $educationprogramType
 * @property int $ep_id
 */
class EducationProgram extends Model
{
    // Override the table used for the User Model
    protected $table = 'educationprogram';
    // Disable using created_at and updated_at columns
    public $timestamps = false;
    // Override the primary key column
    protected $primaryKey = 'ep_id';

    // Default
    protected $fillable = [
        'ep_name', 'eptype_id'
    ];

    protected $casts = [
        'eptype_id' => 'int',
    ];

    public function competenceDescription() {
        return $this->hasOne(CompetenceDescription::class, 'education_program_id', 'ep_id');
    }

    public function category() {
        return $this->hasMany(Category::class, 'ep_id', 'ep_id');
    }

    public function educationprogramType()
    {
        return $this->hasOne(EducationProgramType::class, 'eptype_id', 'eptype_id');
    }

    public function student()
    {
        return $this->belongsToMany(\App\Student::class, 'ep_id', 'ep_id');
    }

    public function competence()
    {
        return $this->hasMany(\App\Competence::class, 'educationprogram_id', 'ep_id');
    }

    public function timeslot()
    {
        return $this->hasMany(\App\Timeslot::class, 'edprog_id', 'ep_id');
    }

    public function resourcePerson()
    {
        return $this->hasMany(\App\ResourcePerson::class, 'ep_id', 'ep_id');
    }

    public function getCompetencies()
    {
        return $this->competence()->get();
    }

    /**
     * @return Collection
     */
    public function getTimeslots()
    {
        return $this->timeslot()
            ->where('wplp_id', '=', 0)->get();
    }

    /**
     * @return Collection
     */
    public function getResourcePersons()
    {
        return $this->resourcePerson()
            ->where('ep_id', '=', $this->ep_id)
            ->where('wplp_id', '=', '0')
            ->get();
    }

    public function cohorts() {
        return $this->hasMany(Cohort::class, 'ep_id', 'ep_id');
    }

    // Relations for query builder
    public function getRelationships()
    {
        return ["competenceDescription", "category", "educationprogramType", "student",
                "competence", "timeslot", "resourcePerson"];
    }
}
