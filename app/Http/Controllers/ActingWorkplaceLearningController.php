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

class ActingWorkplaceLearningController extends Controller{

    public function show(){
        return view("pages.internship")
                ->with("period", new WorkplaceLearningPeriod)
                ->with("workplace", new Workplace);
    }

    public function edit($id){
        $wplp = WorkplaceLearningPeriod::find($id);
        if (is_null($wplp) || $wplp->student_id != Auth::user()->student_id) {
            return redirect()->route('profile')
                ->with('error', 'Deze stage bestaat niet, of je hebt geen toegang om deze in te zien');
        } else {
            return view('pages.internship')
                ->with('period', $wplp)
                ->with("workplace", Workplace::find($wplp->wp_id))
                ->with("categories", Auth::user()->getCurrentWorkplaceLearningPeriod()->categories()->get())
                ->with("resource", new Collection);
        }
    }

    public function create(Request $r){
        // Validate the input
        $validator = Validator::make($r->all(), [
            'companyName'           => 'required|regex:/^[0-9a-zA-Z ()\-,.]*$/|max:255|min:3',
            'companyStreet'         => 'required|regex:/^[0-9a-zA-Z ()\-,.]*$/|max:45|min:3',
            'companyHousenr'        => 'required|regex:/^[0-9]{1,5}[ ]*[a-zA-Z]{0,4}$/|max:9|min:1', //
            'companyPostalcode'     => 'required|regex:/^[0-9a-zA-Z]*$/|max:10|min:3', //TODO: Fix Regex to proper intl format
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
                ->route('workplacelearningperiod')
                ->withErrors($validator)
                ->withInput();
        }

        // Pass. Create the internship and period.
        $wplp = new WorkplaceLearningPeriod;
        $wp = new Workplace;

        // Save the workplace first
        $wp->wp_name        = $r['companyName'];
        $wp->street         = $r['companyStreet'];
        $wp->housenr        = $r['companyHousenr'];
        $wp->postalcode     = $r['companyPostalcode'];
        $wp->town           = $r['companyLocation'];
        $wp->contact_name   = $r['contactPerson'];
        $wp->contact_email  = $r['contactEmail'];
        $wp->contact_phone  = $r['contactPhone'];
        $wp->numberofemployees = 0;
        $wp->save();

        $wplp->student_id   = Auth::user()->student_id;
        $wplp->wp_id        = $wp->wp_id;
        $wplp->startdate    = $r['startdate'];
        $wplp->enddate      = $r['enddate'];
        $wplp->nrofdays     = $r['numdays'];
        $wplp->description  = $r['internshipAssignment'];
        $wplp->save();

        // Create unplanned learning as default learning goal
        $l = new LearningGoal;
        $l->learninggoal_label = "Ongepland leermoment";
        $l->wplp_id = $wplp->wplp_id;
        $l->save();

        // Creating default learning goals for internship period
        for ($i = 1; $i < 4; $i ++) {
            $l = new LearningGoal;
            $l->learninggoal_label = sprintf('Leervraag %s', $i);
            $l->wplp_id = $wplp->wplp_id;
            $l->save();
        }

        // Set the user setting to the current Internship ID
        if($r['isActive'] == 1){
            Auth::user()->setUserSetting('active_internship', $wplp->wplp_id);
        }

        return redirect()->route('profile')->with('success', 'De wijzigingen zijn opgeslagen.');
    }

    public function update(Request $r, $id){
        // Validate the input
        $validator = Validator::make($r->all(), [
            'companyName'           => 'required|regex:/^[0-9a-zA-Z ()\-,.]*$/|max:255|min:3',
            'companyStreet'         => 'required|regex:/^[0-9a-zA-Z ()\-,.]*$/|max:45|min:3',
            'companyHousenr'        => 'required|regex:/^[0-9]{1,5}[a-zA-Z]{0,1}$/|max:4|min:1', //
            'companyPostalcode'     => 'required|regex:/^[0-9a-zA-Z]*$/|max:10|min:6', //TODO: Fix Regex to proper intl format
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
                ->route('workplacelearningperiod-edit', ["id" => $id])
                ->withErrors($validator)
                ->withInput();
        }

        // Input is valid. Attempt to fetch the WPLP and validate it belongs to the user
        $wplp = WorkplaceLearningPeriod::find($id);
        if(is_null($wplp) || $wplp->student_id != Auth::user()->student_id){
            return redirect()->route('profile')
                ->with('error', 'Deze stage bestaat niet, of je hebt geen toegang om deze in te zien');
        }

        // Succes. Also fetch the associated Workplace Eloquent object and update
        $wp = Workplace::find($wplp->wp_id);

        // Save the workplace first
        $wp->wp_name        = $r['companyName'];
        $wp->street         = $r['companyStreet'];
        $wp->housenr        = $r['companyHousenr'];
        $wp->postalcode     = $r['companyPostalcode'];
        $wp->town           = $r['companyLocation'];
        $wp->contact_name   = $r['contactPerson'];
        $wp->contact_email  = $r['contactEmail'];
        $wp->contact_phone  = $r['contactPhone'];
        $wp->numberofemployees = 0;
        $wp->save();

        $wplp->student_id   = Auth::user()->student_id;
        $wplp->wp_id        = $wp->wp_id;
        $wplp->startdate    = $r['startdate'];
        $wplp->enddate      = $r['enddate'];
        $wplp->nrofdays     = $r['numdays'];
        $wplp->description  = $r['internshipAssignment'];
        $wplp->save();

        // Set the user setting to the current Internship ID
        if($r['isActive'] == 1){
            Auth::user()->setUserSetting('active_internship', $wplp->wplp_id);
        }

        return redirect()->route('profile')->with('success', 'De wijzigingen zijn opgeslagen.');
    }

    public function updateCategories(Request $request, $id){
        // Verify the given ID is valid and belongs to the student
        $t = false;
        foreach(Auth::user()->workplacelearningperiods()->get() as $ip){
            if($ip->wplp_id == $id){
                $t = true;
                break;
            }
        }
        if(!$t) return redirect()->route('profile'); // $id is invalid or does not belong to the student

        // Inject the new item into the request array for processing and validation if it is filled in by the user
        if(!empty($request['newcat']['0']['cg_label'])){
           $request['cat'] = array_merge(((is_array($request['cat'])) ? $request['cat'] : array()), $request['newcat']);
        }

        $validator = Validator::make($request->all(), [
            'cat.*.wplp_id'       => 'required|digits_between:1,5',
            'cat.*.cg_id'       => 'required|digits_between:1,5',
            'cat.*.cg_label'    => 'required|regex:/^[a-zA-Z0-9_() ]*$/|min:3|max:50',
        ]);
        if($validator->fails()){
            // Noes. errors occured. Exit back to profile page with errors
            return redirect()
                ->route('workplacelearningperiod-edit', ["id" => $id])
                ->withErrors($validator)
                ->withInput();
        } else {
            // All is well :)
            foreach($request['cat'] as $cat){
                // Either update or create a new row.
                $c = Category::find($cat['cg_id']);
                if(is_null($c)){
                    $c = new Category;
                    $c->wplp_id = $cat['wplp_id'];
                }
                $c->category_label = $cat['cg_label'];
                $c->save();
            }
            // Done, redirect back to profile page
            return redirect()->route('workplacelearningperiod-edit', ["id" => $id])->with('succes', 'De wijzigingen in jouw categoriën zijn opgeslagen.');
        }
    }

    public function __construct(){
        $this->middleware('auth');
    }
}
