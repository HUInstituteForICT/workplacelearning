<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string category
 * @property int item_id
 * @property int student_id
 * @property false|string created_at
 * @property false|string updated_at
 */
class SavedLearningItem extends Model
{
    // Disable using created_at and updated_at columns
    public $timestamps = false;

    // Override the table used for the User Model
    protected $table = 'saved_learning_items';

    // Override the primary key column
    protected $primaryKey = 'sli_id';

    // Default
    protected $fillable = [
        'category',
        'item_id',
        'student_id',
        'folder',
        'created_at',
        'updated_at',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id', 'student_id');
    }
}
