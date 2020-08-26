<?php

declare(strict_types=1);
/**
 * This file (ProducingActivityController.php) was created on 06/27/2016 at 16:10.
 * (C) Max Cassee
 * This project was commissioned by HU University of Applied Sciences.
 */

namespace App\Http\Controllers;

use App\Http\Requests\LearningActivity\ProducingCreateRequest;
use App\Http\Requests\LearningActivity\ProducingUpdateRequest;
use App\LearningActivityProducing;
use App\Repository\Eloquent\LearningActivityProducingRepository;
use App\Services\AvailableProducingEntitiesFetcher;
use App\Services\CurrentUserResolver;
use App\Services\CustomProducingEntityHandler;
use App\Services\Factories\LAPFactory;
use App\Services\LAPUpdater;
use App\Services\LearningActivityProducingExportBuilder;
use Carbon\Carbon;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProducingActivityController
{
    /**
     * @var CurrentUserResolver
     */
    private $currentUserResolver;
    /**
     * @var LearningActivityProducingRepository
     */
    private $learningActivityProducingRepository;
    /**
     * @var Session
     */
    private $session;

    public function __construct(
        CurrentUserResolver $currentUserResolver,
        LearningActivityProducingRepository $learningActivityProducingRepository,
        Session $session
    ) {
        $this->currentUserResolver = $currentUserResolver;
        $this->learningActivityProducingRepository = $learningActivityProducingRepository;
        $this->session = $session;
    }

    public function show(
        AvailableProducingEntitiesFetcher $availableProducingEntitiesFetcher,
        LearningActivityProducingExportBuilder $exportBuilder
    ) {
        $student = $this->currentUserResolver->getCurrentUser();

        $activitiesJson = $exportBuilder->getJson($this->learningActivityProducingRepository->getActivitiesOfLastActiveDayForStudent($student));

        $exportTranslatedFieldMapping = $exportBuilder->getFieldLanguageMapping();

        return view('pages.producing.activity', $availableProducingEntitiesFetcher->getEntities())
            ->with('activitiesJson', $activitiesJson)
            ->with('exportTranslatedFieldMapping', json_encode($exportTranslatedFieldMapping))
            ->with('workplacelearningperiod', $student->getCurrentWorkplaceLearningPeriod());
    }

    public function edit(
        LearningActivityProducing $learningActivityProducing,
        AvailableProducingEntitiesFetcher $availableProducingEntitiesFetcher,
        Request $request
    ) {
        $referrer = $request->header('referer');
        $redirect = route('process-producing');
        if ($referrer && $referrer === route('progress-producing')) {
            $redirect = route('progress-producing');
        }
        $this->session->put('producing.activity.edit.referrer', $redirect);

        return view('pages.producing.activity-edit', $availableProducingEntitiesFetcher->getEntities())
            ->with('activity', $learningActivityProducing);
    }

    public function progress(LearningActivityProducingExportBuilder $exportBuilder)
    {
        $student = $this->currentUserResolver->getCurrentUser();
        $activities = $this->learningActivityProducingRepository->getActivitiesForStudent($student);

        $activitiesJson = $exportBuilder->getJson($activities, null);
        $exportTranslatedFieldMapping = $exportBuilder->getFieldLanguageMapping();

        $earliest = $this->learningActivityProducingRepository->earliestActivityForStudent($student)->date ?? Carbon::now();
        $latest = $this->learningActivityProducingRepository->latestActivityForStudent($student)->date ?? Carbon::now();

        return view('pages.producing.progress', [
                'activitiesJson'               => $activitiesJson,
                'exportTranslatedFieldMapping' => json_encode($exportTranslatedFieldMapping),
                'weekStatesDates'              => [
                    'earliest' => $earliest->format('Y-m-d'),
                    'latest'   => $latest->format('Y-m-d'),
                ],
            ]
        );
    }

    public function create(
        ProducingCreateRequest $request,
        LAPFactory $LAPFactory,
        CustomProducingEntityHandler $customProducingEntityHandler
    ) {
        // Because related entities can be created during this route, create them first and set their ids
        // in the request data so that the factory can be relatively simple
        $data = $customProducingEntityHandler->process($request->all());

        $learningActivityProducing = $LAPFactory->createLAP($data);

        if ($learningActivityProducing->feedback) {
            session()->flash('notification', __('notifications.feedback-hard'));
            $url = route('feedback-producing', ['feedback' => $learningActivityProducing->feedback]);

            if ($request->acceptsJson()) {
                return response()->json([
                        'status' => 'success',
                        'url'    => $url,
                    ]);
            }

            return redirect($url);
        }

        session()->flash('success', __('activity.saved-successfully'));
        $url = route('process-producing');

        if ($request->acceptsJson()) {
            return response()->json([
                'status' => 'success',
                'url'    => $url,
            ]);
        }

        return redirect($url);
    }

    public function update(
        ProducingUpdateRequest $request,
        LearningActivityProducing $learningActivityProducing,
        LAPUpdater $LAPUpdater
    ) {
        $LAPUpdater->update($learningActivityProducing, $request->all());

        session()->flash('success', __('activity.saved-successfully'));
        $url = route('process-producing');
        if ($this->session->has('producing.activity.edit.referrer')) {
            $url = $this->session->remove('producing.activity.edit.referrer');
        }

        if ($request->acceptsJson()) {
            return response()->json([
                'status' => 'success',
                'url'    => $url,
            ]);
        }

        return redirect($url);
    }

    public function delete(LearningActivityProducing $learningActivityProducing): RedirectResponse
    {
        $this->learningActivityProducingRepository->delete($learningActivityProducing);

        return redirect()->route('process-producing');
    }
}
