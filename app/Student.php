<?php

namespace App;

use App\EducationProgram;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Class Student
 * @property int $student_id
 * @property string $email
 * @property string $firstname
 * @property string $lastname
 * @property integer $userlevel
 * @property EducationProgram $educationProgram
 * @property string $pw_hash
 *
 */
class Student extends Authenticatable
{
    use Notifiable, CanResetPassword;
    // Override the table used for the User Model
    protected $table = 'student';
    // Disable using created_at and updated_at columns
    public $timestamps = false;
    // Override the primary key column
    protected $primaryKey = 'student_id';

    protected $fillable = [
        'student_id',
        'studentnr',
        'firstname',
        'lastname',
        'ep_id',
        'userlevel',
        'gender',
        'birthdate',
        'email',
        'registrationdate',
        'answer',
        'pw_hash',
        'locale'
    ];


    public static $locales = [
        "nl" => "Nederlands",
        "en" => "English",
    ];

    protected $hidden = [
        'remember_token',
    ];

    private $currentWorkplaceLearningPeriod = null;

    private $userSettings = [];

    public function getInitials()
    {
        $initials = "";
        if (preg_match('/\s/', $this->firstname)) {
            $names = explode(' ', $this->lastname);
            foreach ($names as $name) {
                $initials = (strlen($initials) == 0) ? substr($name, 0, 1)."." : $initials." ".substr($name, 0, 1).".";
            }
        } else {
            $initials = substr($this->firstname, 0, 1).".";
        }
        return $initials;
    }

    public function getUserLevel()
    {
        return $this->userlevel;
    }

    public function isAdmin()
    {
        return ($this->userlevel > 0);
    }

    public function getUserSetting($label, $forceRefresh = false)
    {
        if($forceRefresh || !isset($this->userSettings[$label])) {
            $this->userSettings[$label] = $this->usersettings()->where('setting_label', '=', $label)->first();
        }
        return $this->userSettings[$label];
    }

    public function setUserSetting($label, $value)
    {
        $setting = $this->getUserSetting($label);
        if (!$setting) {
            $setting = UserSetting::create([
                'student_id'    => $this->student_id,
                'setting_label'  => $label,
                'setting_value' => $value,
            ]);
        } else {
            $setting->setting_value = $value;
            $setting->save();
        }
        $this->userSettings[$label] = $setting;
        return;
    }

    public function educationProgram():BelongsTo
    {
        return $this->belongsTo(EducationProgram::class, 'ep_id', 'ep_id');
    }

    public function deadlines()
    {
        return $this->hasMany(\App\Deadline::class, 'student_id', 'student_id');
    }

    public function usersettings()
    {
        return $this->hasMany(\App\UserSetting::class, 'student_id', 'student_id');
    }

    public function workplaceLearningPeriods()
    {
        return $this->hasMany(\App\WorkplaceLearningPeriod::class, 'student_id', 'student_id');
    }

    public function workplaces()
    {
        return $this->belongsToMany(\App\Workplace::class, 'workplacelearningperiod', 'student_id', 'wp_id');
    }

    public function getWorkplaceLearningPeriods()
    {
        return $this->workplaceLearningPeriods()
            ->join("workplace", 'workplacelearningperiod.wp_id', '=', 'workplace.wp_id')
            ->orderBy('startdate', 'desc')
            ->get();
    }

    /**
     * @return null|WorkplaceLearningPeriod
     */
    public function getCurrentWorkplaceLearningPeriod(): ?WorkplaceLearningPeriod
    {
        if($this->currentWorkplaceLearningPeriod === null) {
            if (!$this->getUserSetting('active_internship')) {
                return null;
            }
            $this->currentWorkplaceLearningPeriod = $this->workplaceLearningPeriods()->where('wplp_id', '=',
                $this->getUserSetting('active_internship')->setting_value)->first();
        }

        return $this->currentWorkplaceLearningPeriod;
    }

    public function getCurrentWorkplace()
    {
        if (($wplp = $this->getCurrentWorkplaceLearningPeriod()) == null) {
            return null;
        }
        return $this->workplaces()->where('workplace.wp_id', '=', $wplp->wp_id)->first();
    }

    /**
     * @return EducationProgram
     */
    public function getEducationProgram()
    {
        return $this->educationProgram()->first();
    }

    public function getEducationProgramType()
    {
        return $this->getEducationProgram()->educationprogramType()->first();
    }

    /**
     * @return Cohort
     */
    public function currentCohort()
    {
        return $this->getCurrentWorkplaceLearningPeriod()->cohort;
    }

    /* OVERRIDE IN ORDER TO DISABLE THE REMEMBER_ME TOKEN */
    public function getRememberToken()
    {
        return null;
    }
    public function setRememberToken($value)
    {
    }
    public function getRememberTokenName()
    {
        return null;
    }
    public function setAttribute($key, $value)
    {
        $isRememberTokenAttribute = $key == $this->getRememberTokenName();
        if (!$isRememberTokenAttribute) {
            parent::setAttribute($key, $value);
        }
    }

    // Override to use pw_hash as field instead of password
    public function getAuthPassword()
    {
        return $this->pw_hash;
    }

    public function setActiveWorkplaceLearningPeriod(WorkplaceLearningPeriod $workplaceLearningPeriod)
    {
        $this->setUserSetting('active_internship', $workplaceLearningPeriod->wplp_id);
    }
}
