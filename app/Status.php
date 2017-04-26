<?php
/**
 * This file (Status.php) was created on 02/12/2017 at 14:02.
 * (C) Max Cassee
 * This project was commissioned by HU University of Applied Sciences.
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    // Override the table used for the User Model
    protected $table = 'status';
    // Disable using created_at and updated_at columns
    public $timestamps = false;
    // Override the primary key column
    protected $primaryKey = 'status_id';

    // Default
    protected $fillable = [
        'status_id',
        'status_label',
    ];

    public function learningActivityProducing()
    {
        $this->belongsTo('App\LearningActivityProducing');
    }
}
