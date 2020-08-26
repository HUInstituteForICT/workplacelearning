<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Collection;

/**
 * @property Student student
 * @property string title
 * @property string description
 * @property int student_id
 * @property Collection|SavedLearningItem[] savedLearningItems
 * @property Student teacher
 * @property int folder_id
 * @property int teacher_id
 * @property Collection|FolderComment[] folderComments
 */
class Folder extends Model
{
    use SoftDeletes;

    // Override the table used for the User Model
    protected $table = 'folder';

    // Override the primary key column
    protected $primaryKey = 'folder_id';

    // Default
    protected $fillable = [
        'title',
        'description',
        'student_id',
        'teacher_id',
        'created_at',
        'updated_at',
    ];

    public function isShared(): bool
    {
        return $this->teacher_id !== null;
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id', 'student_id');
    }

    public function teacher(): HasOne
    {
        if ($this->isShared()) {
            return $this->HasOne(Student::class, 'student_id', 'teacher_id');
        }

        throw new \RuntimeException('This folder has no teacher or is not yet shared with the teacher.');
    }

    public function savedLearningItems(): BelongsToMany
    {
        return $this->belongsToMany(SavedLearningItem::class, 'sli_to_folder', 'folder_id', 'sli_id');
    }

    public function folderComments(): HasMany
    {
        return $this->hasMany(FolderComment::class, 'folder_id', 'folder_id');
    }

    public function teacherInteracted(): bool
    {
        $teacherComments = $this->folderComments->filter(function (FolderComment $comment) {
            return $comment->author->is($this->teacher);
        });

        return !$teacherComments->isEmpty();
    }
}
