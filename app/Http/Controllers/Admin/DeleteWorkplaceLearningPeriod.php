<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repository\Eloquent\WorkplaceLearningPeriodRepository;
use App\Student;
use App\WorkplaceLearningPeriod;

class DeleteWorkplaceLearningPeriod extends Controller
{
    /**
     * @var WorkplaceLearningPeriodRepository
     */
    private $workplaceLearningPeriodRepository;

    public function __construct(WorkplaceLearningPeriodRepository $workplaceLearningPeriodRepository)
    {
        $this->workplaceLearningPeriodRepository = $workplaceLearningPeriodRepository;
    }

    public function __invoke(Student $student, WorkplaceLearningPeriod $workplaceLearningPeriod)
    {
        $this->workplaceLearningPeriodRepository->delete($workplaceLearningPeriod);

        session()->flash('notification', 'Successfully deleted the workplace learning period');

        return redirect(route('admin-student-details', ['student' => $student]));
    }
}
