<?php
/**
 * This file (ProducingActivityController.php) was created on 06/27/2016 at 16:10.
 * (C) Max Cassee
 * This project was commissioned by HU University of Applied Sciences.
 */

namespace App\Http\Controllers;

use App\Category;
use App\Difficulty;
use App\Feedback;
use App\ResourcePerson;
use App\LearningActivityProducing;
use App\Http\Requests;
use App\Status;
use phpDocumentor\Reflection\Types\This;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProducingActivityController extends Controller {

    public function show(){
        // Allow only to view this page if an internship exists.
        if(Auth::user()->getCurrentWorkplaceLearningPeriod() == null)
            return redirect()->route('profile')->withErrors(['Je kan geen activiteiten registreren zonder (actieve) stage.']);

        $resourcePersons = Auth::user()->getEducationProgram()->getResourcePersons()->union(
                Auth::user()->getCurrentWorkplaceLearningPeriod()->getResourcePersons()
        );

        return view('pages.producing.activity')
            ->with('learningWith', $resourcePersons)
            ->with('difficulties', Difficulty::all())
            ->with('statuses', Status::all());
    }

    public function edit($id){
        // Allow only to view this page if an internship exists.
        if(Auth::user()->getCurrentWorkplaceLearningPeriod() == null)
            return redirect()->route('profile')->withErrors(['Je kan geen activiteiten registreren zonder (actieve) stage.']);

        $activity = Auth::user()->getCurrentWorkplaceLearningPeriod()->getLearningActivityProducingById($id);

        if (!$activity) {
            return redirect()->route('process-acting')
                ->withErrors('Helaas, er is geen activiteit gevonden.');
        }

        $resourcePersons = Auth::user()->getEducationProgram()->getResourcePersons()->union(
                Auth::user()->getCurrentWorkplaceLearningPeriod()->getResourcePersons()
        );

        return view('pages.producing.activity-edit')
            ->with('activity', $activity)
            ->with('learningWith', $resourcePersons)
            ->with('categories', Auth::user()->getCurrentWorkplaceLearningPeriod()->getCategories());
    }

    public function feedback($id){
        $fb  = Feedback::find($id);
        if($fb != null) {
            $lap = LearningActivityProducing::find($fb->learningactivity_id);
        }
        return view('pages.producing.feedback')
            ->with('lap', $lap)
            ->with('fb', $fb);
    }

    public function progress($pagenr){
        return view('pages.producing.progress')->with('page', $pagenr);
    }

    public function updateFeedback(Request $r, $id){
        $fb  = Feedback::find($id); $wzh = null;
        if($fb != null) {
            $lap = LearningActivityProducing::find($fb->wzh_id);
            if(is_null($lap) || $lap->wplp_id != Auth::user()->getCurrentWorkplaceLearningPeriod()->wplp_id){
                return redirect()->route('home')->withErrors(['Je hebt geen rechten om deze feedback te versturen']);
            }
        }

        $v = Validator::make($r->all(), [
            'notfinished'               => 'required|regex:/^[0-9a-zA-Z()-_,. ]+$/',
            'newnotfinished'            => 'required_if:notfinished,Anders|max:150|regex:/^[0-9a-zA-Z()\-_,. ]+$/',
            'support_requested'         => 'required|in:0,1,2',
            'supported_provided_wp'    => 'required_unless:support_requested,0|max:150|regex:/^[0-9a-zA-Z()\-_,. ]+$/',
            'initiatief'                => 'required|max:500|regex:/^[0-9a-zA-Z()-_,. ]+$/',
            'progress_satisfied'        => 'required|in:1,2',
            'vervolgstap_zelf'          => 'required|max:150|regex:/^[0-9a-zA-Z()-_,. ]+$/',
            'ondersteuning_werkplek'    => 'required_unless:ondersteuningWerkplek,Geen|max:150|regex:/^[0-9a-zA-Z()\-_,. ]+$/',
            'ondersteuning_opleiding'   => 'required_unless:ondersteuningOpleiding,Geen|max:150|regex:/^[0-9a-zA-Z()\-_,. ]+$/',
        ]);
        if($v->fails()){
            return redirect()->route('feedback-producing', ["id" => $id])
                ->withErrors($v)
                ->withInput();
        } else {
            $fb->notfinished                = ($r['notfinished'] == "Anders") ? $r['newnotfinished'] : $r['notfinished'];
            $fb->initiative                 = $r['initiatief'];
            $fb->progress_satisfied         = $r['progress_satisfied'];
            $fb->support_requested          = $r['support_requested'];
            $fb->supported_provided_wp      = $r['supported_provided_wp'];
            $fb->nextstep_self              = $r['vervolgstap_zelf'];
            $fb->support_needed_wp          = (!isset($r['ondersteuningWerkplek'])) ? $r['ondersteuning_werkplek'] : "Geen";
            $fb->support_needed_ed          = (!isset($r['ondersteuningOpleiding'])) ? $r['ondersteuning_opleiding'] : "Geen";
            $fb->save();
            return redirect()->route('feedback-producing', ['id' => $id])->with('success', 'De feedback is opgeslagen.');
        }
    }

    public function create(Request $r){
        // Allow only to view this page if an internship exists.
        if(Auth::user()->getCurrentWorkplaceLearningPeriod() == null)
            return redirect()->route('profile')->withErrors(['Je kan geen activiteiten registreren zonder (actieve) stage.']);

        $v = Validator::make($r->all(), [
            'datum'         => 'required|date|before:'.date('Y-m-d', strtotime('tomorrow')),
            'omschrijving'  => 'required|regex:/^[ 0-9a-zA-Z\-_,.?!*&%#()\'\\\\\/"\s]+\s*$/',
            'aantaluren'    => 'required|regex:/^[0-9]{1}[.]?[0-9]{0,2}$/',
            'resource'      => 'required|in:persoon,alleen,internet,boek,new',
            'moeilijkheid'  => 'required|exists:difficulty,difficulty_id',
            'status'        => 'required|exists:status,status_id',
        ]);

        // Conditional Validators
        $v->sometimes('previous_wzh', 'required|exists:learningactivityproducing,lap_id', function($input){
            return $input->previous_wzh != "-1";
        });
        $v->sometimes('newcat', 'sometimes|regex:/^[0-9a-zA-Z ()\\\\\/]{1,50}$/', function($input){
            return $input->category_id == "new";
        });
        $v->sometimes('category_id', 'required|exists:category,category_id', function($input){
            return $input->category_id != "new";
        });
        $v->sometimes('newswv', 'required|regex:/^[0-9a-zA-Z ()\\\\\/]{1,50}$/', function($input) {
            return ($input->personsource == "new" && $input->resource == "persoon");
        });
        $v->sometimes('personsource', 'required|exists:resourceperson,rp_id', function($input){
            return ($input->personsource != "new" && $input->resource == "persoon");
        });
        //$v->sometimes('internetsource', 'required|url', function($input){ temporarily loosened up validation
        $v->sometimes('internetsource', 'required|regex:/^[0-9a-zA-Z ,.\-_!@%()\\\\\/]{1,250}$/', function($input){
            return $input->resource == "internet";
        });
        $v->sometimes('booksource', 'required|regex:/^[0-9a-zA-Z ,.\-_!@%()\\\\\/]{1,250}$/', function($input){
            return $input->resource == "book";
        });
        $v->sometimes('newlerenmet', 'required|regex:/^[0-9a-zA-Z ,.\-_()\\\\\/]{1,250}$/', function($input){
            return $input->resource == "new";
        });

        // Validate the input
        if ($v->fails()) {
            return redirect()->route('process-producing')
                ->withErrors($v)
                ->withInput();
        } else {
            // All ok.
            if($r['resource'] == "new"){
                $r['resource'] = "other";
            }
            if($r['category_id'] == "new"){
                $c                  = new Category;
                $c->category_label  = $r['newcat'];
                $c->wplp_id         = Auth::user()->getCurrentWorkplaceLearningPeriod()->wplp_id;
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

            $w->category_id             = ($r['category_id'] == "new") ? $c->category_id : $r['category_id'];
            $w->difficulty_id           = $r['moeilijkheid'];
            $w->status_id               = $r['status'];
            $w->prev_lap_id             = ($r['previous_wzh'] != "-1") ? $r['previous_wzh'] : NULL;
            $w->date                    = date_format(date_create($r->datum, timezone_open("Europe/Amsterdam")), 'Y-m-d H:i:s');
            $w->save();

            if(
                ($w->difficulty_id == 2 || $w->difficulty_id == 3)
                && ($w->status_id == 2)
            ){
                // Create Feedback object and redirect
                $fb = new Feedback;
                $fb->learningactivity_id = $w->lap_id;
                $fb->save();
                return redirect()->route('feedback-producing', ['id' => $fb->fb_id])->with('notification', 'Je vond deze activiteit moeilijk. Kan je aangeven wat je lastig vond?');
            }
            return redirect()->route('process-producing')->with('success', 'De leeractiviteit is opgeslagen.');
        }
    }

    public function update(Request $req, $id){
        // Allow only to view this page if an internship exists.
        if(Auth::user()->getCurrentWorkplaceLearningPeriod() == null)
            return redirect()->route('profile')->withErrors(['Je kan geen activiteiten registreren zonder (actieve) stage.']);

        $v = Validator::make($req->all(), [
            'datum'         => 'required|date|before:'.date('Y-m-d', strtotime('tomorrow')),
            'omschrijving'  => 'required|regex:/^[ 0-9a-zA-Z\-_,.?!*&%#()\'\\\\\/"\s]+\s*$/',
            'aantaluren'    => 'required|regex:/^[0-9]{1}[.]?[0-9]{0,2}$/',
            'resource'      => 'required|in:persoon,alleen,internet,boek,new',
            'moeilijkheid'  => 'required|exists:difficulty,difficulty_id',
            'status'        => 'required|exists:status,status_id',
        ]);

        // Conditional Validators
        $v->sometimes('newcat', 'sometimes|regex:/^[0-9a-zA-Z ()\\\\\/]{1,50}$/', function($input){
            return $input->category_id == "new";
        });
        $v->sometimes('category_id', 'required|exists:category,category_id', function($input){
            return $input->category_id != "new";
        });
        $v->sometimes('newswv', 'required|regex:/^[0-9a-zA-Z ()\\\\\/]{1,50}$/', function($input) {
            return ($input->personsource == "new" && $input->resource == "persoon");
        });
        $v->sometimes('personsource', 'required|exists:resourceperson,rp_id', function($input){
            return ($input->personsource != "new" && $input->resource == "persoon");
        });
        //$v->sometimes('internetsource', 'required|url', function($input){ temporarily loosened up validation
        $v->sometimes('internetsource', 'required|regex:/^[0-9a-zA-Z ,.\-_!@%()\\\\\/]{1,250}$/', function($input){
            return $input->resource == "internet";
        });
        $v->sometimes('booksource', 'required|regex:/^[0-9a-zA-Z ,.\-_!@%()\\\\\/]{1,250}$/', function($input){
            return $input->resource == "book";
        });
        $v->sometimes('newlerenmet', 'required|regex:/^[0-9a-zA-Z ,.\-_()\\\\\/]{1,250}$/', function($input){
            return $input->resource == "new";
        });

        if ($v->fails()) {
            return redirect()->route('process-producing-edit', ['id' => $id])
                ->withErrors($v)
                ->withInput();
        }

        $a = Auth::user()->getCurrentWorkplaceLearningPeriod()->getLearningActivityProducingById($id);
        $a->date = $req['datum'];
        $a->description = $req['omschrijving'];
        $a->duration = $req['aantaluren'];

        switch($req['resource']) {
            case 'persoon':
                $a->res_person_id = $req['personsource'];
                $a->res_material_id = null;
                $a->res_material_detail = null;
                break;
            case 'internet':
                $a->res_material_id = 1;
                $a->res_material_detail = $req['internetsource'];
                $a->res_person_id = null;
                break;
            case 'boek':
                $a->res_material_id = 2;
                $a->res_material_detail = $req['booksource'];
                $a->res_person_id = null;
                break;
        }

        $a->category_id = $req['category_id'];
        $a->difficulty_id = $req['moeilijkheid'];
        $a->status_id = $req['status'];
        $a->save();

        return redirect()->route('process-producing')->with('success', 'De leeractiviteit is aangepast.');
    }
}
