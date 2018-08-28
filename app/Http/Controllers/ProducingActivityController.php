<?php
/**
 * This file (ProducingActivityController.php) was created on 06/27/2016 at 16:10.
 * (C) Max Cassee
 * This project was commissioned by HU University of Applied Sciences.
 */

namespace App\Http\Controllers;

use App\Chain;
use App\ChainManager;
use App\Difficulty;
use App\Feedback;
use App\Http\Requests\LearningActivity\ProducingCreateRequest;
use App\LearningActivityProducing;
use App\LearningActivityProducingExportBuilder;
use App\Services\LAPFactory;
use App\Status;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Translation\Translator;
use Illuminate\Validation\UnauthorizedException;
use Validator;

class ProducingActivityController extends Controller
{
    public function show(Request $request, Translator $translator)
    {
        // Allow only to view this page if an internship exists.
        if (null === Auth::user()->getCurrentWorkplaceLearningPeriod()) {
            return redirect()->route('profile')->withErrors([Lang::get('errors.activity-no-internship')]);
        }

        $resourcePersons = Auth::user()->currentCohort()->resourcePersons()->get()->merge(
            Auth::user()->getCurrentWorkplaceLearningPeriod()->getResourcePersons()
        );

        $categories = Auth::user()->currentCohort()->categories()->get()->merge(
            Auth::user()->getCurrentWorkplaceLearningPeriod()->categories()->get()
        );

        $exportBuilder = new LearningActivityProducingExportBuilder(Auth::user()->getCurrentWorkplaceLearningPeriod()->learningActivityProducing()
            ->with('category', 'difficulty', 'status', 'resourcePerson', 'resourceMaterial', 'chain', 'feedback')
            ->take(8)
            ->orderBy('date', 'DESC')
            ->orderBy('lap_id', 'DESC')
            ->get(), $translator);

        $activitiesJson = $exportBuilder->getJson();

        $exportTranslatedFieldMapping = $exportBuilder->getFieldLanguageMapping(app()->make('translator'));

        $wplp = $request->user()->getCurrentWorkplaceLearningPeriod();

        $chains = $wplp->chains;

        return view('pages.producing.activity')
            ->with('learningWith', $resourcePersons)
            ->with('categories', $categories)
            ->with('difficulties', Difficulty::all())
            ->with('statuses', Status::all())
            ->with('activitiesJson', $activitiesJson)
            ->with('exportTranslatedFieldMapping', json_encode($exportTranslatedFieldMapping))
            ->with('workplacelearningperiod', Auth::user()->getCurrentWorkplaceLearningPeriod())
            ->with('chains', $chains);
    }

    public function edit(Request $request, $id)
    {
        // Allow only to view this page if an internship exists.
        if (null == Auth::user()->getCurrentWorkplaceLearningPeriod()) {
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

        $wplp = $request->user()->getCurrentWorkplaceLearningPeriod();

        $chains = $wplp->chains;

        return view('pages.producing.activity-edit')
            ->with('activity', $activity)
            ->with('learningWith', $resourcePersons)
            ->with('categories', $categories)
            ->with('chains', $chains);
    }

    public function feedback(Feedback $feedback)
    {
        return view('pages.producing.feedback')
            ->with('lap', $feedback->learningActivityProducing)
            ->with('feedback', $feedback);
    }

    public function progress(Translator $translator)
    {
        if (null === Auth::user()->getCurrentWorkplaceLearningPeriod()) {
            return redirect()->route('profile')->withErrors([Lang::get('notifications.generic.nointernshipprogress')]);
        }

        $activities = Auth::user()->getCurrentWorkplaceLearningPeriod()->learningActivityProducing()
            ->with('category', 'difficulty', 'status', 'resourcePerson', 'resourceMaterial')
            ->orderBy('date', 'DESC')
            ->get();
        $exportBuilder = new LearningActivityProducingExportBuilder($activities, $translator);

        $activitiesJson = $exportBuilder->getJson();

        /** @var Carbon $earliest */
        $earliest = null;
        /** @var Carbon $latest */
        $latest = null;

        $activities->each(function (LearningActivityProducing $activity) use (&$earliest, &$latest) {
            $activityDate = Carbon::createFromTimestamp(strtotime($activity->date));

            if (null === $earliest || $activityDate->lessThan($earliest)) {
                $earliest = $activityDate;
            }
            if (null === $latest || $activityDate->greaterThan($latest)) {
                $latest = $activityDate;
            }
        });

        $exportTranslatedFieldMapping = $exportBuilder->getFieldLanguageMapping(app()->make('translator'));

        $earliest = $earliest ?? Carbon::now();
        $latest = $latest ?? Carbon::now();

        return view('pages.producing.progress')
            ->with('activitiesJson', $activitiesJson)
            ->with('exportTranslatedFieldMapping', json_encode($exportTranslatedFieldMapping))
            ->with('weekStatesDates', ['earliest' => $earliest->format('Y-m-d'), 'latest' => $latest->format('Y-m-d')]);
    }

    public function updateFeedback(Request $request, Feedback $feedback)
    {
        $wzh = null;
        $learningActivityProducing = $feedback->learningActivityProducing;

        if ($learningActivityProducing->wplp_id !== Auth::user()->getCurrentWorkplaceLearningPeriod()->wplp_id) {
            return redirect()->route('home')->withErrors([Lang::get('errors.feedback-permission')]);
        }

        $messages = [
            'newnotfinished' => Lang::get('validation.newnotfinished'),
            'ondersteuning_werkplek.required_unless' => Lang::get('validation.ondersteuning_werkplek.required_unless'),
            'ondersteuning_opleiding.required_unless' => Lang::get('validation.ondersteuning_opleiding.required_unless'),
        ];

        $validator = Validator::make($request->all(), [
            'notfinished' => 'required',
            'support_requested' => 'required|in:0,1,2',
            'supported_provided_wp' => 'required_unless:support_requested,0|max:150',
            'initiatief' => 'required|max:500',
            'progress_satisfied' => 'required|in:1,2',
            'vervolgstap_zelf' => 'required|max:150',
            'ondersteuning_werkplek' => 'required_unless:ondersteuningWerkplek,Geen|max:150',
            'ondersteuning_opleiding' => 'required_unless:ondersteuningOpleiding,Geen|max:150',
        ]);

        $validator->sometimes('newnotfinished', 'required|max:150', function ($input) {
            return 'Anders' === $input->notfinished;
        });

        if ($validator->fails()) {
            return redirect()->route('feedback-producing', ['id' => $feedback->fb_id])
                ->withErrors($validator)
                ->withInput();
        }

        $feedback->notfinished = ('Anders' === $request['notfinished']) ? $request['newnotfinished'] : $request['notfinished'];
        $feedback->initiative = $request['initiatief'];
        $feedback->progress_satisfied = $request['progress_satisfied'];
        $feedback->support_requested = $request['support_requested'];
        $feedback->supported_provided_wp = $request['supported_provided_wp'];
        $feedback->nextstep_self = $request['vervolgstap_zelf'];
        $feedback->support_needed_wp = !isset($request['ondersteuningWerkplek']) ? $request['ondersteuning_werkplek'] : 'Geen';
        $feedback->support_needed_ed = !isset($request['ondersteuningOpleiding']) ? $request['ondersteuning_opleiding'] : 'Geen';
        $feedback->save();

        return redirect()->route('process-producing')->with(
            'success',
            Lang::get('activity.feedback-activity-saved')
        );
    }

    public function create(ProducingCreateRequest $request, LAPFactory $LAPManager)
    {
        // Allow only to view this page if an internship exists.
        if (null === Auth::user()->getCurrentWorkplaceLearningPeriod()) {
            return redirect()->route('profile')->withErrors([Lang::get('errors.internship-no-permission')]);
        }

        $learningActivityProducing = $LAPManager->createLAP($request->all());

        $difficulty = $learningActivityProducing->difficulty;
        $status = $learningActivityProducing->status;

        if ($status->isBusy() && ($difficulty->isHard() || $difficulty->isAverage())) {
            // Create Feedback object and redirect
            $feedback = new Feedback();
            $feedback->learningActivityProducing()->associate($learningActivityProducing);
            $feedback->save();

            return redirect()
                ->route('feedback-producing', ['id' => $feedback->fb_id])
                ->with('notification', Lang::get('notifications.feedback-hard'));
        }

        return redirect()
            ->route('process-producing')
            ->with('success', Lang::get('activity.saved-successfully'));
    }

    public function update(Request $request, ChainManager $chainManager, $id)
    {
        // Allow only to view this page if an internship exists.
        if (null === Auth::user()->getCurrentWorkplaceLearningPeriod()) {
            return redirect()->route('profile')->withErrors([Lang::get('errors.internship-no-permission')]);
        }

        // TODO shouldn't these fields be in English?
        $validator = Validator::make($request->all(), [
            'datum' => 'required|date|date_in_wplp',
            'omschrijving' => 'required',
            'aantaluren' => 'required',
            'resource' => 'required|in:persoon,alleen,internet,boek,new',
            'moeilijkheid' => 'required|exists:difficulty,difficulty_id',
            'status' => 'required|exists:status,status_id',
            'chain_id' => 'canChain',
        ]);

        // Conditional Validators
        $validator->sometimes('newcat', 'sometimes|max:50', function ($input) {
            return 'new' == $input->category_id;
        });
        $validator->sometimes('category_id', 'required|exists:category,category_id', function ($input) {
            return 'new' != $input->category_id;
        });
        $validator->sometimes('newswv', 'required|max:50', function ($input) {
            return 'new' == $input->personsource && 'persoon' == $input->resource;
        });
        $validator->sometimes('personsource', 'required|exists:resourceperson,rp_id', function ($input) {
            return 'new' != $input->personsource && 'persoon' == $input->resource;
        });
        //$v->sometimes('internetsource', 'required|url', function($input){ temporarily loosened up validation

        $validator->sometimes('internetsource', 'required|url|max:75', function ($input) {
            return 'internet' == $input->resource;
        });
        $validator->sometimes('booksource', 'required|max:75', function ($input) {
            return 'book' == $input->resource;
        });
        $validator->sometimes('newlerenmet', 'required|max:250', function ($input) {
            return 'new' == $input->resource;
        });

        $validator->sometimes('aantaluren_custom', 'required|numeric', function ($input) {
            return 'x' === $input->aantaluren;
        });

        if ($validator->fails()) {
            return redirect()->route('process-producing-edit', ['id' => $id])
                ->withErrors($validator)
                ->withInput();
        }

        // Todo refactor model->fill()
        /** @var LearningActivityProducing $learningActivityProducing */
        $learningActivityProducing = Auth::user()->getCurrentWorkplaceLearningPeriod()->getLearningActivityProducingById($id);
        $learningActivityProducing->date = $request['datum'];
        $learningActivityProducing->description = $request['omschrijving'];
        $learningActivityProducing->duration = 'x' !== $request['aantaluren'] ? $request['aantaluren'] : round(
            ((int) $request['aantaluren_custom']) / 60,
            2
        );

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
        $learningActivityProducing->prev_lap_id = ('-1' != $request['previous_wzh']) ? $request['previous_wzh'] : null;

        $chainId = $request->get('chain_id', null);

        if (null !== $chainId) {
            if (((int) $chainId) === -1) {
                $learningActivityProducing->chain_id = null;
            } elseif (((int) $chainId) !== -1) {
                $chain = (new Chain())->find($chainId);
                if (Chain::STATUS_FINISHED !== $chain->status) {
                    $chainManager->attachActivity($learningActivityProducing, $chain);
                }
            }
        }

        $learningActivityProducing->save();

        return redirect()->route('process-producing')->with('success', Lang::get('activity.saved-successfully'));
    }

    public function delete(LearningActivityProducing $activity)
    {
        if (null === $activity) {
            return redirect()->route('process-producing');
        }
        // Allow only to view this page if an internship exists.
        if (null === Auth::user()->getCurrentWorkplaceLearningPeriod()) {
            return redirect()->route('profile')->withErrors([Lang::get('errors.activity-no-internship')]);
        }

        if (null !== $activity->nextLearningActivityProducing()->first()) {
            return redirect()->route('process-producing')->withErrors([Lang::get('errors.activity-in-chain')]);
        }

        if (Auth::user()->getCurrentWorkplaceLearningPeriod()->wplp_id !== $activity->wplp_id) {
            throw new UnauthorizedException('No access');
        }

        $activity->feedback()->delete();
        $activity->delete();

        return redirect()->route('process-producing');
    }
}
