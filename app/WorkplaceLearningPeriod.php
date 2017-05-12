<?php
/**
 * This file (WorkplaceLearningPeriod.php) was created on 20/01/2017 at 12:32.
 * (C) Max Cassee
 * This project was commissioned by HU University of Applied Sciences.
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class WorkplaceLearningPeriod extends Model
{
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

    public function student()
    {
        return $this->belongsTo(\App\Student::class, 'student_id', 'student_id');
    }

    public function workplace()
    {
        return $this->hasOne(\App\Workplace::class, 'wp_id', 'wp_id');
    }

    public function categories()
    {
        return $this->hasMany(\App\Category::class, 'wplp_id', 'wplp_id');
    }

    public function learningGoals()
    {
        return $this->hasMany(\App\LearningGoal::class, 'wplp_id', 'wplp_id');
    }

    public function resourcePerson()
    {
        return $this->hasOne(\App\ResourcePerson::class, 'wplp_id', 'wplp_id');
    }

    public function resourceMaterial()
    {
        return $this->hasOne(\App\ResourceMaterial::class, 'wplp_id', 'wplp_id');
    }

    public function learningActivityProducing()
    {
        return $this->hasMany(\App\LearningActivityProducing::class, 'wplp_id', 'wplp_id');
    }

    public function learningActivityActing()
    {
        return $this->hasMany(\App\LearningActivityActing::class, 'wplp_id', 'wplp_id');
    }

    public function getLearningActivityActingById($id)
    {
        return $this->learningActivityActing()
            ->where('laa_id', '=', $id)
            ->first();
    }

    public function getLearningActivityProducingById($id)
    {
        return $this->learningActivityProducing()
            ->where('lap_id', '=', $id)
            ->first();
    }

    public function getWorkplace()
    {
        return $this->workplace()->first();
    }

    public function getUnfinishedActivityProducing()
    {
        return $this->learningActivityProducing()
            ->where('status_id', '=', '2')
            ->orderBy('date', 'asc')
            ->orderBy('lap_id', 'desc')
            ->get();
    }

    public function getCategories()
    {
        return $this->categories()
            ->orWhere('wplp_id', '=', '0')
            ->orderBy('category_id', 'asc')
            ->get();
    }

    public function getLearningGoals()
    {
        return $this->learningGoals()
            ->orderBy('learninggoal_id', 'asc')
            ->get();
    }

    public function hasLoggedHours()
    {
        return (count($this->getLastActivity(1)) > 0);
    }

    public function getNumLoggedHours()
    {
        return ($this->getLastActivity(1000000, 0)->sum('duration'));
    }

    public function getResourcePersons()
    {
        return $this->resourcePerson()
            ->orderBy('rp_id', 'asc')
            ->get();
    }

    public function getResourceMaterials()
    {
        return $this->resourceMaterial()
            ->orWhere('wplp_id', '=', '0')
            ->orderBy('rm_id', 'asc')
            ->get();
    }

    public function getLastActivity($count, $offset = 0)
    {
        switch ($this->student()->first()->ep_id) {
            case 1:
                return $this->getLastActivityProducing($count, $offset);
                break;
            case 2:
                return $this->getLastActivityActing($count, $offset);
                break;
            default:
                return null;
        }
    }

    private function getLastActivityProducing($count, $offset = 0)
    {
        return $this->LearningActivityProducing()
            ->orderBy('date', 'desc')
            ->orderBy('lap_id', 'desc')
            ->skip($offset)
            ->take($count)
            ->get();
    }

    private function getLastActivityActing($count, $offset = 0)
    {
        return $this->LearningActivityActing()
            ->orderBy('date', 'desc')
            ->orderBy('laa_id', 'desc')
            ->skip($offset)
            ->take($count)
            ->get();
    }
}
