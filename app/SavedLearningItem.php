<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property string category
 * @property int item_id
 * @property int student_id
 * @property false|string created_at
 * @property false|string updated_at
 * @property Folder[] folders
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
        'created_at',
        'updated_at',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id', 'student_id');
    }

    public function folders(): BelongsToMany {
        return $this->belongsToMany(Folder::class, 'sli_to_folder', 'sli_id', 'folder_id');
    }
}
