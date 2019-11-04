<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repository\Eloquent\StudentRepository;
use App\Repository\Eloquent\WorkPlaceLearningPeriodRepository;
use App\Repository\Eloquent\WorkplaceRepository;
use Illuminate\Http\Request;
use App\Student;
use Illuminate\Support\Collection;

class Linking extends Controller
{
    /**
     * @var StudentRepository
     */
    private $studentRepository;

    /**
     * @var WorkPlaceLearningPeriodRepository
     */
    private $wplpRepository;

     /**
     * @var WorkPlaceRepository
     */
    private $workplaceRepository;

    public function __construct(StudentRepository $studentRepository, WorkPlaceLearningPeriodRepository $wplpRepository, WorkPlaceRepository $workplaceRepository)
    {
        $this->studentRepository = $studentRepository;
        $this->wplpRepository = $wplpRepository;
        $this->workplaceRepository = $workplaceRepository;
    }

    public function __invoke(Request $request)
    {
        $wplperiods = $this->wplpRepository->all()->all();
        $workplaces = $this->workplaceRepository->getAll()->all();

        /** @var Collection|Student[] $users */
        $users = $this->studentRepository->all();

        $students = $users->filter(static function (Student $user) {
            return $user->isStudent();
        })->all();

        $teachers = $users->filter(static function (Student $user) {
            return $user->isTeacher();
        })->all();
        

        return view('pages.admin.link-teacher-student', [
            'students'   => $students,
            'teachers'   => $teachers,
            'wplperiods' => $wplperiods,
            'workplaces' => $workplaces,
        ]);
    }
}
