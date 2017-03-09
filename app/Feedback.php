<?php
/**
 * This file (Deadline.php) was created on 08/18/2016 at 15:30.
 * (C) Max Cassee
 * This project was commissioned by HU University of Applied Sciences.
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model{
    // Override the table used for the User Model
    protected $table = 'feedback';
    // Disable using created_at and updated_at columns
    public $timestamps = false;
    // Override the primary key column
    protected $primaryKey = 'fb_id';

    // Default
    protected $fillable = [
        'fb_id',
        'learningactivity_id',
        'notfinished',
        'initiative',
        'progress_satisfied',
        'support_requested',
        'supported_provided_wp',
        'nextstep_self',
        'support_needed_wp',
        'support_needed_ed',
    ];

    public function isSaved(){
        return (strlen($this->notfinished) > 0);
    }

    public function learningactivityproducing(){
        return $this->belongsTo('App\LearningActivityProducing');
    }
}