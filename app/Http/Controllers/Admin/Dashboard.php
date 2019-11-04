<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repository\Eloquent\StudentRepository;
use Illuminate\Http\Request;

class Dashboard extends Controller
{
    /**
     * @var StudentRepository
     */
    private $studentRepository;

    public function __construct(StudentRepository $studentRepository)
    {
        $this->studentRepository = $studentRepository;
    }

    public function __invoke(Request $request)
    {
        $students = $this->studentRepository->search($request->get('filter', []), 25, ['educationProgram']);

        return view('pages.admin.dashboard', [
            'students' => $students,
            'filters'  => $this->studentRepository->getSearchFilters(),
        ]);
    }
}
