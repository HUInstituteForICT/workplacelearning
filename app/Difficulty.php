<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Difficulty extends Model
{
    // Override the table used for the User Model
    protected $table = 'difficulty';
    // Disable using created_at and updated_doat columns
    public $timestamps = false;
    // Override the primary key column
    protected $primaryKey = 'difficulty_id';

    // Default
    protected $fillable = [
        'difficulty_id',
        'difficulty_label'
    ];

    public function learningActivityProducing()
    {
        $this->belongsTo(\App\LearningActivityProducing::class);
    }
}
