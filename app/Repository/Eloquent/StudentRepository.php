<?php

namespace App\Repository\Eloquent;

use App\Student;

class StudentRepository
{
    public function get(int $id): Student
    {
        return Student::findOrFail($id);
    }

    public function save(Student $student): bool
    {
        return $student->save();
    }
}
