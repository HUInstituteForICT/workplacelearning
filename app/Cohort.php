<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cohort extends Model
{

    protected $fillable = ["name", "description", "ep_id"];
    public $timestamps = false;


    public function categories()
    {
        return $this->hasMany(Category::class, 'cohort_id', 'id');
    }

    public function competencies()
    {
        return $this->hasMany(Competence::class, 'cohort_id', 'id');
    }

    public function CompetenceDescription()
    {
        return $this->hasOne(CompetenceDescription::class, 'cohort_id', 'id');
    }

    public function EducationProgram()
    {
        return $this->belongsTo(EducationProgram::class, "ep_id", "ep_id");
    }

    public function ResourcePerson()
    {
        return $this->hasMany(ResourcePerson::class, 'cohort_id', 'id');
    }

    public function Timeslot()
    {
        return $this->hasMany(Timeslot::class, 'cohort_id', 'id');
    }

    public function WorkplaceLearningPeriod()
    {
        return $this->hasMany(WorkplaceLearningPeriod::class, 'cohort_id', 'id');
    }

}
