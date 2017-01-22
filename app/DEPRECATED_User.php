<?php

namespace App;


use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\UserSetting;

class User extends Authenticatable
{
    // Override the table used for the User Model
    protected $table = 'students';
    // Disable using created_at and updated_at columns
    public $timestamps = false;
    // Override the primary key column
    protected $primaryKey = 'stud_id';

    protected $fillable = [
        'stud_id',
        'studentnummer',
        'voornaam',
        'achternaam',
        'userlevel',
        'geslacht',
        'geboortedatum',
        'email',
        'telefoon',
        'datumregistratie',
        'answer',
        'pw_hash',
    ];

    protected $hidden = [
       'remember_token',
    ];

    public function getInitials(){
        $initials = "";
        if(preg_match('/\s/', $this->voornaam)){
            $names = explode(' ', $this->voornaam);
            foreach($names as $name){
                $initials = (strlen($initials) == 0) ? substr($name, 0, 1)."." : $initials." ".substr($name, 0, 1).".";
            }
        } else {
            $initials = substr($this->voornaam, 0, 1).".";
        }
        return $initials;
    }

    public function getUserLevel(){
        return $this->userlevel;
    }

    public function isAdmin(){
        return ($this->userlevel == 1);
    }

    public function getUserSetting($name){
        return ($this->usersettings()->where('setting_name', '=', $name)->first());
    }

    public function setUserSetting($name, $value){
        $setting = $this->getUserSetting($name);
        if(!$setting)
            $setting = UserSetting::create(array(
                'student_id'    => $this->stud_id,
                'setting_name'  => $name,
                'setting_value' => $value,
            ));
        else {
            $setting->setting_value = $value;
            $setting->save();
        }
        return;
    }

    public function getCurrentInternshipPeriod(){
        if(!$this->getUserSetting('active_internship')) return null;
        return $this->internshipperiods()->where('stud_stid', '=', $this->getUserSetting('active_internship')->setting_value)->first();
    }
    
    public function getCurrentInternship(){
        if($this->getCurrentInternshipPeriod() == null) return null;
        $ip = $this->getCurrentInternshipPeriod();
        if($ip == null) return null;
        return $this->internships()->where('stp_id', '=', $ip->stageplaats_id)->first();
    }

    public function getInternshipPeriods(){
        return $this->internshipperiods()
            ->join('stageplaatsen', 'stageplaats_id', '=', 'stp_id')
            ->orderBy('startdatum', 'desc')
            ->get();
    }

    public function internshipperiods(){
        return $this->hasMany('App\InternshipPeriod', 'student_id', 'stud_id');
    }

    public function internships(){
        return $this->belongsToMany('App\Internship', 'student_stages', 'student_id', 'stageplaats_id');
    }

    public function deadlines(){
        return $this->hasMany('App\Deadline', 'student_id', 'stud_id');
    }

    public function usersettings(){
        return $this->hasMany('App\UserSetting', 'student_id', 'stud_id');
    }

    /* OVERRIDE IN ORDER TO DISABLE THE REMEMBER_ME TOKEN */
    public function getRememberToken(){ return null; }
    public function setRememberToken($value){ }
    public function getRememberTokenName(){ return null;  }
    public function setAttribute($key, $value){
        $isRememberTokenAttribute = $key == $this->getRememberTokenName();
        if (!$isRememberTokenAttribute) {
            parent::setAttribute($key, $value);
        }
    }

    // Override to use pw_hash as field instead of password
    public function getAuthPassword(){
        return $this->pw_hash;
    }
}
