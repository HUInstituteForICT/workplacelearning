<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Interfaces\StudentSystemServiceInterface;
//use App\Repository\Eloquent\StudentRepository;
use App\Services\CurrentUserResolver;
use App\Student;

class DeleteStudent extends Controller
{
//    /**
//     * @var StudentRepository
//     */
//    private $studentRepository;

    /**
     * @var StudentSystemServiceInterface
     */
    private $studentSystemService;

    /**
     * @var CurrentUserResolver
     */
    private $currentUserResolver;

    public function __construct(StudentSystemServiceInterface $studentSystemService, CurrentUserResolver $currentUserResolver) //StudentRepository $studentRepository
    {
//        $this->studentRepository = $studentRepository;
        $this->studentSystemService = $studentSystemService;
        $this->currentUserResolver = $currentUserResolver;
    }

    public function __invoke(Student $student)
    {
        if ($student->student_id === $this->currentUserResolver->getCurrentUser()->student_id) {
            session()->flash('notification', 'You cannot delete yourself');

            return redirect(route('admin-student-details', ['student' => $student]));
        }

//        $this->studentRepository->delete($student);
        $this->studentSystemService->deleteStudent($student);

        session()->flash('notification', 'Successfully deleted the student');

        return redirect(route('admin-dashboard'));
    }
}
