<?php

declare(strict_types=1);

namespace App\Repository\Eloquent;
use App\Folder;
use App\Student;

class FolderRepository
{

    public function all()
    {
        return Folder::all();
    }

    public function save(Folder $folder): bool
    {
        return $folder->save();
    }

    public function findByTeacherId(Student $teacher)
    {
        return Folder::where('teacher_id', '=', $teacher->student_id)->get();

        
    }
}