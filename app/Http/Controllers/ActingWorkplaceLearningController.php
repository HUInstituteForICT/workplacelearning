<?php

declare(strict_types=1);
/**
 * This file (InternshipController.php) was created on 06/20/2016 at 01:11.
 * (C) Max Cassee
 * This project was commissioned by HU University of Applied Sciences.
 */

namespace App\Http\Controllers;

// Use the PHP native IntlDateFormatter (note: enable .dll in php.ini)

use App\Http\Requests\Workplace\ActingLearningGoalsUpdateRequest;
use App\Http\Requests\Workplace\ActingWorkplaceCreateRequest;
use App\Http\Requests\Workplace\ActingWorkplaceUpdateRequest;
use App\Interfaces\LearningSystemServiceInterface;
use App\Interfaces\ProgressRegistrySystemServiceInterface;
use App\Interfaces\StudentSystemServiceInterface;
//use App\Repository\Eloquent\CohortRepository;
use App\Services\CurrentPeriodResolver;
use App\Services\CurrentUserResolver;
use App\Services\Factories\ActingWorkplaceFactory;
use App\Services\Factories\LearningGoalFactory;
use App\Workplace;
use App\WorkplaceLearningPeriod;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;

class ActingWorkplaceLearningController
{
    /**
     * @var CurrentUserResolver
     */
    private $currentUserResolver;

    /**
     * @var StudentSystemServiceInterface
     */
    private $studentSystemService;

    /**
     * @var ProgressRegistrySystemServiceInterface
     */
    private $progressRegistrySystemService;

    /**
     * @var LearningSystemServiceInterface
     */
    private $learningSystemService;

    public function __construct(CurrentUserResolver $currentUserResolver, StudentSystemServiceInterface $studentSystemService, ProgressRegistrySystemServiceInterface $progressRegistrySystemService, LearningSystemServiceInterface $learningSystemService)
    {
        $this->currentUserResolver = $currentUserResolver;
        $this->studentSystemService = $studentSystemService;
        $this->progressRegistrySystemService = $progressRegistrySystemService;
        $this->learningSystemService = $learningSystemService;
    }

    public function show(): View
    {
        $workplace = new Workplace();
        $workplace->country = trans('general.netherlands');

        $student = $this->currentUserResolver->getCurrentUser();
        $cohorts = $this->studentSystemService->cohortsAvailableForStudent($student);  //old way: $cohortRepository->cohortsAvailableForStudent($student);

        return view('pages.acting.internship')
            ->with('period', new WorkplaceLearningPeriod())
            ->with('workplace', $workplace)
            ->with('cohorts', $cohorts);
    }

    public function create(
        ActingWorkplaceCreateRequest $request,
        ActingWorkplaceFactory $actingWorkplaceFactory,
        Redirector $redirector
    ): RedirectResponse {
        $actingWorkplaceFactory->createEntities($request->all());

        return $redirector->route('profile')->with('success', __('general.edit-saved'));
    }

    public function edit(WorkplaceLearningPeriod $workplaceLearningPeriod): View
    {
        $workplace = $workplaceLearningPeriod->workplace;

        return view('pages.acting.internship', [
            'period'        => $workplaceLearningPeriod,
            'workplace'     => $workplace,
            'learninggoals' => $workplaceLearningPeriod->learningGoals,
        ]);
    }

    public function update(
        ActingWorkplaceUpdateRequest $request,
        WorkplaceLearningPeriod $workplaceLearningPeriod,
        Redirector $redirector                                                                                           //removed: WorkplaceRepository $workplaceRepository, WorkplaceLearningPeriodRepository $workplaceLearningPeriodRepository
    ): RedirectResponse {
        $this->progressRegistrySystemService->updateWorkplace($workplaceLearningPeriod->workplace, $request->all());     //old way: $workplaceRepository->update($workplaceLearningPeriod->workplace, $request->all());
        $this->progressRegistrySystemService->updateWorkplaceLearningPeriod($workplaceLearningPeriod, $request->all());  //old way: $workplaceLearningPeriodRepository->update($workplaceLearningPeriod, $request->all());

        if ((int) $request->get('isActive') === 1) {
            $student = $this->currentUserResolver->getCurrentUser();
            $student->setActiveWorkplaceLearningPeriod($workplaceLearningPeriod);
        }

        return $redirector->route('profile')->with('success', __('general.edit-saved'));
    }

    public function updateLearningGoals(
        ActingLearningGoalsUpdateRequest $request,
        LearningGoalFactory $learningGoalFactory,
        CurrentPeriodResolver $currentPeriodResolver,
        Redirector $redirector                                                                           //removed: LearningGoalUpdater $learningGoalUpdater
    ): RedirectResponse {
        if ($request->has('learningGoal')) {
            $this->learningSystemService->updateLearningGoals($request->get('learningGoal'));       //old way: $learningGoalUpdater->updateLearningGoals($request->get('learningGoal'));
        }
        if ($request->has('new_learninggoal_name') && !empty($request->get('new_learninggoal_name'))) {
            $learningGoalFactory->createLearningGoal([
                'label'       => $request->get('new_learninggoal_name'),
                'description' => $request->get('new_learninggoal_description'),
                'wplp_id'     => $currentPeriodResolver->getPeriod()->wplp_id,
            ]);
        }

        return $redirector->route('period-acting-edit',
            ['workplaceLearningPeriod' => $currentPeriodResolver->getPeriod()->wplp_id]
        )->with('success', __('general.edit-saved'));
    }
}
