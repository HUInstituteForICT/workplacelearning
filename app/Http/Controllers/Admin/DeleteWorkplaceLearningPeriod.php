<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Interfaces\ProgressRegistrySystemServiceInterface;
use App\Repository\Eloquent\WorkplaceLearningPeriodRepository;
use App\Student;
use App\WorkplaceLearningPeriod;

class DeleteWorkplaceLearningPeriod extends Controller
{
    /**
     * @var ProgressRegistrySystemServiceInterface
     */
    private $progressRegistrySystemService;

    public function __construct(ProgressRegistrySystemServiceInterface $progressRegistrySystemService)
    {
        $this->progressRegistrySystemService = $progressRegistrySystemService;
    }

    public function __invoke(Student $student, WorkplaceLearningPeriod $workplaceLearningPeriod)
    {
        //$this->workplaceLearningPeriodRepository->delete($workplaceLearningPeriod);
        $this->progressRegistrySystemService->deleteWorkplaceLearningPeriod($workplaceLearningPeriod);

        session()->flash('notification', 'Successfully deleted the workplace learning period');

        return redirect(route('admin-student-details', ['student' => $student]));
    }
}
