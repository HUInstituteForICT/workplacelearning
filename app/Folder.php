<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Folder extends Model
{

       // Disable using created_at and updated_at columns
   
       protected $table = 'folder';
   
       // Override the primary key column
   
       protected $primaryKey = 'folder_id';

        // Default
        protected $fillable = [
            'title',
            'description',
            'student_id',
        ];

        public function isShared(): bool
        {
            return $this->teacher_id !== null;
        }
    }
