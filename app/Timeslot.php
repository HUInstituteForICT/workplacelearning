<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Timeslot extends Model
{
    // Override the table used for the User Model
    protected $table = 'timeslot';
    // Disable using created_at and updated_at columns
    public $timestamps = false;
    // Override the primary key column
    protected $primaryKey = 'timeslot_id';

    // Default
    protected $fillable = [
        'timeslot_id',
        'timeslot_text',
        'edprog_id'
    ];

    public function educationProgram()
    {
        return $this->belongsTo(\App\EducationProgram::class);
    }
}
