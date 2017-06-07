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

class ProducingActivityController extends Controller
{

    public function show()
    {
        // Allow only to view this page if an internship exists.
        if (Auth::user()->getCurrentWorkplaceLearningPeriod() == null) {
            return redirect()->route('profile')->withErrors(['Je kan geen activiteiten registreren zonder (actieve) stage.']);
        }

        $resourcePersons = Auth::user()->getEducationProgram()->getResourcePersons()->union(
            Auth::user()->getCurrentWorkplaceLearningPeriod()->getResourcePersons()
        );

        return view('pages.producing.activity')
            ->with('learningWith', $resourcePersons)
            ->with('difficulties', Difficulty::all())
            ->with('statuses', Status::all());
    }

    public function edit($id)
    {
        // Allow only to view this page if an internship exists.
        if (Auth::user()->getCurrentWorkplaceLearningPeriod() == null) {
            return redirect()->route('profile')->withErrors(['Je kan geen activiteiten registreren zonder (actieve) stage.']);
        }

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

    public function feedback($id)
    {
        $feedback  = Feedback::find($id);
        if ($feedback != null) {
            $learningActivityProducing = LearningActivityProducing::find($feedback->learningactivity_id);
        }
        return view('pages.producing.feedback')
            ->with('lap', $learningActivityProducing)
            ->with('fb', $feedback);
    }

    public function progress($pageNr)
    {
        return view('pages.producing.progress')->with('page', $pageNr);
    }

    public function updateFeedback(Request $request, $id)
    {
        $feedback  = Feedback::find($id);
        $wzh = null;
        if ($feedback != null) {
            $learningActivityProducing = LearningActivityProducing::find($feedback->wzh_id);
            if (is_null($learningActivityProducing) || $learningActivityProducing->wplp_id != Auth::user()->getCurrentWorkplaceLearningPeriod()->wplp_id) {
                return redirect()->route('home')->withErrors(['Je hebt geen rechten om deze feedback te versturen']);
            }
        }

        $validator = Validator::make($request->all(), [
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
        if ($validator->fails()) {
            return redirect()->route('feedback-producing', ["id" => $id])
                ->withErrors($validator)
                ->withInput();
        } else {
            // Todo refactor with model->fill($request)
            $feedback->notfinished                = ($request['notfinished'] == "Anders") ? $request['newnotfinished'] : $request['notfinished'];
            $feedback->initiative                 = $request['initiatief'];
            $feedback->progress_satisfied         = $request['progress_satisfied'];
            $feedback->support_requested          = $request['support_requested'];
            $feedback->supported_provided_wp      = $request['supported_provided_wp'];
            $feedback->nextstep_self              = $request['vervolgstap_zelf'];
            $feedback->support_needed_wp          = (!isset($request['ondersteuningWerkplek'])) ? $request['ondersteuning_werkplek'] : "Geen";
            $feedback->support_needed_ed          = (!isset($request['ondersteuningOpleiding'])) ? $request['ondersteuning_opleiding'] : "Geen";
            $feedback->save();
            return redirect()->route('feedback-producing', ['id' => $id])->with('success', 'De feedback is opgeslagen.');
        }
    }

    public function create(Request $request)
    {
        // Allow only to view this page if an internship exists.
        if (Auth::user()->getCurrentWorkplaceLearningPeriod() == null) {
            return redirect()->route('profile')->withErrors(['Je kan geen activiteiten registreren zonder (actieve) stage.']);
        }

        $validator = Validator::make($request->all(), [
            'datum'         => 'required|date|before:'.date('Y-m-d', strtotime('tomorrow')),
            'omschrijving'  => 'required|regex:/^[ 0-9a-zA-Z\-_,.?!*&%#()\'\\\\\/"\s]+\s*$/',
            'aantaluren'    => 'required|regex:/^[0-9]{1}[.]?[0-9]{0,2}$/',
            'resource'      => 'required|in:persoon,alleen,internet,boek,new',
            'moeilijkheid'  => 'required|exists:difficulty,difficulty_id',
            'status'        => 'required|exists:status,status_id',
        ]);

        // Conditional Validators
        $validator->sometimes('previous_wzh', 'required|exists:learningactivityproducing,lap_id', function ($input) {
            return $input->previous_wzh != "-1";
        });
        $validator->sometimes('newcat', 'sometimes|regex:/^[0-9a-zA-Z ()\\\\\/]{1,50}$/', function ($input) {
            return $input->category_id == "new";
        });
        $validator->sometimes('category_id', 'required|exists:category,category_id', function ($input) {
            return $input->category_id != "new";
        });
        $validator->sometimes('newswv', 'required|regex:/^[0-9a-zA-Z ()\\\\\/]{1,50}$/', function ($input) {
            return ($input->personsource == "new" && $input->resource == "persoon");
        });
        $validator->sometimes('personsource', 'required|exists:resourceperson,rp_id', function ($input) {
            return ($input->personsource != "new" && $input->resource == "persoon");
        });
        //$v->sometimes('internetsource', 'required|url', function($input){ temporarily loosened up validation
        $validator->sometimes('internetsource', 'required|regex:/^[0-9a-zA-Z ,.\-_!@%()\\\\\/]{1,250}$/', function ($input) {
            return $input->resource == "internet";
        });
        $validator->sometimes('booksource', 'required|regex:/^[0-9a-zA-Z ,.\-_!@%()\\\\\/]{1,250}$/', function ($input) {
            return $input->resource == "book";
        });
        $validator->sometimes('newlerenmet', 'required|regex:/^[0-9a-zA-Z ,.\-_()\\\\\/]{1,250}$/', function ($input) {
            return $input->resource == "new";
        });

        // Validate the input
        if ($validator->fails()) {
            return redirect()->route('process-producing')
                ->withErrors($validator)
                ->withInput();
        } else {
            // All ok.
            if ($request['resource'] == "new") {
                $request['resource'] = "other";
            }
            if ($request['category_id'] == "new") {
                $category                  = new Category;
                $category->category_label  = $request['newcat'];
                $category->wplp_id         = Auth::user()->getCurrentWorkplaceLearningPeriod()->wplp_id;
                $category->save();
            }
            if ($request['personsource'] == "new") {
                $resourcePerson                = new ResourcePerson;
                $resourcePerson->person_label  = $request['newswv'];
                $resourcePerson->wplp_id       = Auth::user()->getCurrentWorkplaceLearningPeriod()->wplp_id;
                $resourcePerson->ep_id         = Auth::user()->getEducationProgram()->ep_id;
                $resourcePerson->save();
            }

            // Todo mass assign
            $learningActivityProducing = new LearningActivityProducing;
            $learningActivityProducing->wplp_id            = Auth::user()->getCurrentWorkplaceLearningPeriod()->wplp_id;
            $learningActivityProducing->description        = $request['omschrijving'];
            $learningActivityProducing->duration           = $request['aantaluren'];

            switch ($request['resource']) {
                case 'persoon':
                    $learningActivityProducing->res_person_id = $request['personsource'] == 'new' ? $resourcePerson->rp_id : $request['personsource'];
                    break;
                case 'internet':
                    $learningActivityProducing->res_material_id = 1;
                    $learningActivityProducing->res_material_detail = $request['internetsource'];
                    break;
                case 'boek':
                    $learningActivityProducing->res_material_id = 2;
                    $learningActivityProducing->res_material_detail = $request['booksource'];
                    break;
            }

            $learningActivityProducing->category_id             = ($request['category_id'] == "new") ? $category->category_id : $request['category_id'];
            $learningActivityProducing->difficulty_id           = $request['moeilijkheid'];
            $learningActivityProducing->status_id               = $request['status'];
            $learningActivityProducing->prev_lap_id             = ($request['previous_wzh'] != "-1") ? $request['previous_wzh'] : null;
            $learningActivityProducing->date                    = date_format(date_create($request->datum, timezone_open("Europe/Amsterdam")), 'Y-m-d H:i:s');
            $learningActivityProducing->save();

            if (($learningActivityProducing->difficulty_id == 2 || $learningActivityProducing->difficulty_id == 3)
                && ($learningActivityProducing->status_id == 2)
            ) {
                // Create Feedback object and redirect
                $feedback = new Feedback;
                $feedback->learningactivity_id = $learningActivityProducing->lap_id;
                $feedback->save();
                return redirect()->route('feedback-producing', ['id' => $feedback->fb_id])->with('notification', 'Je vond deze activiteit moeilijk. Kan je aangeven wat je lastig vond?');
            }
            return redirect()->route('process-producing')->with('success', 'De leeractiviteit is opgeslagen.');
        }
    }

    public function update(Request $request, $id)
    {
        // Allow only to view this page if an internship exists.
        if (Auth::user()->getCurrentWorkplaceLearningPeriod() == null) {
            return redirect()->route('profile')->withErrors(['Je kan geen activiteiten registreren zonder (actieve) stage.']);
        }

        // TODO shouldn't these fields be in English?
        $validator = Validator::make($request->all(), [
            'datum'         => 'required|date|before:'.date('Y-m-d', strtotime('tomorrow')),
            'omschrijving'  => 'required|regex:/^[ 0-9a-zA-Z\-_,.?!*&%#()\'\\\\\/"\s]+\s*$/',
            'aantaluren'    => 'required|regex:/^[0-9]{1}[.]?[0-9]{0,2}$/',
            'resource'      => 'required|in:persoon,alleen,internet,boek,new',
            'moeilijkheid'  => 'required|exists:difficulty,difficulty_id',
            'status'        => 'required|exists:status,status_id',
        ]);

        // Conditional Validators
        $validator->sometimes('newcat', 'sometimes|regex:/^[0-9a-zA-Z ()\\\\\/]{1,50}$/', function ($input) {
            return $input->category_id == "new";
        });
        $validator->sometimes('category_id', 'required|exists:category,category_id', function ($input) {
            return $input->category_id != "new";
        });
        $validator->sometimes('newswv', 'required|regex:/^[0-9a-zA-Z ()\\\\\/]{1,50}$/', function ($input) {
            return ($input->personsource == "new" && $input->resource == "persoon");
        });
        $validator->sometimes('personsource', 'required|exists:resourceperson,rp_id', function ($input) {
            return ($input->personsource != "new" && $input->resource == "persoon");
        });
        //$v->sometimes('internetsource', 'required|url', function($input){ temporarily loosened up validation
        $validator->sometimes('internetsource', 'required|regex:/^[0-9a-zA-Z ,.\-_!@%()\\\\\/]{1,250}$/', function ($input) {
            return $input->resource == "internet";
        });
        $validator->sometimes('booksource', 'required|regex:/^[0-9a-zA-Z ,.\-_!@%()\\\\\/]{1,250}$/', function ($input) {
            return $input->resource == "book";
        });
        $validator->sometimes('newlerenmet', 'required|regex:/^[0-9a-zA-Z ,.\-_()\\\\\/]{1,250}$/', function ($input) {
            return $input->resource == "new";
        });

        if ($validator->fails()) {
            return redirect()->route('process-producing-edit', ['id' => $id])
                ->withErrors($validator)
                ->withInput();
        }

        // Todo refactor model->fill()
        $learningActivityProducing = Auth::user()->getCurrentWorkplaceLearningPeriod()->getLearningActivityProducingById($id);
        $learningActivityProducing->date = $request['datum'];
        $learningActivityProducing->description = $request['omschrijving'];
        $learningActivityProducing->duration = $request['aantaluren'];

        // Todo refactor extract method?
        switch ($request['resource']) {
            case 'persoon':
                $learningActivityProducing->res_person_id = $request['personsource'];
                $learningActivityProducing->res_material_id = null;
                $learningActivityProducing->res_material_detail = null;
                break;
            case 'internet':
                $learningActivityProducing->res_material_id = 1;
                $learningActivityProducing->res_material_detail = $request['internetsource'];
                $learningActivityProducing->res_person_id = null;
                break;
            case 'boek':
                $learningActivityProducing->res_material_id = 2;
                $learningActivityProducing->res_material_detail = $request['booksource'];
                $learningActivityProducing->res_person_id = null;
                break;
        }

        $learningActivityProducing->category_id = $request['category_id'];
        $learningActivityProducing->difficulty_id = $request['moeilijkheid'];
        $learningActivityProducing->status_id = $request['status'];
        $learningActivityProducing->save();

        return redirect()->route('process-producing')->with('success', 'De leeractiviteit is aangepast.');
    }
}
