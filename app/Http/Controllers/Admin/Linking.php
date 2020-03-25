<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repository\Eloquent\StudentRepository;
use App\Repository\Eloquent\WorkplaceLearningPeriodRepository;
use App\Repository\Eloquent\WorkplaceRepository;
use App\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class Linking extends Controller
{
    /**
     * @var StudentRepository
     */
    private $studentRepository;

    /**
     * @var WorkplaceLearningPeriodRepository
     */
    private $wplpRepository;

    /**
     * @var WorkplaceRepository
     */
    private $workplaceRepository;

    public function __construct(
        StudentRepository $studentRepository,
        WorkplaceLearningPeriodRepository $wplpRepository,
        WorkplaceRepository $workplaceRepository
    ) {
        $this->studentRepository = $studentRepository;
        $this->wplpRepository = $wplpRepository;
        $this->workplaceRepository = $workplaceRepository;
    }

    public function __invoke(Request $request)
    {
        $workplaceLearningPeriods = $this->wplpRepository->all()->all();

        /** @var Collection|Student[] $users */
        $users = $this->studentRepository->all();

        $students = $users->filter(static function (Student $user) {
            return $user->isStudent();
        })->values()->all();

        $teachers = $users->filter(static function (Student $user) {
            return $user->isTeacher();
        })->values()->all();

        return view('pages.admin.link-teacher-student', [
            'students'                 => $students,
            'teachers'                 => $teachers,
            'workplaceLearningPeriods' => $workplaceLearningPeriods,
        ]);
    }
}
