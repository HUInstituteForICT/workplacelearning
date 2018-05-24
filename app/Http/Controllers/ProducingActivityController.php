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
use App\Http\Requests;
use App\LearningActivityProducing;
use App\LearningActivityProducingExportBuilder;
use App\ResourcePerson;
use App\Status;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\UnauthorizedException;
use Validator;

class ProducingActivityController extends Controller
{

    public function show()
    {
        // Allow only to view this page if an internship exists.
        if (Auth::user()->getCurrentWorkplaceLearningPeriod() === null) {
            return redirect()->route('profile')->withErrors([Lang::get('errors.activity-no-internship')]);
        }


        $resourcePersons = Auth::user()->currentCohort()->resourcePersons()->get()->merge(
            Auth::user()->getCurrentWorkplaceLearningPeriod()->getResourcePersons()
        );

        $categories = Auth::user()->currentCohort()->categories()->get()->merge(
            Auth::user()->getCurrentWorkplaceLearningPeriod()->categories()->get()
        );

        $exportBuilder = new LearningActivityProducingExportBuilder(Auth::user()->getCurrentWorkplaceLearningPeriod()->learningActivityProducing()
            ->with('category', 'difficulty', 'status', 'resourcePerson', 'resourceMaterial')
            ->take(8)
            ->orderBy('date', 'DESC')
            ->orderBy('lap_id', 'DESC')
            ->get());

        $activitiesJson = $exportBuilder->getJson();

        $exportTranslatedFieldMapping = $exportBuilder->getFieldLanguageMapping(app()->make('translator'));



        return view('pages.producing.activity')
            ->with('learningWith', $resourcePersons)
            ->with('categories', $categories)
            ->with('difficulties', Difficulty::all())
            ->with('statuses', Status::all())
            ->with('activitiesJson', $activitiesJson)
            ->with('exportTranslatedFieldMapping', json_encode($exportTranslatedFieldMapping))
            ->with('workplacelearningperiod', Auth::user()->getCurrentWorkplaceLearningPeriod());
    }

    public function edit($id)
    {
        // Allow only to view this page if an internship exists.
        if (Auth::user()->getCurrentWorkplaceLearningPeriod() == null) {
            return redirect()->route('profile')->withErrors([Lang::get('errors.activity-no-internship')]);
        }

        $activity = Auth::user()->getCurrentWorkplaceLearningPeriod()->getLearningActivityProducingById($id);

        if (!$activity) {
            return redirect()->route('process-producing')
                ->withErrors(Lang::get('errors.no-activity-found'));
        }

        $resourcePersons = Auth::user()->currentCohort()->resourcePersons()->get()->merge(
            Auth::user()->getCurrentWorkplaceLearningPeriod()->getResourcePersons()
        );

        $categories = Auth::user()->currentCohort()->categories()->get()->merge(
            Auth::user()->getCurrentWorkplaceLearningPeriod()->categories()->get()
        );

        return view('pages.producing.activity-edit')
            ->with('activity', $activity)
            ->with('learningWith', $resourcePersons)
            ->with('categories', $categories);
    }

    public function feedback($id)
    {
        $feedback = Feedback::find($id);
        if ($feedback != null) {
            $learningActivityProducing = LearningActivityProducing::find($feedback->learningactivity_id);
        }

        return view('pages.producing.feedback')
            ->with('lap', $learningActivityProducing)
            ->with('fb', $feedback);
    }

    public function progress($pageNr)
    {
        if (Auth::user()->getCurrentWorkplaceLearningPeriod() === null) {
            return redirect()->route('profile')->withErrors([Lang::get("notifications.generic.nointernshipprogress")]);
        }

        $activities = Auth::user()->getCurrentWorkplaceLearningPeriod()->learningActivityProducing()
            ->with('category', 'difficulty', 'status', 'resourcePerson', 'resourceMaterial')
            ->orderBy('date', 'DESC')
            ->get();
        $exportBuilder = new LearningActivityProducingExportBuilder($activities);

        $activitiesJson = $exportBuilder->getJson();

        /** @var Carbon $earliest */
        $earliest = null;
        /** @var Carbon $latest */
        $latest = null;

        $activities->each(function(LearningActivityProducing $activity) use(&$earliest, &$latest) {
            $activityDate = Carbon::createFromTimestamp(strtotime($activity->date));

            if($earliest === null || $activityDate->lessThan($earliest)) {
                $earliest = $activityDate;
            }
            if($latest === null || $activityDate->greaterThan($latest)) {
                $latest = $activityDate;
            }
        });

        $exportTranslatedFieldMapping = $exportBuilder->getFieldLanguageMapping(app()->make('translator'));

        $earliest = $earliest ?? Carbon::now();
        $latest = $latest ?? Carbon::now();

        return view('pages.producing.progress')
            ->with("activitiesJson", $activitiesJson)
            ->with("exportTranslatedFieldMapping", json_encode($exportTranslatedFieldMapping))
            ->with('page', $pageNr)
            ->with('weekStatesDates', ["earliest" => $earliest->format("Y-m-d"), "latest" => $latest->format("Y-m-d")])
            ;
    }

    public function updateFeedback(Request $request, $id)
    {
        $feedback = Feedback::find($id);
        $wzh = null;
        if ($feedback != null) {
            $learningActivityProducing = LearningActivityProducing::find($feedback->learningactivity_id);
            if (is_null($learningActivityProducing) || $learningActivityProducing->wplp_id != Auth::user()->getCurrentWorkplaceLearningPeriod()->wplp_id) {
                return redirect()->route('home')->withErrors([Lang::get('errors.feedback-permission')]);
            }
        }

        $messages = [
            "newnotfinished" => Lang::get('validation.newnotfinished'),
            "ondersteuning_werkplek.required_unless" => Lang::get('validation.ondersteuning_werkplek.required_unless'),
            "ondersteuning_opleiding.required_unless" => Lang::get('validation.ondersteuning_opleiding.required_unless')
        ];

        $validator = Validator::make($request->all(), [
            'notfinished'               => 'required',
            'support_requested'         => 'required|in:0,1,2',
            'supported_provided_wp'     => 'required_unless:support_requested,0|max:150',
            'initiatief'                => 'required|max:500',
            'progress_satisfied'        => 'required|in:1,2',
            'vervolgstap_zelf'          => 'required|max:150',
            'ondersteuning_werkplek'    => 'required_unless:ondersteuningWerkplek,Geen|max:150',
            'ondersteuning_opleiding'   => 'required_unless:ondersteuningOpleiding,Geen|max:150',
        ]);


        $validator->sometimes("newnotfinished", "required|max:150", function ($input) {
            return $input->notfinished === "Anders";
        });


        if ($validator->fails()) {
            return redirect()->route('feedback-producing', ["id" => $id])
                ->withErrors($validator)
                ->withInput();
        } else {
            // Todo refactor with model->fill($request)
            $feedback->notfinished = ($request['notfinished'] == "Anders") ? $request['newnotfinished'] : $request['notfinished'];
            $feedback->initiative = $request['initiatief'];
            $feedback->progress_satisfied = $request['progress_satisfied'];
            $feedback->support_requested = $request['support_requested'];
            $feedback->supported_provided_wp = $request['supported_provided_wp'];
            $feedback->nextstep_self = $request['vervolgstap_zelf'];
            $feedback->support_needed_wp = (!isset($request['ondersteuningWerkplek'])) ? $request['ondersteuning_werkplek'] : "Geen";
            $feedback->support_needed_ed = (!isset($request['ondersteuningOpleiding'])) ? $request['ondersteuning_opleiding'] : "Geen";
            $feedback->save();

            return redirect()->route('process-producing')->with('success',
                Lang::get('activity.feedback-activity-saved'));
        }
    }

    public function create(Request $request)
    {
        // Allow only to view this page if an internship exists.
        if (Auth::user()->getCurrentWorkplaceLearningPeriod() == null) {
            return redirect()->route('profile')->withErrors([Lang::get('errors.internship-no-permission')]);
        }

        $validator = Validator::make($request->all(), [
            'datum'         => 'required|date|date_in_wplp',
            'omschrijving'  => 'required',
            'aantaluren'    => 'required',
            'resource'      => 'required|in:persoon,alleen,internet,boek,new',
            'moeilijkheid'  => 'required|exists:difficulty,difficulty_id',
            'status'        => 'required|exists:status,status_id',
        ]);

        // Conditional Validators
        $validator->sometimes('previous_wzh', 'required|exists:learningactivityproducing,lap_id', function ($input) {
            return $input->previous_wzh != "-1";
        });
        $validator->sometimes('newcat', 'sometimes|max:50', function ($input) {
            return $input->category_id == "new";
        });
        $validator->sometimes('category_id', 'required|exists:category,category_id', function ($input) {
            return $input->category_id != "new";
        });
        $validator->sometimes('newswv', 'required|max:50', function ($input) {
            return ($input->personsource == "new" && $input->resource == "persoon");
        });
        $validator->sometimes('personsource', 'required|exists:resourceperson,rp_id', function ($input) {
            return ($input->personsource != "new" && $input->resource == "persoon");
        });
        //$v->sometimes('internetsource', 'required|url', function($input){ temporarily loosened up validation
        $validator->sometimes('internetsource', 'required|max:250', function ($input) {
            return $input->resource == "internet";
        });
        $validator->sometimes('booksource', 'required|max:250', function ($input) {
            return $input->resource == "book";
        });
        $validator->sometimes('newlerenmet', 'required|max:250', function ($input) {
            return $input->resource == "new";
        });

        $validator->sometimes('aantaluren_custom', 'required|numeric', function ($input) {
            return $input->aantaluren === "x";
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
            if ($request['personsource'] == "new" && $request['resource'] === 'persoon') { //
                $resourcePerson                = new ResourcePerson;
                $resourcePerson->person_label  = $request['newswv'];
                $resourcePerson->wplp_id       = Auth::user()->getCurrentWorkplaceLearningPeriod()->wplp_id;
                $resourcePerson->ep_id         = Auth::user()->getEducationProgram()->ep_id; //deprecated, not necessary, bound to wplp..?
                $resourcePerson->save();
            }

            // Todo mass assign
            $learningActivityProducing = new LearningActivityProducing;
            $learningActivityProducing->wplp_id            = Auth::user()->getCurrentWorkplaceLearningPeriod()->wplp_id;
            $learningActivityProducing->description        = $request['omschrijving'];
            $learningActivityProducing->duration = $request['aantaluren'] !== "x" ? $request['aantaluren'] : (round(((int)$request['aantaluren_custom']) / 60,
                2));

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
                return redirect()->route('feedback-producing', ['id' => $feedback->fb_id])->with('notification', Lang::get('notifications.feedback-hard'));
            }
            return redirect()->route('process-producing')->with('success', Lang::get('activity.saved-successfully'));
        }
    }

    public function update(Request $request, $id)
    {
        // Allow only to view this page if an internship exists.
        if (Auth::user()->getCurrentWorkplaceLearningPeriod() == null) {
            return redirect()->route('profile')->withErrors([Lang::get('errors.internship-no-permission')]);
        }

        // TODO shouldn't these fields be in English?
        $validator = Validator::make($request->all(), [
            'datum'         => 'required|date|date_in_wplp',
            'omschrijving'  => 'required',
            'aantaluren'    => 'required',
            'resource'      => 'required|in:persoon,alleen,internet,boek,new',
            'moeilijkheid'  => 'required|exists:difficulty,difficulty_id',
            'status'        => 'required|exists:status,status_id',
        ]);

        // Conditional Validators
        $validator->sometimes('newcat', 'sometimes|max:50', function ($input) {

            return $input->category_id == "new";
        });
        $validator->sometimes('category_id', 'required|exists:category,category_id', function ($input) {
            return $input->category_id != "new";
        });
        $validator->sometimes('newswv', 'required|max:50', function ($input) {
            return ($input->personsource == "new" && $input->resource == "persoon");
        });
        $validator->sometimes('personsource', 'required|exists:resourceperson,rp_id', function ($input) {
            return ($input->personsource != "new" && $input->resource == "persoon");
        });
        //$v->sometimes('internetsource', 'required|url', function($input){ temporarily loosened up validation

        $validator->sometimes('internetsource', 'required|url|max:75', function ($input) {
            return $input->resource == "internet";
        });
        $validator->sometimes('booksource', 'required|max:75', function ($input) {
            return $input->resource == "book";
        });
        $validator->sometimes('newlerenmet', 'required|max:250', function ($input) {
            return $input->resource == "new";
        });

        $validator->sometimes('aantaluren_custom', 'required|numeric', function ($input) {
            return $input->aantaluren === "x";
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
        $learningActivityProducing->duration = $request['aantaluren'] !== "x" ? $request['aantaluren'] : (round(((int)$request['aantaluren_custom']) / 60,
            2));


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
            case 'alleen':
                $learningActivityProducing->res_person_id = null;
                $learningActivityProducing->res_material_id = null;
                $learningActivityProducing->res_material_detail = null;
                break;
        }

        $learningActivityProducing->category_id = $request['category_id'];
        $learningActivityProducing->difficulty_id = $request['moeilijkheid'];
        $learningActivityProducing->status_id = $request['status'];
        $learningActivityProducing->save();

        return redirect()->route('process-producing')->with('success', Lang::get('activity.saved-successfully'));
    }

    public function delete(LearningActivityProducing $activity)
    {
        if($activity === null) {
            return redirect()->route('process-acting');
        }
        // Allow only to view this page if an internship exists.
        if (Auth::user()->getCurrentWorkplaceLearningPeriod() == null) {
            return redirect()->route('profile')->withErrors([Lang::get('errors.activity-no-internship')]);
        }

        if(Auth::user()->getCurrentWorkplaceLearningPeriod()->wplp_id !== $activity->wplp_id) {
            throw new UnauthorizedException("No access");
        }

        $activity->feedback()->delete();
        $activity->delete();

        return redirect()->route('process-producing');
    }
}
