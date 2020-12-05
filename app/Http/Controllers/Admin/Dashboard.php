<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Interfaces\StudentSystemServiceInterface;
//use App\Repository\Eloquent\StudentRepository;
use Illuminate\Http\Request;

class Dashboard extends Controller
{
//    /**
//     * @var StudentRepository
//     */
//    private $studentRepository;
    /**
     * @var StudentSystemServiceInterface
     */
    private $studentSystemService;

    public function __construct(StudentSystemServiceInterface $studentSystemService) //StudentRepository $studentRepository
    {
//        $this->studentRepository = $studentRepository;
        $this->studentSystemService = $studentSystemService;
    }

    public function __invoke(Request $request)
    {
//        $students = $this->studentRepository->search($request->get('filter', []), 25, ['educationProgram']);
        $students = $this->studentSystemService->searchStudents($request->get('filter', []), 25, ['educationProgram']);


        return view('pages.admin.dashboard', [
            'students' => $students,
            'filters'  => $this->studentSystemService->getSearchFilters(),
//            'filters'  => $this->studentRepository->getSearchFilters(),
        ]);
    }
}
