<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class CompetenceDescription extends Model
{

    public function educationProgram() {
        return $this->belongsTo(EducationProgram::class, 'ep_id', 'education_program_id');
    }

    protected $hidden = ['data'];

    protected $appends = ['has_data'];

    public function getHasDataAttribute() {
        return $this->data !== null;
    }


}