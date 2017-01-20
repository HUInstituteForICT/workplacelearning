<?php
/**
 * This file (WorkplaceLearningPeriod.php) was created on 20/01/2017 at 12:32.
 * (C) Max Cassee
 * This project was commissioned by HU University of Applied Sciences.
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class WorkplaceLearningPeriod extends Model{
    // Override the table used for the User Model
    protected $table = 'workplacelearningperiod';
    // Disable using created_at and updated_at columns
    public $timestamps = false;
    // Override the primary key column
    protected $primaryKey = 'wplp_id';

    // Default
    protected $fillable = [
        'wplp_id',
        'student_id',
        'wp_id',
        'startdate',
        'enddate',
        'nrofdays',
        'description',
    ];

    public function student(){
        return $this->belongsTo('App\Student', 'student_id', 'student_id');
    }

    public function workplace(){
        return $this->hasOne('App\Workplace', 'wp_id', 'wp_id');
    }

}