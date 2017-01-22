<?php
/**
 * This file (Samenwerkingsverband.php) was created on 06/06/2016 at 15:22.
 * (C) Max Cassee
 * This project was commissioned by HU University of Applied Sciences.
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class Samenwerkingsverband extends Model{
    // Override the table used for the User Model
    protected $table = 'samenwerkingsverbanden';
    // Disable using created_at and updated_at columns
    public $timestamps = false;
    // Override the primary key column
    protected $primaryKey = 'swv_id';

    // Default
    protected $fillable = [
        'swv_id',
        'swv_value',
        'swv_omschrijving',
        'ss_id',
    ];

    public function InternshipPeriods(){
        return $this->belongsTo('App\InternshipPeriod');
    }
}