<?php
/**
 * This file (Deadline.php) was created on 06/21/2016 at 13:44.
 * (C) Max Cassee
 * This project was commissioned by HU University of Applied Sciences.
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class Deadline extends Model
{
    // Override the table used for the User Model
    protected $table = 'deadline';
    // Disable using created_at and updated_at columns
    public $timestamps = false;
    // Override the primary key column
    protected $primaryKey = 'dl_id';

    // Default
    protected $fillable = [
        'dl_id',
        'dl_value',
        'dl_datetime',
        'student_id',
    ];

    public function User()
    {
        return $this->belongsTo('App\Student');
    }
}
