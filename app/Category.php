<?php
/**
 * This file (Internship.php) was created on 06/06/2016 at 15:22.
 * (C) Max Cassee
 * This project was commissioned by HU University of Applied Sciences.
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model{
    // Override the table used for the User Model
    protected $table = 'category';
    // Disable using created_at and updated_at columns
    public $timestamps = false;
    // Override the primary key column
    protected $primaryKey = 'category_id';

    // Default
    protected $fillable = [
        'category_id',
        'category_label',
        'wplp_id',
    ];

    public function InternshipPeriods(){
        return $this->belongsTo('App\WorkplaceLearningPeriod');
    }

    public function getCategoryLabel(){
        return $this->category_label;
    }

    public function setCategoryLabel($label){
        $this->category_label = $label;
    }

}