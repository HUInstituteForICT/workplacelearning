<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repository\Eloquent\StudentRepository;
use App\Repository\Eloquent\WorkPlaceLearningPeriodRepository;
use App\Repository\Eloquent\WorkplaceRepository;
use Illuminate\Http\Request;

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
        $students = $this->studentRepository->search($request->get('filter', []));
        $wplperiods = $this->wplpRepository->all();
        $workplaces = $this->workplaceRepository->getAll();

        return view('pages.admin.link-teacher-student', [
            'students' => $students,
            'wplperiods' => $wplperiods,
            'workplaces' => $workplaces,
            'filters'  => $this->studentRepository->getSearchFilters(),
        ]);
    }
}
