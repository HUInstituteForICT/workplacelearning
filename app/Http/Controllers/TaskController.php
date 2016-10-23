<?php
/**
 * This file (TaskController.php) was created on 06/27/2016 at 16:10.
 * (C) Max Cassee
 * This project was commissioned by HU University of Applied Sciences.
 */

namespace App\Http\Controllers;

use App\Categorie;
use App\Feedback;
use App\Samenwerkingsverband;
use App\Werkzaamheid;
use App\Http\Requests;
use phpDocumentor\Reflection\Types\This;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller{

    public function show(){
        return view('pages.tasks');
    }

    public function feedback($id){
        $fb  = Feedback::find($id);
        if($fb != null) {
            $wzh = Werkzaamheid::find($fb->wzh_id);
        }
        return view('pages.feedback')
            ->with('wzh', $wzh)
            ->with('fb', $fb);
    }

    public function progress($pagenr){
        return view('pages.progress')->with('page', $pagenr);
    }

    public function updateFeedback(Request $r, $id){
        $fb  = Feedback::find($id); $wzh = null;
        if($fb != null) {
            $wzh = Werkzaamheid::find($fb->wzh_id);
        }
        //if(strlen($fb->notfinished) > 0) return redirect('feedback/'.$id)->withErrors(['Je kan deze feedback niet meer aanpassen.']);

        $v = Validator::make($r->all(), [
            'notfinished'               => 'required|regex:/^[0-9a-zA-Z()-_,. ]+$/',
            'newnotfinished'            => 'required_if:notfinished,Anders|max:150|regex:/^[0-9a-zA-Z()-_,. ]+$/',
            'help_asked'                => 'required|in:0,1,2',
            'help_werkplek'             => 'required_unless:help_asked,0|max:150|regex:/^[0-9a-zA-Z()-_,. ]+$/',
            'initiatief'                => 'required|max:500|regex:/^[0-9a-zA-Z()-_,. ]+$/',
            'progress_satisfied'        => 'required|in:1,2',
            'vervolgstap_zelf'          => 'required|max:150|regex:/^[0-9a-zA-Z()-_,. ]+$/',
            'ondersteuning_werkplek'    => 'required_unless:ondersteuningWerkplek,Geen|max:150|regex:/^[0-9a-zA-Z()-_,. ]+$/',
            'ondersteuning_opleiding'   => 'required_unless:ondersteuningOpleiding,Geen|max:150|regex:/^[0-9a-zA-Z()-_,. ]+$/',
        ]);
        if($v->fails()){
            return redirect('feedback/'.$id)
                ->withErrors($v)
                ->withInput();
        } else {
            $fb->notfinished                = ($r['notfinished'] == "Anders") ? $r['newnotfinished'] : $r['notfinished'];
            $fb->help_asked                 = $r['help_asked'];
            $fb->help_werkplek              = ($r['help_asked'] == 0) ? "Geen" : $r['help_werkplek'];
            $fb->progress_satisfied         = $r['progress_satisfied'];
            $fb->initiatief                 = $r['initiatief'];
            $fb->vervolgstap_zelf           = $r['vervolgstap_zelf'];
            $fb->ondersteuning_werkplek     = (!isset($r['ondersteuningWerkplek'])) ? $r['ondersteuning_werkplek'] : "Geen";
            $fb->ondersteuning_opleiding    = (!isset($r['ondersteuningOpleiding'])) ? $r['ondersteuning_opleiding'] : "Geen";
            $fb->save();
            return redirect('leerproces')->with('success', 'De feedback is opgeslagen.');
        }
    }
    
    public function create(Request $r){
        // Allow only to view this page if an internship exists.
        if(Auth::user()->getCurrentInternshipPeriod() == null)
            return redirect('profiel')->withErrors(['Je kan geen activiteiten registreren zonder (actieve) stage.']);

        $v = Validator::make($r->all(), [
            'datum'         => 'required|date|before:'.date('Y-m-d', strtotime('tomorrow')),
            'omschrijving'  => 'required|regex:/^[ 0-9a-zA-Z-_,.?!*&%#()\'"]+$/',
            'aantaluren'    => 'required|regex:/^[0-9]{1}[.]?[0-9]{0,2}$/',
            'lerenmet'      => 'required|in:persoon,alleen,internet,boek,new',
            'moeilijkheid'  => 'required|exists:moeilijkheden,mh_id',
            'status'        => 'required|exists:statussen,st_id',
        ]);

        // Conditional Validators
        $v->sometimes('previous_wzh', 'required|exists:werkzaamheden,wzh_id', function($input){
            return $input->previous_wzh != "-1";
        });
        $v->sometimes('newcat', 'sometimes|regex:/^[0-9a-zA-Z ()]{1,50}$/', function($input){
            return $input->cat_id == "new";
        });
        $v->sometimes('cat_id', 'required|exists:categorieen,cg_id', function($input){
            return $input->cat_id != "new";
        });
        $v->sometimes('newswv', 'required|regex:/^[0-9a-zA-Z ()]{1,50}$/', function($input) {
            return ($input->swv_id == "new" && $input->lerenmet == "persoon");
        });
        $v->sometimes('swv_id', 'required|exists:samenwerkingsverbanden,swv_id', function($input){
            return ($input->swv_id != "new" && $input->lerenmet == "persoon");
        });
        $v->sometimes('internetsource', 'required|url', function($input){
            return $input->lerenmet == "internet";
        });
        $v->sometimes('booksource', 'required|regex:/^[0-9a-zA-Z ,.-_!@%()]{1,250}$/', function($input){
            return $input->lerenmet == "boek";
        });
        $v->sometimes('newlerenmet', 'required|regex:/^[0-9a-zA-Z ,.-_()]{1,250}$/', function($input){
            return $input->lerenmet == "new";
        });

        // Validate the input
        if ($v->fails()) {
            return redirect('leerproces')
                ->withErrors($v)
                ->withInput();
        } else {
            // All ok.
            if($r['lerenmet'] == "new"){
                $r['lerenmet'] = "Anders";
            }
            if($r['cat_id'] == "new"){
                $c = new Categorie;
                $c->cg_value    = $r['newcat'];
                $c->ss_id       = Auth::user()->getCurrentInternshipPeriod()->stud_stid;
                $c->save();
            }
            if($r['swv_id'] == "new"){
                $swv = new Samenwerkingsverband;
                $swv->swv_value = $r['newswv'];
                $swv->ss_id     = Auth::user()->getCurrentInternshipPeriod()->stud_stid;
                $swv->save();
            }
            $w = new Werkzaamheid;
            $w->student_stage_id        = Auth::user()->getCurrentInternshipPeriod()->stud_stid;
            $w->wzh_datum               = $r['datum'];
            $w->wzh_omschrijving        = $r['omschrijving'];
            $w->wzh_aantaluren          = $r['aantaluren'];
            $w->lerenmet                = $r['lerenmet'];
            switch($r['lerenmet']){
                case "persoon":     $w->lerenmetdetail = ($r['swv_id'] == "new") ? $swv->swv_id : $r['swv_id'];
                break;
                case "internet":    $w->lerenmetdetail = $r['internetsource'];
                break;
                case "boek":        $w->lerenmetdetail = $r['booksource'];
                break;
                case "Anders":      $w->lerenmetdetail = $r['newlerenmet'];
                break;
                default:            $w->lerenmetdetail = "";
            }

            $w->categorie_id            = ($r['cat_id'] == "new") ? $c->cg_id : $r['cat_id'];
            $w->moeilijkheid_id         = $r['moeilijkheid'];
            $w->status_id               = $r['status'];
            $w->prev_wzh_id             = ($r['previous_wzh'] != "-1") ? $r['previous_wzh'] : NULL;
            $w->display                 = ($r['status'] == 2) ? 1 : 0; // Only set this WZH to display if it is unfinished
            $w->created_at              = date_format(date_create(null, timezone_open("Europe/Amsterdam")), 'Y-m-d H:i:s');
            $w->session_id              = $r->session()->getId();
            $w->save();
            // Update the previous WZH
            if($r['previous_wzh'] > 1){
                $prev_wzh = Werkzaamheid::find($w->prev_wzh_id);
                $prev_wzh->display = 0;
                $prev_wzh->save();
            }
            
            if(
                ($w->moeilijkheid_id == 2 || $w->moeilijkheid_id == 3)
                && ($w->status_id == 2)
            ){
                // Create Feedback object and redirect
                $fb = new Feedback;
                $fb->wzh_id = $w->wzh_id;
                $fb->save();
                return redirect('feedback/'.$fb->fb_id)->with('success', 'De leeractiviteit is opgeslagen.');
            }
            return redirect('leerproces')->with('success', 'De leeractiviteit is opgeslagen.');
        }
    }

    public function edit(Request $r){
        // Allow only to view this page if an internship exists.
        if(Auth::user()->getCurrentInternship() == null)
            return redirect('profiel');
        return view('pages.tasks');
    }

    public function update(Request $r){
        // Allow only to view this page if an internship exists.
        if(Auth::user()->getCurrentInternship() == null)
            return redirect('profiel');
        return view('pages.tasks');
    }
    public function __construct(){
        $this->middleware('auth');
    }
}