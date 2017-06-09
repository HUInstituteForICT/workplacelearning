<?php
/**
 * This file (InternshipController.php) was created on 06/20/2016 at 01:11.
 * (C) Max Cassee
 * This project was commissioned by HU University of Applied Sciences.
 */

namespace app\Http\Controllers;

// Use the PHP native IntlDateFormatter (note: enable .dll in php.ini)

use App\Category;
use App\Workplace;
use App\WorkplaceLearningPeriod;
use App\LearningGoal;
use Illuminate\Support\Collection;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class ProducingWorkplaceLearningController extends Controller
{

    public function show()
    {
        return view("pages.producing.internship")
                ->with("period", new WorkplaceLearningPeriod)
                ->with("workplace", new Workplace);
    }

    public function edit($id)
    {
        $wplPeriod = WorkplaceLearningPeriod::find($id);
        if (is_null($wplPeriod) || $wplPeriod->student_id != Auth::user()->student_id) {
            return redirect()->route('profile')
                ->with('error', 'Deze stage bestaat niet, of je hebt geen toegang om deze in te zien');
        } else {
            return view('pages.producing.internship')
                ->with('period', $wplPeriod)
                ->with("workplace", Workplace::find($wplPeriod->wp_id))
                ->with("categories", $wplPeriod->categories()->get())
                ->with("resource", new Collection);
        }
    }

    public function create(Request $request)
    {
        // Validate the input
        $validator = Validator::make($request->all(), [
            'companyName'           => 'required|regex:/^[0-9a-zA-Z ()\-,.]*$/|max:255|min:3',
            'companyStreet'         => 'required|regex:/^[0-9a-zA-Z ()\-,.]*$/|max:45|min:3',
            'companyHousenr'        => 'required|regex:/^[0-9]{1,5}[ ]*[a-zA-Z]{0,4}$/|max:9|min:1', //
            'companyPostalcode'     => 'required|postalcode',
            'companyLocation'       => 'required|regex:/^[0-9a-zA-Z ()\-]*$/|max:255|min:3',
            'contactPerson'         => 'required|regex:/^[0-9a-zA-Z ()\-,.]*$/|max:255|min:3',
            'contactPhone'          => 'required|regex:/^[0-9]{2,3}-?[0-9]{7,8}$/',
            'contactEmail'          => 'required|email|max:255',
            'numdays'               => 'required|integer|min:1',
            'startdate'             => 'required|date|after:'.date("Y-m-d", strtotime('-6 months')),
            'enddate'               => 'required|date|after:startdate',
            'internshipAssignment'  => 'required|regex:/^[ 0-9a-zA-Z\-_,.?!*&%#()\/\\\\\'\s"]*\s*$/|min:15|max:500',
            'isActive'              => 'sometimes|required|in:1,0'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('period-producing-create')
                ->withErrors($validator)
                ->withInput();
        }

        // Pass. Create the internship and period.
        $wplPeriod = new WorkplaceLearningPeriod;
        $workplace = new Workplace;

        // Todo use mass assignment
        // Save the workplace first
        $workplace->wp_name        = $request['companyName'];
        $workplace->street         = $request['companyStreet'];
        $workplace->housenr        = $request['companyHousenr'];
        $workplace->postalcode     = $request['companyPostalcode'];
        $workplace->town           = $request['companyLocation'];
        $workplace->contact_name   = $request['contactPerson'];
        $workplace->contact_email  = $request['contactEmail'];
        $workplace->contact_phone  = $request['contactPhone'];
        $workplace->numberofemployees = 0;
        $workplace->save();

        // Todo use mass assignment
        $wplPeriod->student_id   = Auth::user()->student_id;
        $wplPeriod->wp_id        = $workplace->wp_id;
        $wplPeriod->startdate    = $request['startdate'];
        $wplPeriod->enddate      = $request['enddate'];
        $wplPeriod->nrofdays     = $request['numdays'];
        $wplPeriod->description  = $request['internshipAssignment'];
        $wplPeriod->save();

        // Set the user setting to the current Internship ID
        if ($request['isActive'] == 1) {
            Auth::user()->setUserSetting('active_internship', $wplPeriod->wplp_id);
        }

        return redirect()->route('profile')->with('success', 'De wijzigingen zijn opgeslagen.');
    }

    public function update(Request $request, $id)
    {
        // Validate the input
        $validator = Validator::make($request->all(), [
            'companyName'           => 'required|regex:/^[0-9a-zA-Z ()\-,.]*$/|max:255|min:3',
            'companyStreet'         => 'required|regex:/^[0-9a-zA-Z ()\-,.]*$/|max:45|min:3',
            'companyHousenr'        => 'required|regex:/^[0-9]{1,5}[a-zA-Z]{0,1}$/|max:4|min:1', //
            'companyPostalcode'     => 'required|postalcode',
            'companyLocation'       => 'required|regex:/^[0-9a-zA-Z ()\-]*$/|max:255|min:3',
            'contactPerson'         => 'required|regex:/^[0-9a-zA-Z ()\-,.]*$/|max:255|min:3',
            'contactPhone'          => 'required|regex:/^[0-9]{2,3}-?[0-9]{7,8}$/',
            'contactEmail'          => 'required|email|max:255',
            'numdays'               => 'required|integer|min:1',
            'startdate'             => 'required|date|after:'.date("Y-m-d", strtotime('-6 months')),
            'enddate'               => 'required|date|after:startdate',
            'internshipAssignment'  => 'required|regex:/^[ 0-9a-zA-Z\-_,.?!*&%#()\/\\\\\'\s"]*\s*$/|min:15|max:500',
            'isActive'              => 'sometimes|required|in:1,0'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('period-producing-edit', ["id" => $id])
                ->withErrors($validator)
                ->withInput();
        }

        // Input is valid. Attempt to fetch the WPLP and validate it belongs to the user
        $wplPeriod = WorkplaceLearningPeriod::find($id);
        if (is_null($wplPeriod) || $wplPeriod->student_id != Auth::user()->student_id) {
            return redirect()->route('profile')
                ->with('error', 'Deze stage bestaat niet, of je hebt geen toegang om deze in te zien');
        }

        // Succes. Also fetch the associated Workplace Eloquent object and update
        $workplace = Workplace::find($wplPeriod->wp_id);


        // Todo use model->fill()
        // Save the workplace first
        $workplace->wp_name        = $request['companyName'];
        $workplace->street         = $request['companyStreet'];
        $workplace->housenr        = $request['companyHousenr'];
        $workplace->postalcode     = $request['companyPostalcode'];
        $workplace->town           = $request['companyLocation'];
        $workplace->contact_name   = $request['contactPerson'];
        $workplace->contact_email  = $request['contactEmail'];
        $workplace->contact_phone  = $request['contactPhone'];
        $workplace->numberofemployees = 0;
        $workplace->save();

        // Todo use model->fill()
        $wplPeriod->student_id   = Auth::user()->student_id;
        $wplPeriod->wp_id        = $workplace->wp_id;
        $wplPeriod->startdate    = $request['startdate'];
        $wplPeriod->enddate      = $request['enddate'];
        $wplPeriod->nrofdays     = $request['numdays'];
        $wplPeriod->description  = $request['internshipAssignment'];
        $wplPeriod->save();

        // Set the user setting to the current Internship ID
        if ($request['isActive'] == 1) {
            Auth::user()->setUserSetting('active_internship', $wplPeriod->wplp_id);
        }

        return redirect()->route('profile')->with('success', 'De wijzigingen zijn opgeslagen.');
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
            return redirect()->route('profile')->withErrors("Je hebt geen rechten om deze actie uit te voeren voor dit profiel."); // $id is invalid or does not belong to the student
        }

        // Inject the new item into the request array for processing and validation if it is filled in by the user
        if (!empty($request['newcat']['0']['cg_label'])) {
            $request['cat'] = array_merge(((is_array($request['cat'])) ? $request['cat'] : []), $request['newcat']);
        }

        $validator = Validator::make($request->all(), [
            'cat.*.wplp_id'       => 'required|digits_between:1,5',
            'cat.*.cg_id'       => 'required|digits_between:1,5',
            'cat.*.cg_label'    => 'required|regex:/^[a-zA-Z0-9_() ]*$/|min:3|max:50',
        ]);
        if ($validator->fails()) {
            // Noes. errors occured. Exit back to profile page with errors
            return redirect()
                ->route('period-producing-edit', ["id" => $id])
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
            return redirect()->route('period-producing-edit', ["id" => $id])->with('succes', 'De wijzigingen in jouw categoriën zijn opgeslagen.');
        }
    }

    public function __construct()
    {
        $this->middleware('auth');
    }
}
