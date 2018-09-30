<?php
/**
 * This file (InternshipController.php) was created on 06/20/2016 at 01:11.
 * (C) Max Cassee
 * This project was commissioned by HU University of Applied Sciences.
 */

namespace App\Http\Controllers;

// Use the PHP native IntlDateFormatter (note: enable .dll in php.ini)

use App\Category;
use App\Http\Requests\Workplace\ActingWorkplaceCreateRequest;
use App\Http\Requests\Workplace\ActingWorkplaceUpdateRequest;
use App\LearningGoal;
use App\Repository\Eloquent\CohortRepository;
use App\Repository\Eloquent\WorkplaceLearningPeriodRepository;
use App\Repository\Eloquent\WorkplaceRepository;
use App\Services\CurrentUserResolver;
use App\Services\Factories\ActingWorkplaceFactory;
use App\Workplace;
use App\WorkplaceLearningPeriod;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Validator;

class ActingWorkplaceLearningController
{
    /**
     * @var CurrentUserResolver
     */
    private $currentUserResolver;

    public function __construct(CurrentUserResolver $currentUserResolver)
    {
        $this->currentUserResolver = $currentUserResolver;
    }

    public function show(CohortRepository $cohortRepository): View
    {
        $workplace = new Workplace();
        $workplace->country = trans('general.netherlands');

        $student = $this->currentUserResolver->getCurrentUser();
        $cohorts = $cohortRepository->cohortsAvailableForStudent($student);

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

    public function edit(WorkplaceLearningPeriod $workplaceLearningPeriod)
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
        Redirector $redirector,
        WorkplaceRepository $workplaceRepository,
        WorkplaceLearningPeriodRepository $workplaceLearningPeriodRepository
    ): RedirectResponse {
        $workplaceRepository->update($workplaceLearningPeriod->workplace, $request->all());

        $workplaceLearningPeriodRepository->update($workplaceLearningPeriod, $request->all());

        if ((int) $request->get('isActive') === 1) {
            $student = $this->currentUserResolver->getCurrentUser();
            $student->setActiveWorkplaceLearningPeriod($workplaceLearningPeriod);
        }

        return $redirector->route('profile')->with('success', Lang::get('general.edit-saved'));
    }

    public function updateLearningGoals(Request $request, $id)
    {
        // Verify the given ID is valid and belongs to the student
        $belongsToStudent = false;
        foreach (Auth::user()->workplacelearningperiods()->get() as $ip) {
            if ($ip->wplp_id == $id) {
                $belongsToStudent = true;
                break;
            }
        }
        if (!$belongsToStudent) {
            return redirect()->route('profile')->withErrors(Lang::get('general.profile-permission'));
        } // $id is invalid or does not belong to the student

        $validator = Validator::make($request->all(), [
            'learninggoal_name.*'        => 'required|min:3|max:50',
            'learninggoal_description.*' => 'required|min:3, max:1000',
        ]);
        $validator->sometimes(
            'new_learninggoal_name',
            'required|min:3|max:50',
            function ($input) {
                return strlen($input->new_learninggoal_name) > 0;
            }
        );

        $validator->sometimes(
            'new_learninggoal_description',
            'required|min:3|max:1000',
            function ($input) {
                return strlen($input->new_learninggoal_name) > 0;
            }
        );

        if ($validator->fails()) {
            // Noes. errors occurred. Exit back to profile page with errors
            return redirect()
                ->route('period-acting-edit', ['id' => $id])
                ->withErrors($validator)
                ->withInput();
        }
        if (isset($request['learninggoal_name'])) {
            foreach ($request['learninggoal_name'] as $lg_id => $name) {
                $learningGoal = LearningGoal::find($lg_id);
                $description = $request['learninggoal_description'][$lg_id];
                if (is_null($learningGoal)) {
                    $learningGoal = new LearningGoal();
                    $learningGoal->wplp_id = $id;
                }
                $learningGoal->learninggoal_label = $name;
                $learningGoal->description = $description;
                $learningGoal->save();
            }
        }

        if (strlen($request['new_learninggoal_name']) > 0) {
            $learningGoal = new LearningGoal();
            $learningGoal->learninggoal_label = $request['new_learninggoal_name'];
            $learningGoal->description = $request['new_learninggoal_description'];
            $learningGoal->wplp_id = $id;
            $learningGoal->save();
        }

        // Done, redirect back to profile page
        return redirect()->route('period-acting-edit', ['id' => $id])->with(
            'success',
            Lang::get('general.edit-saved')
        );
    }

    public function updateCategories(Request $request, $id)
    {
        // Verify the given ID is valid and belongs to the student
        $belongsToStudent = false;
        foreach (Auth::user()->workplacelearningperiods()->get() as $ip) {
            if ($ip->wplp_id == $id) {
                $belongsToStudent = true;
                break;
            }
        }
        if (!$belongsToStudent) {
            return redirect()->route('profile')->withErrors(Lang::get('general.profile-permission'));
        } // $id is invalid or does not belong to the student

        // Inject the new item into the request array for processing and validation if it is filled in by the user
        if (!empty($request['newcat']['0']['cg_label'])) {
            $request['cat'] = array_merge(((is_array($request['cat'])) ? $request['cat'] : []), $request['newcat']);
        }

        $validator = Validator::make($request->all(), [
            'cat.*.wplp_id'  => 'required|digits_between:1,5',
            'cat.*.cg_id'    => 'required|digits_between:1,5',
            'cat.*.cg_label' => 'required|min:3|max:50',
        ]);
        if ($validator->fails()) {
            // Noes. errors occured. Exit back to profile page with errors
            return redirect()
                ->route('period-acting-edit', ['id' => $id])
                ->withErrors($validator)
                ->withInput();
        }
        // All is well :)
        foreach ($request['cat'] as $cat) {
            // Either update or create a new row.
            $category = Category::find($cat['cg_id']);
            if (is_null($category)) {
                $category = new Category();
                $category->wplp_id = $cat['wplp_id'];
            }
            $category->category_label = $cat['cg_label'];
            $category->save();
        }

        // Done, redirect back to profile page
        return redirect()->route('period-acting-edit', ['id' => $id])->with(
                'succes',
                Lang::get('general.edit-saved')
            );
    }
}
