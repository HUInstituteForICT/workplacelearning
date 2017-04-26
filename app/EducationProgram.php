<?php
/**
 * This file (EducationProgram.php) was created on 01/20/2017 at 10:44.
 * (C) Max Cassee
 * This project was commissioned by HU University of Applied Sciences.
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

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
        'ep_id',
        'ep_name',
    ];

    public function educationprogramType()
    {
        return $this->hasOne('App\EducationProgramType', 'eptype_id', 'eptype_id');
    }

    public function student()
    {
        return $this->belongsToMany('App\Student', 'ep_id', 'ep_id');
    }

    public function competence()
    {
        return $this->hasMany('App\Competence', 'educationprogram_id', 'ep_id');
    }

    public function timeslot()
    {
        return $this->hasMany('App\Timeslot', 'edprog_id', 'ep_id');
    }

    public function resourcePerson()
    {
        return $this->hasMany('App\ResourcePerson', 'ep_id', 'ep_id');
    }

    public function getCompetencies()
    {
        return $this->competence()->get();
    }

    public function getTimeslots()
    {
        return $this->timeslot()->get();
    }

    public function getResourcePersons()
    {
        return $this->resourcePerson()
            ->where('ep_id', '=', $this->ep_id)
            ->where('wplp_id', '=', '0')
            ->get();
    }
}
