<?php
/**
 * This file (InternshipController.php) was created on 06/20/2016 at 01:11.
 * (C) Max Cassee
 * This project was commissioned by HU University of Applied Sciences.
 */

namespace app\Http\Controllers;

// Use the PHP native IntlDateFormatter (note: enable .dll in php.ini)

use App\Category;
use App\Cohort;
use App\Workplace;
use App\WorkplaceLearningPeriod;
use App\LearningGoal;
use http\Exception\InvalidArgumentException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Lang;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class ActingWorkplaceLearningController extends Controller
{

    public function show()
    {
        $workplace = new Workplace();
        $workplace->country = trans('general.netherlands');
        return view("pages.acting.internship")
            ->with("period", new WorkplaceLearningPeriod)
            ->with("workplace", $workplace)
            ->with('cohorts', Auth::user()->getEducationProgram()->cohorts()->where('disabled', '=', 0)->get());
    }

    public function edit($id)
    {
        $wplPeriod = WorkplaceLearningPeriod::find($id);
        if (is_null($wplPeriod) || $wplPeriod->student_id != Auth::user()->student_id) {
            return redirect()->route('profile')
                ->with('error', Lang::get('errors.internship-no-permission'));
        } else {
            return view('pages.acting.internship')
                ->with("period", $wplPeriod)
                ->with("workplace", Workplace::find($wplPeriod->wp_id))
                ->with("learninggoals", $wplPeriod->getLearningGoals())
                ->with("resource", new Collection)
                ->with('cohorts', collect($wplPeriod->cohort()->get()));
        }
    }

    public function create(Request $request)
    {
        // Validate the input
        $validator = Validator::make($request->all(), [
            'companyName'          => 'required|max:255|min:3',
            'companyStreet'        => 'required|max:45|min:3',
            'companyHousenr'       => 'required|max:9|min:1',
            //
            'companyPostalcode'    => 'required|postalcode',
            'companyLocation'      => 'required|max:255|min:3',
            'companyCountry'       => 'required|max:255|min:2',
            'contactPerson'        => 'required|max:255|min:3',
            'contactPhone'         => 'required',
            'contactEmail'         => 'required|email|max:255',
            'numdays'              => 'required|integer|min:1',
            'startdate'            => 'required|date|after:' . date("Y-m-d", strtotime('-6 months')),
            'enddate'              => 'required|date|after:startdate',
            'internshipAssignment' => 'required|min:15|max:500',
            'isActive'             => 'sometimes|required|in:1,0',
            "cohort"               => "required|exists:cohorts,id",

        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('period-acting-create')
                ->withErrors($validator)
                ->withInput();
        }

        $cohort = Cohort::find($request['cohort']);

        if ($cohort->educationProgram->ep_id !== Auth::user()->educationProgram->ep_id || $cohort->disabled === 1) {
            throw new InvalidArgumentException("Unknown cohort");
        }

        // Pass. Create the internship and period.
        $wplPeriod = new WorkplaceLearningPeriod;
        $wplPeriod->hours_per_day = 7.5; // Although not used in acting, still set it as its not nullable in DB
        $workplace = new Workplace;


        // Todo use model->fill($request)
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

        // Todo use model->fill($workplace)
        $wplPeriod->student_id = Auth::user()->student_id;
        $wplPeriod->wp_id = $workplace->wp_id;
        $wplPeriod->startdate = $request['startdate'];
        $wplPeriod->enddate = $request['enddate'];
        $wplPeriod->nrofdays = $request['numdays'];
        $wplPeriod->description = $request['internshipAssignment'];
        $wplPeriod->cohort()->associate($cohort);
        $wplPeriod->save();

        // Create unplanned learning as default learning goal
        $learningGoal = new LearningGoal;
        $learningGoal->learninggoal_label = Lang::get('general.default.learninggoal_label');
        $learningGoal->description = Lang::get('general.default.learninggoal_desc');
        $learningGoal->wplp_id = $wplPeriod->wplp_id;
        $learningGoal->save();

        // Creating default learning goals for internship period
        for ($i = 1; $i < 4; $i++) {
            $learningGoal = new LearningGoal;
            $learningGoal->learninggoal_label = sprintf(Lang::get('activity.learningquestion') . ' %s', $i);
            $learningGoal->description = sprintf(Lang::get('general.default.learninggoal_desc_placeholder'). ' %s', $i);
            $learningGoal->wplp_id = $wplPeriod->wplp_id;
            $learningGoal->save();
        }

        // Set the user setting to the current Internship ID
        if ($request['isActive'] == 1) {
            Auth::user()->setUserSetting('active_internship', $wplPeriod->wplp_id);
        }

        return redirect()->route('profile')->with('success', Lang::get('general.edit-saved'));
    }

    public function update(Request $request, $id)
    {
        // Validate the input
        $validator = Validator::make($request->all(), [
            'companyName'          => 'required|max:255|min:3',
            'companyStreet'        => 'required|max:45|min:3',
            'companyHousenr'       => 'required|max:4|min:1',
            //
            'companyPostalcode'    => 'required|postalcode',
            'companyLocation'      => 'required|max:255|min:3',
            'companyCountry'       => 'required|max:255|min:2',
            'contactPerson'        => 'required|max:255|min:3',
            'contactPhone'         => 'required',
            'contactEmail'         => 'required|email|max:255',
            'numdays'              => 'required|integer|min:1',
            'startdate'            => 'required|date|after:' . date("Y-m-d", strtotime('-6 months')),
            'enddate'              => 'required|date|after:startdate',
            'internshipAssignment' => 'required|min:15|max:500',
            'isActive'             => 'sometimes|required|in:1,0',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('period-acting-edit', ["id" => $id])
                ->withErrors($validator)
                ->withInput();
        }

        // Input is valid. Attempt to fetch the WPLP and validate it belongs to the user
        $wplPeriod = WorkplaceLearningPeriod::find($id);
        if (is_null($wplPeriod) || $wplPeriod->student_id != Auth::user()->student_id) {
            return redirect()->route('profile')
                ->with('error', Lang::get('errors.internship-no-permission'));
        }

        // Succes. Also fetch the associated Workplace Eloquent object and update
        $workplace = Workplace::find($wplPeriod->wp_id);


        // Todo use model->fill($request)
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

        // Todo use model->fill($request)
        $wplPeriod->student_id = Auth::user()->student_id;
        $wplPeriod->wp_id = $workplace->wp_id;
        $wplPeriod->startdate = $request['startdate'];
        $wplPeriod->enddate = $request['enddate'];
        $wplPeriod->nrofdays = $request['numdays'];
        $wplPeriod->description = $request['internshipAssignment'];
        $wplPeriod->save();

        // Set the user setting to the current Internship ID
        if ($request['isActive'] == 1) {
            Auth::user()->setUserSetting('active_internship', $wplPeriod->wplp_id);
        }

        return redirect()->route('profile')->with('success', Lang::get('general.edit-saved'));
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
            'learninggoal_name.*' => 'required|min:3|max:50',
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
                ->route('period-acting-edit', ["id" => $id])
                ->withErrors($validator)
                ->withInput();
        } else {
            if(isset($request['learninggoal_name'])) {
                foreach ($request['learninggoal_name'] as $lg_id => $name) {
                    $learningGoal = LearningGoal::find($lg_id);
                    $description = $request['learninggoal_description'][$lg_id];
                    if (is_null($learningGoal)) {
                        $learningGoal = new LearningGoal;
                        $learningGoal->wplp_id = $id;
                    }
                    $learningGoal->learninggoal_label = $name;
                    $learningGoal->description = $description;
                    $learningGoal->save();
                }
            }

            if (strlen($request['new_learninggoal_name']) > 0) {
                $learningGoal = new LearningGoal;
                $learningGoal->learninggoal_label = $request['new_learninggoal_name'];
                $learningGoal->description = $request['new_learninggoal_description'];
                $learningGoal->wplp_id = $id;
                $learningGoal->save();
            }
        }

        // Done, redirect back to profile page
        return redirect()->route('period-acting-edit', ["id" => $id])->with(
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
                ->route('period-acting-edit', ["id" => $id])
                ->withErrors($validator)
                ->withInput();
        } else {
            // All is well :)
            foreach ($request['cat'] as $cat) {
                // Either update or create a new row.
                $category = Category::find($cat['cg_id']);
                if (is_null($category)) {
                    $category = new Category;
                    $category->wplp_id = $cat['wplp_id'];
                }
                $category->category_label = $cat['cg_label'];
                $category->save();
            }

            // Done, redirect back to profile page
            return redirect()->route('period-acting-edit', ["id" => $id])->with(
                'succes',
                Lang::get('general.edit-saved')
            );
        }
    }

    public function __construct()
    {
        $this->middleware('auth');
    }
}
