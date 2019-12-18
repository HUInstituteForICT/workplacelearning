<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FolderComment extends Model
{

       // Disable using created_at and updated_at columns
   
       protected $table = 'folder_comments';
   
       // Override the primary key column
   
       protected $primaryKey = 'folder_comments_id';

        // Default
        protected $fillable = [
            'text',
            'folder_id',
        ];

        public function author(): BelongsTo
        {
            return $this->belongsTo(Student::class, 'author_id', 'student_id');
        }
    }
