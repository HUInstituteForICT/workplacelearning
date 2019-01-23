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

    public function findByEmailOrCanvasId(string $email, string $canvasUserId): ?Student
    {
        $student = Student::where('email', '=', $email)->first();

        if($student === null) {
            $student = Student::where('canvas_user_id', '=', $canvasUserId)->first();
        }

        return $student;
    }
}
