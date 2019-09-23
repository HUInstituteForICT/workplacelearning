<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repository\Eloquent\StudentRepository;
use App\Student;
use Illuminate\Http\Request;

class StudentDetails extends Controller
{
    /**
     * @var StudentRepository
     */
    private $studentRepository;

    public function __construct(StudentRepository $studentRepository)
    {
        $this->studentRepository = $studentRepository;
    }

    public function __invoke(Student $student, Request $request)
    {
        if ($request->request->get('user_level')) {
            $this->handleUserLevel($request, $student);
        }

        return view('pages.admin.student_details', [
            'student' => $student,
        ]);
    }

    private function handleUserLevel(Request $request, Student $student): void
    {
        $mapping = [
            'student' => 0,
            'teacher' => 1,
            'admin'   => 2,
        ];

        $newLevel = $mapping[$request->request->get('user_level')];

        $student->userlevel = $newLevel;

        $this->studentRepository->save($student);

        session()->flash('notification', 'Applied new user level to user');
    }
}
