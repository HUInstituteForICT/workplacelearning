<?php
/**
 * This file (UserSetting.php) was created on 06/22/2016 at 14:21.
 * (C) Max Cassee
 * This project was commissioned by HU University of Applied Sciences.
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\UserSetting.
 *
 * @property int          $setting_id
 * @property string       $setting_label
 * @property string|null  $setting_value
 * @property int          $student_id
 * @property \App\Student $student
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserSetting whereSettingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserSetting whereSettingLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserSetting whereSettingValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserSetting whereStudentId($value)
 * @mixin \Eloquent
 */
class UserSetting extends Model
{
    // Override the table used for the User Model
    protected $table = 'usersetting';
    // Disable using created_at and updated_at columns
    public $timestamps = false;
    // Override the primary key column
    protected $primaryKey = 'setting_id';

    // Default
    protected $fillable = [
        'setting_id',
        'student_id',
        'setting_label',
        'setting_value',
    ];

    public function student()
    {
        return $this->belongsTo(\App\Student::class);
    }
}
