<?php

namespace App\Http\Controllers;

use App\Http\Requests\LearningActivity\ActingCreateRequest;
use App\Http\Requests\LearningActivity\ActingUpdateRequest;
use App\LearningActivityActing;
use App\Repository\Eloquent\LearningActivityActingRepository;
use App\Services\AvailableActingEntitiesFetcher;
use App\Services\CurrentUserResolver;
use App\Services\EvidenceUploadHandler;
use App\Services\Factories\LAAFactory;
use App\Services\LAAUpdater;
use App\Services\LearningActivityActingExportBuilder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;

class ActingActivityController
{
    /**
     * @var Redirector
     */
    private $redirector;

    /**
     * @var CurrentUserResolver
     */
    private $currentUserResolver;
    /**
     * @var LearningActivityActingRepository
     */
    private $learningActivityActingRepository;

    public function __construct(
        Redirector $redirector,
        CurrentUserResolver $currentUserResolver,
        LearningActivityActingRepository $learningActivityActingRepository
    ) {
        $this->redirector = $redirector;
        $this->currentUserResolver = $currentUserResolver;
        $this->learningActivityActingRepository = $learningActivityActingRepository;
    }

    public function show(
        AvailableActingEntitiesFetcher $availableActingEntitiesFetcher,
        LearningActivityActingExportBuilder $exportBuilder
    ) {
        $student = $this->currentUserResolver->getCurrentUser();

        $activitiesJson = $exportBuilder->getJson($this->learningActivityActingRepository->getActivitiesForStudent($student),
            8);

        $exportTranslatedFieldMapping = $exportBuilder->getFieldLanguageMapping();

        return view('pages.acting.activity', $availableActingEntitiesFetcher->getEntities())
            ->with('activitiesJson', $activitiesJson)
            ->with('exportTranslatedFieldMapping', json_encode($exportTranslatedFieldMapping))
            ->with('workplacelearningperiod', $student->getCurrentWorkplaceLearningPeriod());
    }

    public function edit(
        LearningActivityActing $learningActivityActing,
        AvailableActingEntitiesFetcher $availableActingEntitiesFetcher
    ) {
        return view('pages.acting.activity-edit', $availableActingEntitiesFetcher->getEntities())
            ->with('activity', $learningActivityActing);
    }

    public function progress(LearningActivityActingExportBuilder $exportBuilder)
    {
        $student = $this->currentUserResolver->getCurrentUser();

        $activitiesJson = $exportBuilder->getJson(
            $this->learningActivityActingRepository->getActivitiesForStudent($student),
            null
        );

        $exportTranslatedFieldMapping = $exportBuilder->getFieldLanguageMapping();

        return view('pages.acting.progress')
            ->with('activitiesJson', $activitiesJson)
            ->with('exportTranslatedFieldMapping', json_encode($exportTranslatedFieldMapping));
    }

    public function create(
        ActingCreateRequest $request,
        LAAFactory $LAAFactory,
        EvidenceUploadHandler $evidenceUploadHandler
    ): RedirectResponse {
        $learningActivityActing = $LAAFactory->createLAA($request->all());

        if ($request->hasFile('evidence')) {
            $evidenceUploadHandler->process($request, $learningActivityActing);
        }

        return $this->redirector->route('process-acting')->with('success', __('activity.saved-successfully'));
    }

    public function update(
        ActingUpdateRequest $request,
        LearningActivityActing $learningActivityActing,
        EvidenceUploadHandler $evidenceUploadHandler,
        LAAUpdater $LAAUpdater
    ): RedirectResponse {
        if ($request->hasFile('evidence')) {
            $evidenceUploadHandler->process($request, $learningActivityActing);
        }

        $LAAUpdater->update($learningActivityActing, $request->all());

        return $this->redirector->route('process-acting')->with('success', __('activity.saved-successfully'));
    }

    public function delete(LearningActivityActing $learningActivityActing): RedirectResponse
    {
        $this->learningActivityActingRepository->delete($learningActivityActing);

        return $this->redirector->route('process-acting');
    }
}
