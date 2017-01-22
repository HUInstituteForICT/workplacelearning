<?php
/**
 * This file (Internship.php) was created on 06/06/2016 at 15:22.
 * (C) Max Cassee
 * This project was commissioned by HU University of Applied Sciences.
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class Categorie extends Model{
    // Override the table used for the User Model
    protected $table = 'categorieen';
    // Disable using created_at and updated_at columns
    public $timestamps = false;
    // Override the primary key column
    protected $primaryKey = 'cg_id';

    // Default
    protected $fillable = [
        'cg_id',
        'cg_value',
        'ss_id',
    ];

    public function InternshipPeriods(){
        return $this->belongsTo('App\InternshipPeriod');
    }

    public function getCategoryName(){
        return $this->cg_value;
    }

    public function setCategoryName($name){
        $this->cg_value = $name;
    }

}