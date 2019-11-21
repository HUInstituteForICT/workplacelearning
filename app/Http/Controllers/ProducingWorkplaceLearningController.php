<?php

declare(strict_types=1);
/**
 * This file (InternshipController.php) was created on 06/20/2016 at 01:11.
 * (C) Max Cassee
 * This project was commissioned by HU University of Applied Sciences.
 */

namespace App\Http\Controllers;

// Use the PHP native IntlDateFormatter (note: enable .dll in php.ini)

use App\Category;
use App\Cohort;
use App\Repository\Eloquent\CohortRepository;
use App\Services\CurrentUserResolver;
use App\Workplace;
use App\WorkplaceLearningPeriod;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use InvalidArgumentException;
use Validator;

class ProducingWorkplaceLearningController extends Controller
{
    /**
     * @var CohortRepository
     */
    private $cohortRepository;

    /**
     * @var CurrentUserResolver
     */
    private $currentUserResolver;

    public function __construct(CohortRepository $cohortRepository, CurrentUserResolver $currentUserResolver)
    {
        $this->middleware('auth');
        $this->cohortRepository = $cohortRepository;
        $this->currentUserResolver = $currentUserResolver;
    }

    public function show()
    {
        $workplace = new Workplace();
        $workplace->country = trans('general.netherlands');
        $period = new WorkplaceLearningPeriod();
        $period->hours_per_day = 7.5; // Default hours per day for a new period

        return view('pages.producing.internship')
            ->with('period', $period)
            ->with('workplace', $workplace)
            ->with('cohorts', $this->cohortRepository->cohortsAvailableForStudent($this->currentUserResolver->getCurrentUser()));
    }

    public function edit(WorkplaceLearningPeriod $workplaceLearningPeriod)
    {
        if ($workplaceLearningPeriod->student_id !== Auth::user()->student_id) {
            return redirect()->route('profile')
                ->with('error', __('general.profile-permission'));
        }

        return view('pages.producing.internship')
            ->with('period', $workplaceLearningPeriod)
            ->with('workplace', $workplaceLearningPeriod->workplace)
            ->with('categories', $workplaceLearningPeriod->categories)
            ->with('resource', new Collection())
            ->with('cohorts', [$workplaceLearningPeriod->cohort]);
    }

    public function create(Request $request)
    {
        // Validate the input
        $validator = Validator::make($request->all(), [
            'companyName'          => 'required|max:255|min:3',
            'companyStreet'        => 'required|max:45|min:3',
            'companyHousenr'       => 'required|max:9|min:1',
            'companyPostalcode'    => 'required|postalcode',
            'companyLocation'      => 'required|max:255|min:3',
            'companyCountry'       => 'required|max:255|min:2',
            'contactPerson'        => 'required|max:255|min:3',
            'contactPhone'         => 'required',
            'contactEmail'         => 'required|email|max:255',
            'numdays'              => 'required|integer|min:1',
            'numhours'             => 'required|numeric|min:1|max:24',
            'startdate'            => 'required|date|after:'.date('Y-m-d', strtotime('-6 months')),
            'enddate'              => 'required|date|after:startdate',
            'internshipAssignment' => 'required|min:15|max:500',
            'isActive'             => 'sometimes|required|in:1,0',
            'cohort'               => 'required|exists:cohorts,id',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('period-producing-create')
                ->withErrors($validator)
                ->withInput();
        }

        $cohort = Cohort::find($request['cohort']);

        if ($cohort->disabled === 1 || $cohort->educationProgram->ep_id !== Auth::user()->educationProgram->ep_id) {
            throw new InvalidArgumentException('Unknown cohort');
        }

        // Pass. Create the internship and period.
        $wplPeriod = new WorkplaceLearningPeriod();
        $workplace = new Workplace();

        // Todo use mass assignment
        // Save the workplace first
        $workplace->wp_name = $request['companyName'];
        $workplace->street = $request['companyStreet'];
        $workplace->housenr = $request['companyHousenr'];
        $workplace->postalcode = $request['companyPostalcode'];
        $workplace->town = $request['companyLocation'];
        $workplace->country = $request['companyCountry'];
        $workplace->contact_name = $request['contactPerson'];
        $workplace->contact_email = $request['contactEmail'];
        $workplace->contact_phone = $request['contactPhone'];
        $workplace->numberofemployees = 0;
        $workplace->save();

        // Todo use mass assignment
        $wplPeriod->student_id = Auth::user()->student_id;
        $wplPeriod->wp_id = $workplace->wp_id;
        $wplPeriod->startdate = new Carbon($request['startdate']);
        $wplPeriod->enddate = new Carbon($request['enddate']);
        $wplPeriod->nrofdays = $request['numdays'];
        $wplPeriod->hours_per_day = $request['numhours'];
        $wplPeriod->description = $request['internshipAssignment'];
        $wplPeriod->cohort()->associate($cohort);
        $wplPeriod->save();

        // Set the user setting to the current Internship ID
        if ($request['isActive'] == 1) {
            Auth::user()->setUserSetting('active_internship', $wplPeriod->wplp_id);
        }

        return redirect()->route('profile')->with('success', __('general.edit-saved'));
    }

    public function update(Request $request, WorkplaceLearningPeriod $workplaceLearningPeriod)
    {
        // Validate the input
        $validator = Validator::make($request->all(), [
            'companyName'          => 'required|max:255|min:3',
            'companyStreet'        => 'required|max:45|min:3',
            'companyHousenr'       => 'required|max:4|min:1',
            'companyPostalcode'    => 'required|postalcode',
            'companyLocation'      => 'required|max:255|min:3',
            'companyCountry'       => 'required|max:255|min:2',
            'contactPerson'        => 'required|max:255|min:3',
            'contactPhone'         => 'required|',
            'contactEmail'         => 'required|email|max:255',
            'numdays'              => 'required|integer|min:1',
            'numhours'             => 'required|numeric|min:1|max:24',
            'startdate'            => 'required|date|after:'.date('Y-m-d', strtotime('-6 months')),
            'enddate'              => 'required|date|after:startdate',
            'internshipAssignment' => 'required|min:15|max:500',
            'isActive'             => 'sometimes|required|in:1,0',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('period-producing-edit', ['workplaceLearningPeriod' => $workplaceLearningPeriod])
                ->withErrors($validator)
                ->withInput();
        }

        // Input is valid. Attempt to fetch the WPLP and validate it belongs to the user
        if ($workplaceLearningPeriod->student_id !== Auth::user()->student_id) {
            return redirect()->route('profile')
                ->with('error', __('general.profile-permission'));
        }

        // Succes. Also fetch the associated Workplace Eloquent object and update
        $workplace = Workplace::find($workplaceLearningPeriod->wp_id);

        // Todo use model->fill()
        // Save the workplace first
        $workplace->wp_name = $request['companyName'];
        $workplace->street = $request['companyStreet'];
        $workplace->housenr = $request['companyHousenr'];
        $workplace->postalcode = $request['companyPostalcode'];
        $workplace->town = $request['companyLocation'];
        $workplace->country = $request['companyCountry'];
        $workplace->contact_name = $request['contactPerson'];
        $workplace->contact_email = $request['contactEmail'];
        $workplace->contact_phone = $request['contactPhone'];
        $workplace->numberofemployees = 0;
        $workplace->save();

        // Todo use model->fill()
        $workplaceLearningPeriod->student_id = Auth::user()->student_id;
        $workplaceLearningPeriod->wp_id = $workplace->wp_id;
        $workplaceLearningPeriod->startdate = new Carbon($request['startdate']);
        $workplaceLearningPeriod->enddate = new Carbon($request['enddate']);
        $workplaceLearningPeriod->nrofdays = $request['numdays'];
        $workplaceLearningPeriod->hours_per_day = $request['numhours'];
        $workplaceLearningPeriod->description = $request['internshipAssignment'];
        $workplaceLearningPeriod->save();

        // Set the user setting to the current Internship ID
        if ($request['isActive'] == 1) {
            Auth::user()->setUserSetting('active_internship', $workplaceLearningPeriod->wplp_id);
        }

        return redirect()->route('profile')->with('success', __('general.edit-saved'));
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
            return redirect()->route('profile')->withErrors(__('general.profile-permission')); // $id is invalid or does not belong to the student
        }

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
                ->route('period-producing-edit',
                    ['workplaceLearningPeriod' => Auth::user()->getCurrentWorkplaceLearningPeriod()])
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
        return redirect()->route('period-producing-edit', ['id' => $id])->with('succes', __('general.edit-saved'));
    }
}
