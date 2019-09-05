<?php


namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Student;

class StudentDetails extends Controller
{

    public function __construct()
    {
    }


    public function __invoke(Student $student)
    {


        return view('pages.admin.student_details', [
            'student' => $student
        ]);
    }
}