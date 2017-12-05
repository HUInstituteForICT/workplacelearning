<?php
/**
 * This file (EducationProgram.php) was created on 01/20/2017 at 10:44.
 * (C) Max Cassee
 * This project was commissioned by HU University of Applied Sciences.
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer eptype_id ID of the EducationProgramType
 * @property string eptype_name Name of the EducationProgramType
 */
class EducationProgramType extends Model
{
    // Override the table used for the User Model
    protected $table = 'educationprogramtype';
    // Disable using created_at and updated_at columns
    public $timestamps = false;
    // Override the primary key column
    protected $primaryKey = 'eptype_id';

    // Default
    protected $fillable = [
        'eptype_id',
        'eptype_name',
    ];

    public function educationprogram()
    {
        return $this->belongsToMany(\App\EducationProgram::class);
    }
}
