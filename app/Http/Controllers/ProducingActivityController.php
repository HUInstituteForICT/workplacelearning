<?php
/**
 * This file (ProducingActivityController.php) was created on 06/27/2016 at 16:10.
 * (C) Max Cassee
 * This project was commissioned by HU University of Applied Sciences.
 */

namespace App\Http\Controllers;

use App\Category;
use App\Feedback;
use App\ResourcePerson;
use App\LearningActivityProducing;
use App\Http\Requests;
use phpDocumentor\Reflection\Types\This;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProducingActivityController extends Controller{

    public function show(){
        return view('pages.producingactivity');
    }

    public function feedback($id){
        $fb  = Feedback::find($id);
        if($fb != null) {
            $wzh = LearningActivityProducing::find($fb->wzh_id);
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
            $wzh = LearningActivityProducing::find($fb->wzh_id);
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
        if(Auth::user()->getCurrentWorkplaceLearningPeriod() == null)
            return redirect('profiel')->withErrors(['Je kan geen activiteiten registreren zonder (actieve) stage.']);

        $v = Validator::make($r->all(), [
            'datum'         => 'required|date|before:'.date('Y-m-d', strtotime('tomorrow')),
            'omschrijving'  => 'required|regex:/^[ 0-9a-zA-Z-_,.?!*&%#()\'"]+$/',
            'aantaluren'    => 'required|regex:/^[0-9]{1}[.]?[0-9]{0,2}$/',
            'resource'      => 'required|in:persoon,alleen,internet,boek,new',
            'moeilijkheid'  => 'required|exists:difficulty,difficulty_id',
            'status'        => 'required|exists:status,status_id',
        ]);

        // Conditional Validators
        $v->sometimes('previous_wzh', 'required|exists:learningactivityproducing,lap_id', function($input){
            return $input->previous_wzh != "-1";
        });
        $v->sometimes('newcat', 'sometimes|regex:/^[0-9a-zA-Z ()]{1,50}$/', function($input){
            return $input->category_id == "new";
        });
        $v->sometimes('category_id', 'required|exists:category,category_id', function($input){
            return $input->category_id != "new";
        });
        $v->sometimes('newswv', 'required|regex:/^[0-9a-zA-Z ()]{1,50}$/', function($input) {
            return ($input->personsource == "new" && $input->resource == "persoon");
        });
        $v->sometimes('personsource', 'required|exists:resourceperson,rp_id', function($input){
            return ($input->personsource != "new" && $input->resource == "persoon");
        });
        $v->sometimes('internetsource', 'required|url', function($input){
            return $input->resource == "internet";
        });
        $v->sometimes('booksource', 'required|regex:/^[0-9a-zA-Z ,.-_!@%()]{1,250}$/', function($input){
            return $input->resource == "book";
        });
        $v->sometimes('newlerenmet', 'required|regex:/^[0-9a-zA-Z ,.-_()]{1,250}$/', function($input){
            return $input->resource == "new";
        });

        // Validate the input
        if ($v->fails()) {
            return redirect('leerproces')
                ->withErrors($v)
                ->withInput();
        } else {
            // All ok.
            if($r['resource'] == "new"){
                $r['resource'] = "other";
            }
            if($r['category_id'] == "new"){
                $c                  = new Categorie;
                $c->category_label  = $r['newcat'];
                $c->ss_id           = Auth::user()->getCurrentWorkplaceLearningPeriod()->student_id;
                $c->save();
            }
            if($r['personsource'] == "new"){
                $p                = new ResourcePerson;
                $p->person_label  = $r['newswv'];
                $p->wplp_id       = Auth::user()->getCurrentWorkplaceLearningPeriod()->wplp_id;
                $p->ep_id         = Auth::user()->getEducationProgram()->ep_id;
                $p->save();
            }

            $w = new LearningActivityProducing;
            $w->wplp_id            = Auth::user()->getCurrentWorkplaceLearningPeriod()->wplp_id;
            $w->date               = $r['datum'];
            $w->description        = $r['omschrijving'];
            $w->duration           = $r['aantaluren'];

            switch($r['resource']) {
                case 'persoon':
                    $w->res_person_id = $r['personsource'] == 'new' ? $p->rp_id : $r['personsource'];
                    break;
                case 'internet':
                    $w->res_material_id = 1;
                    $w->res_material_detail = $r['internetsource'];
                    break;
                case 'boek':
                    $w->res_material_id = 2;
                    $w->res_material_detail = $r['booksource'];
                    break;
            }

            // $w->rest_material_id   = $r['lerenmet'];
            // switch($r['lerenmet']){
            //     case "persoon":     $w->lerenmetdetail = ($r['rp_id'] == "new") ? $swv->rp_id : $r['rp_id'];
            //     break;
            //     case "internet":    $w->lerenmetdetail = $r['internetsource'];
            //     break;
            //     case "boek":        $w->lerenmetdetail = $r['booksource'];
            //     break;
            //     case "Anders":      $w->lerenmetdetail = $r['newlerenmet'];
            //     break;
            //     default:            $w->lerenmetdetail = "";
            // }

            $w->category_id             = ($r['category_id'] == "new") ? $c->cg_id : $r['category_id'];
            $w->difficulty_id           = $r['moeilijkheid'];
            $w->status_id               = $r['status'];
            $w->prev_lap_id             = ($r['previous_wzh'] != "-1") ? $r['previous_wzh'] : NULL;
            // $w->display                 = ($r['status'] == 2) ? 1 : 0; // Only set this WZH to display if it is unfinished
            $w->date              = date_format(date_create(null, timezone_open("Europe/Amsterdam")), 'Y-m-d H:i:s');
            //$w->session_id              = $r->session()->getId();
            $w->save();
            // // Update the previous WZH
            // if($r['previous_wzh'] > 1){
            //     $prev_lap_id = LearningActivityProducing::find($w->prev_lap_id);
            //     //$prev_wzh->display = 0;
            //     $prev_lap_id->save();
            // }

            if(
                ($w->difficulty_id == 2 || $w->difficulty_id == 3)
                && ($w->status_id == 2)
            ){
                // Create Feedback object and redirect
                $fb = new Feedback;
                $fb->learningactivity_id = $w->lap_id;
                $fb->save();
                return redirect('feedback/'.$fb->fb_id)->with('success', 'De leeractiviteit is opgeslagen.');
            }
            return redirect('leerproces')->with('success', 'De leeractiviteit is opgeslagen.');
        }
    }

    public function edit(Request $r){
        // Allow only to view this page if an internship exists.
        if(Auth::user()->getCurrentWorkplace() == null)
            return redirect('profiel');
        return view('pages.producingactivity');
    }

    public function update(Request $r){
        // Allow only to view this page if an internship exists.
        if(Auth::user()->getCurrentWorkplace() == null)
            return redirect('profiel');
        return view('pages.producingactivity');
    }
    public function __construct(){
        $this->middleware('auth');
    }
}
