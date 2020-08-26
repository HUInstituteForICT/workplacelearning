<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SavedLearningItem extends Model
{
    // Override the table used for the User Model
    public $timestamps = false;

    // Disable using created_at and updated_at columns

    protected $table = 'saved_learning_items';

    // Override the primary key column

    protected $primaryKey = 'sli_id';

    // Default
    protected $fillable = [
        'category',
        'item_id',
        'student_id',
    ];
}
