<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Interfaces\ProgressRegistrySystemServiceInterface;
use App\Interfaces\StudentSystemServiceInterface;
//use App\Repository\Eloquent\StudentRepository;
use App\Repository\Eloquent\WorkplaceLearningPeriodRepository;
use App\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class Linking extends Controller
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
     * @var WorkplaceLearningPeriodRepository
     */
    private $wplpRepository;

    /**
     * @var ProgressRegistrySystemServiceInterface
     */
    private $progressRegistrySystemService;

    public function __construct(
//        StudentRepository $studentRepository,
        StudentSystemServiceInterface $studentSystemService,
        ProgressRegistrySystemServiceInterface $progressRegistrySystemService
    ) {
//        $this->studentRepository = $studentRepository;
        $this->studentSystemService = $studentSystemService;
        $this->progressRegistrySystemService = $progressRegistrySystemService;
    }

    public function __invoke(Request $request)
    {
        //$workplaceLearningPeriods = $this->wplpRepository->all()->all();
        $workplaceLearningPeriods = $this->progressRegistrySystemService->getAllWorkPlaceLearningPeriods();

        /** @var Collection|Student[] $users */
//        $users = $this->studentRepository->all();
        $users = $this->studentSystemService->getAllStudents();

        $students = $users->filter(static function (Student $user) {
            return $user->isStudent();
        })->values()->all();

        $teachers = $users->filter(static function (Student $user) {
            return $user->isTeacher();
        })->values()->all();

        $admins = $users->filter(static function (Student $user) {
            return $user->isAdmin();
        })->values()->all();

        return view('pages.admin.link-teacher-student', [
            'students'                 => $students,
            'teachers'                 => $teachers,
            'admins'                   => $admins,
            'workplaceLearningPeriods' => $workplaceLearningPeriods,
        ]);
    }
}
