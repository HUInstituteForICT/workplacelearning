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
        return $this->belongsTo('App\Student', 'student_id');
    }

    public function workplace(){
        return $this->hasOne('App\Workplace', 'wp_id', 'wp_id');
    }

    public function categories() {
        return $this->hasMany('App\Category', 'wplp_id', 'wplp_id');
    }

    public function resourcePersons() {
        return $this->hasOne('App\ResourcePerson', 'wplp_id', 'wplp_id');
    }

    public function resourceMaterial() {
        return $this->hasOne('App\ResourceMaterial', 'wplp_id', 'wplp_id');
    }

    public function learningActivityProducing() {
        return $this->hasMany('App\LearningActivityProducing', 'wplp_id', 'wplp_id');
    }

    public function getWorkplace(){
        return $this->workplace()->first();
    }

    public function getUnfinishedActivityProducing() {
        return $this->learningActivityProducing()
            ->where('status_id', '=', '2')
            ->orderBy('date', 'asc')
            ->orderBy('lap_id', 'desc')
            ->get();
    }

    public function getCategories() {
        return $this->categories()
            ->orWhere('wplp_id', '=', '0')
            ->orderBy('category_id', 'asc')
            ->get();
    }

    public function hasLoggedHours() {
        return (count($this->getLastActivity(1)) > 0);
    }

    public function getResourcesPerson() {
        return $this->resourcePersons()
            ->orWhere('wplp_id', '=', '0')
            ->orderBy('rp_id', 'asc')
            ->get();
    }

    public function getLastActivity($count) {
        return $this->LearningActivityProducing()
            ->orderBy('date', 'desc')
            ->orderBy('lap_id', 'desc')
            ->limit($count)
            ->get();
    }
}
