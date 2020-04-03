<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\EditWorkplaceLearningPeriodRequest;
use App\Repository\Eloquent\CohortRepository;
use App\Services\Updaters\WorkplaceLearningPeriodUpdater;
use App\Student;
use App\WorkplaceLearningPeriod;

class EditWorkplaceLearningPeriod extends Controller
{
    /**
     * @var CohortRepository
     */
    private $cohortRepository;

    /**
     * @var WorkplaceLearningPeriodUpdater
     */
    private $workplaceLearningPeriodUpdater;

    public function __construct(
        CohortRepository $cohortRepository,
        WorkplaceLearningPeriodUpdater $workplaceLearningPeriodUpdater
    ) {
        $this->cohortRepository = $cohortRepository;
        $this->workplaceLearningPeriodUpdater = $workplaceLearningPeriodUpdater;
    }

    public function __invoke(
        EditWorkplaceLearningPeriodRequest $request,
        Student $student,
        WorkplaceLearningPeriod $workplaceLearningPeriod
    ) {
        if ($request->isMethod('POST')) {
            $this->handleUpdate($request, $workplaceLearningPeriod);

            $request->session()->flash('success', 'The workplace learning period of the student has been updated.');

            return redirect()->route('admin-student-edit-wplp',
                [$student, $workplaceLearningPeriod]);
        }

        return view('pages.admin.workplace_learning_period_details')
            ->with('wplp', $workplaceLearningPeriod)
            ->with('cohorts', $this->cohortRepository->cohortsAvailableForStudent($workplaceLearningPeriod->student))
            ->with('canUpdateCohort', !$workplaceLearningPeriod->hasActivities());
    }

    private function handleUpdate(
        EditWorkplaceLearningPeriodRequest $request,
        WorkplaceLearningPeriod $workplaceLearningPeriod
    ): void {
        $this->workplaceLearningPeriodUpdater->update($workplaceLearningPeriod, $request->all());
    }
}
