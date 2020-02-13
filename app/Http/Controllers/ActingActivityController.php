<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\LearningActivity\ActingCreateRequest;
use App\Http\Requests\LearningActivity\ActingUpdateRequest;
use App\LearningActivityActing;
use App\SavedLearningItem;
use App\Reflection\Models\ActivityReflection;
use App\Repository\Eloquent\LearningActivityActingRepository;
use App\Repository\Eloquent\SavedLearningItemRepository;
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
    /**
     * @var LearningActivityActingRepository
     */
    private $learningActivityActingRepository;
    /**
     * @var Session
     */
    private $session;
     /**
     * @var SavedLearningItemRepository
     */
    private $savedLearningItemRepository;

    public function __construct(
        Redirector $redirector,
        CurrentUserResolver $currentUserResolver,
        LearningActivityActingRepository $learningActivityActingRepository,
        SavedLearningItemRepository $savedLearningItemRepository,
        Session $session
    ) {
        $this->redirector = $redirector;
        $this->currentUserResolver = $currentUserResolver;
        $this->learningActivityActingRepository = $learningActivityActingRepository;
        $this->savedLearningItemRepository = $savedLearningItemRepository;
        $this->session = $session;
    }

    public function show(
        AvailableActingEntitiesFetcher $availableActingEntitiesFetcher,
        LearningActivityActingExportBuilder $exportBuilder
    ) {
        $student = $this->currentUserResolver->getCurrentUser();

        $activitiesJson = $exportBuilder->getJson($this->learningActivityActingRepository->getActivitiesForStudent($student),
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
        $redirect = route('process-acting');
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
            $this->learningActivityActingRepository->getActivitiesForStudent($student),
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
        $url = route('process-acting');

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
        $url = route('process-acting');
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
        $this->learningActivityActingRepository->delete($learningActivityActing);

        return $this->redirector->route('process-acting');
    }

    public function save(LearningActivityActing $learningActivityActing): RedirectResponse
    {
        $student = $this->currentUserResolver->getCurrentUser();
        $url = route('process-producing');

            $savedLearningItem = new SavedLearningItem();
            $savedLearningItem->category = 'activity';
            $savedLearningItem->item_id = $learningActivityActing->laa_id;
            $savedLearningItem->student_id = $student->student_id;
            $savedLearningItem->created_at = date('Y-m-d H:i:s');
            $savedLearningItem->updated_at = date('Y-m-d H:i:s');
            $this->savedLearningItemRepository->save($savedLearningItem);

            session()->flash('success', __('saved_learning_items.saved-succesfully'));

        return redirect($url);
    }
}
