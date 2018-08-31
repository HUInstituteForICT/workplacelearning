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
use App\Http\Requests\LearningActivity\ProducingUpdateRequest;
use App\LearningActivityActing;
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
            return redirect()->route('profile')->withErrors([__('errors.activity-no-internship')]);
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

    public function edit(Request $request, LearningActivityProducing $learningActivityProducing)
    {
        // Allow only to view this page if an internship exists.
        if (null === Auth::user()->getCurrentWorkplaceLearningPeriod()) {
            return redirect()->route('profile')->withErrors([__('errors.activity-no-internship')]);
        }

        if (!$learningActivityProducing) {
            return redirect()->route('process-producing')
                ->withErrors(__('errors.no-activity-found'));
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
            ->with('activity', $learningActivityProducing)
            ->with('learningWith', $resourcePersons)
            ->with('categories', $categories)
            ->with('chains', $chains);
    }

    public function progress(Translator $translator)
    {
        if (null === Auth::user()->getCurrentWorkplaceLearningPeriod()) {
            return redirect()->route('profile')->withErrors([__('notifications.generic.nointernshipprogress')]);
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

    public function create(ProducingCreateRequest $request, LAPFactory $LAPManager)
    {
        // Allow only to view this page if an internship exists.
        if (null === Auth::user()->getCurrentWorkplaceLearningPeriod()) {
            return redirect()->route('profile')->withErrors([__('errors.internship-no-permission')]);
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
                ->with('notification', __('notifications.feedback-hard'));
        }

        return redirect()
            ->route('process-producing')
            ->with('success', __('activity.saved-successfully'));
    }

    public function update(ProducingUpdateRequest $request, ChainManager $chainManager, LearningActivityProducing $learningActivityProducing)
    {
        // Allow only to view this page if an internship exists.
        if (null === Auth::user()->getCurrentWorkplaceLearningPeriod()) {
            return redirect()->route('profile')->withErrors([__('errors.internship-no-permission')]);
        }

        /** @var LearningActivityProducing $learningActivityProducing */
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

        return redirect()->route('process-producing')->with('success', __('activity.saved-successfully'));
    }

    public function delete(LearningActivityProducing $learningActivityProducing)
    {
        if (null === $learningActivityProducing) {
            return redirect()->route('process-producing');
        }
        // Allow only to view this page if an internship exists.
        if (null === Auth::user()->getCurrentWorkplaceLearningPeriod()) {
            return redirect()->route('profile')->withErrors([__('errors.activity-no-internship')]);
        }

        if (null !== $learningActivityProducing->nextLearningActivityProducing()->first()) {
            return redirect()->route('process-producing')->withErrors([__('errors.activity-in-chain')]);
        }

        if (Auth::user()->getCurrentWorkplaceLearningPeriod()->wplp_id !== $learningActivityProducing->wplp_id) {
            throw new UnauthorizedException('No access');
        }

        $learningActivityProducing->feedback()->delete();
        $learningActivityProducing->delete();

        return redirect()->route('process-producing');
    }
}
