<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property string text
 * @property int folder_id
 * @property int author_id
 * @property int folder_comments_id
 * @property Folder folder
 * @property Student author
 */
class FolderComment extends Model
{
    // Override the table used for the User Model
    protected $table = 'folder_comments';

    // Override the primary key column
    protected $primaryKey = 'folder_comments_id';

    // Default
    protected $fillable = [
        'text',
        'folder_id',
        'author_id',
        'created_at',
        'created_at',
    ];

    public function folder(): HasOne
    {
        return $this->hasOne(Folder::class, 'folder_id', 'folder_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'author_id', 'student_id');
    }
}
