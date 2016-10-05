<?php
/**
 * This file (UserSetting.php) was created on 06/22/2016 at 14:21.
 * (C) Max Cassee
 * This project was commissioned by HU University of Applied Sciences.
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserSetting extends Model{
    // Override the table used for the User Model
    protected $table = 'usersettings';
    // Disable using created_at and updated_at columns
    public $timestamps = false;
    // Override the primary key column
    protected $primaryKey = 'setting_id';

    // Default
    protected $fillable = [
        'setting_id',
        'student_id',
        'setting_name',
        'setting_value',
    ];

    public function user(){
        return $this->belongsTo('App\User');
    }

}