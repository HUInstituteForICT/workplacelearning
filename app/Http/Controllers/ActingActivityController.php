<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\LearningActivity\ActingCreateRequest;
use App\Http\Requests\LearningActivity\ActingUpdateRequest;
use App\Interfaces\ProgressRegistrySystemServiceInterface;
use App\LearningActivityActing;
use App\Reflection\Models\ActivityReflection;
//use App\Repository\Eloquent\LearningActivityActingRepository;
//use App\Repository\Eloquent\SavedLearningItemRepository;
//use App\SavedLearningItem;
use App\Services\AvailableActingEntitiesFetcher;
use App\Services\CurrentUserResolver;
use App\Services\EvidenceUploadHandler;
use App\Services\Factories\LAAFactory;
use App\Services\LAAUpdater;
use App\Services\LearningActivityActingExportBuilder;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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


    //removed LearningActivityActingRepository

    /**
     * @var ProgressRegistrySystemServiceInterface
     */
    private $ProgressRegistrySystemService;

    /**
     * @var Session
     */
    private $session;

//    /**
//     * @var SavedLearningItemRepository
//     */
//    private $savedLearningItemRepository;

    public function __construct(
        Redirector $redirector,
        CurrentUserResolver $currentUserResolver,
        ProgressRegistrySystemServiceInterface $ProgressRegistrySystemServiceInterface,
//        SavedLearningItemRepository $savedLearningItemRepository,
        Session $session
    ) {
        $this->redirector = $redirector;
        $this->currentUserResolver = $currentUserResolver;
        $this->ProgressRegistrySystemService = $ProgressRegistrySystemServiceInterface;
//        $this->savedLearningItemRepository = $savedLearningItemRepository;
        $this->session = $session;
    }

    public function show(
        AvailableActingEntitiesFetcher $availableActingEntitiesFetcher,
        LearningActivityActingExportBuilder $exportBuilder
    ) {
        $student = $this->currentUserResolver->getCurrentUser();

        $activitiesJson = $exportBuilder->getJson($this->ProgressRegistrySystemService->getLearningActivityActingForStudent($student),
            1);

        $exportTranslatedFieldMapping = $exportBuilder->getFieldLanguageMapping();

        $orderedReflectionTypes = $student->orderReflectionTypes(ActivityReflection::TYPES);

        return view('pages.acting.activity', $availableActingEntitiesFetcher->getEntities())
            ->with('activitiesJson', $activitiesJson)
            ->with('exportTranslatedFieldMapping', json_encode($exportTranslatedFieldMapping))
            ->with('workplacelearningperiod', $student->getCurrentWorkplaceLearningPeriod())
            ->with('orderedReflectionTypes', $orderedReflectionTypes)
            ->with('reflectionSettings', $student->reflectionSettings());
    }

    public function edit(
        LearningActivityActing $learningActivityActing,
        AvailableActingEntitiesFetcher $availableActingEntitiesFetcher,
        Request $request
    ) {
        $referrer = $request->header('referer');
        $redirect = route('progress-acting');
        if ($referrer && $referrer === route('progress-acting')) {
            $redirect = route('progress-acting');
        }
        $this->session->put('acting.activity.edit.referrer', $redirect);

        $student = $this->currentUserResolver->getCurrentUser();

        $orderedReflectionTypes = $student->orderReflectionTypes(ActivityReflection::TYPES);

        return view('pages.acting.activity-edit', $availableActingEntitiesFetcher->getEntities())
            ->with('activity', $learningActivityActing)
            ->with('orderedReflectionTypes', $orderedReflectionTypes)
            ->with('reflectionSettings', $student->reflectionSettings());
    }

    public function progress(LearningActivityActingExportBuilder $exportBuilder)
    {
        $student = $this->currentUserResolver->getCurrentUser();

        $activitiesJson = $exportBuilder->getJson(
            $this->ProgressRegistrySystemService->getLearningActivityActingForStudent($student),
            null
        );

        $exportTranslatedFieldMapping = $exportBuilder->getFieldLanguageMapping();

        return view('pages.acting.progress')
            ->with('activitiesJson', $activitiesJson)
            ->with('exportTranslatedFieldMapping', json_encode($exportTranslatedFieldMapping));
    }

    /**
     * @throws \Exception
     */
    public function create(
        ActingCreateRequest $request,
        LAAFactory $LAAFactory,
        EvidenceUploadHandler $evidenceUploadHandler
    ) {
        $learningActivityActing = $LAAFactory->createLAA($request->all());

        if ($request->hasFile('evidence')) {
            $evidenceUploadHandler->process($request, $learningActivityActing);
        }

        session()->flash('success', __('activity.saved-successfully'));
        $url = route('progress-acting');

        if ($request->acceptsJson()) {
            return response()->json([
                'status' => 'success',
                'url'    => $url,
            ]);
        }

        return redirect($url);
    }

    /**
     * @throws \Exception
     */
    public function update(
        ActingUpdateRequest $request,
        LearningActivityActing $learningActivityActing,
        EvidenceUploadHandler $evidenceUploadHandler,
        LAAUpdater $LAAUpdater
    ) {
        if ($request->hasFile('evidence')) {
            $evidenceUploadHandler->process($request, $learningActivityActing);
        }

        $LAAUpdater->update($learningActivityActing, $request->all());

        session()->flash('success', __('activity.saved-successfully'));
        $url = route('progress-acting');
        if ($this->session->has('acting.activity.edit.referrer')) {
            $url = $this->session->remove('acting.activity.edit.referrer');
        }

        if ($request->acceptsJson()) {
            return response()->json([
                'status' => 'success',
                'url'    => $url,
            ]);
        }

        return redirect($url);
    }

    public function delete(LearningActivityActing $learningActivityActing): RedirectResponse
    {
        $this->ProgressRegistrySystemService->deleteLearningActivityActing($learningActivityActing);

        return $this->redirector->route('progress-acting');
    }

    public function save(LearningActivityActing $learningActivityActing, Request $request): RedirectResponse
    {
        $savedLearningItem = $learningActivityActing->bookmark();
        $this->ProgressRegistrySystemService->saveSavedLearningItem($savedLearningItem);

        $request->session()->flash('success', __('saved_learning_items.saved-succesfully'));

        return $this->redirector->route('progress-acting');
    }
}
