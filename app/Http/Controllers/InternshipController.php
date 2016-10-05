<?php
/**
 * This file (InternshipController.php) was created on 06/20/2016 at 01:11.
 * (C) Max Cassee
 * This project was commissioned by HU University of Applied Sciences.
 */

namespace app\Http\Controllers;

// Use the PHP native IntlDateFormatter (note: enable .dll in php.ini)
use App\Categorie;
use App\Internship;
use App\Samenwerkingsverband;
use App\InternshipPeriod;
use Validator;
use IntlDateFormatter;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InternshipController extends Controller{
    public function show(){
        return view('pages.profile');
    }
    
    public function editInternshipPeriod($id){
        if($id === "0"){
            $ip = new InternshipPeriod;
            $ip->student_id = Auth::user()->stud_id;
            $is = new Internship;
        } else {
            $ip = InternshipPeriod::find($id);
        }

        return (is_null($ip) || $ip->student_id != Auth::user()->stud_id) ?
            redirect('profiel')
                ->with('error', 'Deze stage bestaat niet, of je hebt geen toegang om deze in te zien')
            : view('pages.internship')->with('period', $ip);
    }
    
    public function updateInternshipPeriod(Request $request, $id){
        // Validate the input
        $validator = Validator::make($request->all(), [
            'companyName'           => 'required|regex:/^[0-9a-zA-Z ()-,.]*$/|max:255|min:3',
            'companyLocation'       => 'required|regex:/^[0-9a-zA-Z ()-]*$/|max:255|min:3',
            'contactPerson'         => 'required|regex:/^[0-9a-zA-Z ()-,.]*$/|max:255|min:3',
            'contactPhone'          => 'required|regex:/^[0-9]{2,3}-?[0-9]{7,8}$/',
            'contactEmail'          => 'required|email|max:255',
            'numhours'              => 'required|digits_between:1,5',
            'startdate'             => 'required|date|after:'.date("Y-m-d", strtotime('-6 months')),
            'enddate'               => 'required|date|after:'.date("Y-m-d", strtotime('now')),
            'internshipAssignment'  => 'required|regex:/^[0-9a-zA-Z ()-,.]*$/|min:15|max:500',
            'isActive'              => 'sometimes|required|in:1,0'
        ]);

        if ($validator->fails()) {
            return redirect('stageperiode/edit/'.$id)
                ->withErrors($validator)
                ->withInput();
        }

        // Pass. Create the internship and period.
        $ip = InternshipPeriod::find($id);
        $is  = null;
        if(is_null($ip)) {
            $ip     = new InternshipPeriod;
            $is     = new Internship;
        } else {
            $is = Internship::find($ip->stage_id);
            if(is_null($is)){
                $is = new Internship;
            }
        }

        $is->bedrijfsnaam   = $request['companyName'];
        $is->plaats         = $request['companyLocation'];
        $is->contactpersoon = $request['contactPerson'];
        $is->contactemail   = $request['contactEmail'];
        $is->telefoon       = $request['contactPhone'];
        $is->save();

        $ip->student_id             = Auth::user()->stud_id;
        $ip->stageplaats_id         = $is->stp_id;
        $ip->startdatum             = $request['startdate'];
        $ip->einddatum              = $request['enddate'];
        $ip->aantaluren             = $request['numhours'];
        $ip->opdrachtomschrijving   = $request['internshipAssignment'];
        $ip->save();

        // Set the user setting to the current Internship ID
        if($request['isActive'] == 1){
            Auth::user()->setUserSetting('active_internship', $ip->stud_stid);
        }
        
        return redirect('stageperiode/edit/'.$ip->stud_stid)->with('success', 'De wijzigingen zijn opgeslagen.');
    }

    public function updateCategories(Request $request, $id){
        // Verify the given ID is valid and belongs to the student
        $t = false;
        foreach(Auth::user()->internshipperiods()->get() as $ip){
            if($ip->stud_stid == $id){
                $t = true;
                break;
            }
        }
        if(!$t) return redirect('profiel'); // $id is invalid or does not belong to the student

        // Inject the new item into the request array for processing and validation if it is filled in by the user
        if(!empty($request['newcat']['-1']['cg_value'])){
           $request['cat'] = array_merge($request['cat'], $request['newcat']);
        }

        $validator = Validator::make($request->all(), [
            'cat.*.cg_id'       => 'required|digits_between:1,5',
            'cat.*.ss_id'       => 'required|digits_between:1,5',
            'cat.*.cg_value'    => 'required|regex:/^[a-zA-Z0-9_() ]*$/|min:3|max:50',
        ]);
        if($validator->fails()){
            // Noes. errors occured. Exit back to profile page with errors
            return redirect('profiel')
                ->withErrors($validator)
                ->withInput();
        } else {
            // All is well :)
            foreach($request['cat'] as $cat){
                // Either update or create a new row.
                $c = Categorie::find($cat['cg_id']);
                if(is_null($c)){
                    $c = new Categorie;
                    $c->ss_id = $cat['ss_id'];
                }
                $c->cg_value = $cat['cg_value'];
                $c->save();
            }
            // Done, redirect back to profile page
            return redirect('stageperiode/edit/'.$id);
        }
    }
    
    public function updateCooperations(Request $request, $id){
        // Verify the given ID is valid and belongs to the student
        $t = false;
        foreach(Auth::user()->internshipperiods()->get() as $ip){
            if($ip->stud_stid == $id){
                $t = true;
                break;
            }
        }
        if(!$t) return redirect('profiel'); // $id is invalid or does not belong to the student

        // Inject the new item into the request array for processing and validation if it is filled in by the user
        if(!empty($request['newswv']['-1']['value']) && !empty($request['newswv']['-1']['omschrijving'])){
            $request['swv'] = array_merge($request['swv'], $request['newswv']);
        }

        $validator = Validator::make($request->all(), [
            'swv.*.swv_id'          => 'required|digits_between:1,5',
            'swv.*.ss_id'           => 'required|digits_between:1,5',
            'swv.*.value'           => 'required|regex:/^[a-zA-Z0-9_() ]*$/|min:3|max:50',
            'swv.*.omschrijving'    => 'required|regex:/^[a-zA-Z0-9_() ]*$/|min:3|max:50',
        ]);
        if($validator->fails()){
            // Noes. errors occured. Exit back to profile page with errors
            return redirect('profiel')
                ->withErrors($validator)
                ->withInput();
        } else {
            // All is well :)
            foreach($request['swv'] as $swv){
                // Either update or create a new row.
                $s = Samenwerkingsverband::find($swv['swv_id']);
                if(is_null($s)){
                    $s                  = new Categorie;
                    $s->ss_id           = $swv['ss_id'];
                }
                $s->swv_value           = $swv['value'];
                $s->swv_omschrijving    = $swv['omschrijving'];
                $s->save();
            }
            // Done, redirect back to profile page
            return redirect('profiel');
        }
    }
    public function __construct(){
        $this->middleware('auth');
    }
}