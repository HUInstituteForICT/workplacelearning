<?php

namespace App\Http\Controllers;

use App\Http\Requests\LearningActivity\ActingCreateRequest;
use App\Http\Requests\LearningActivity\ActingUpdateRequest;
use App\LearningActivityActing;
use App\LearningActivityActingExportBuilder;
use App\Services\EvidenceUploadHandler;
use App\Services\LAAFactory;
use App\Services\LAAUpdater;
use App\Student;
use Illuminate\Routing\Controller;

class ActingActivityController extends Controller
{
    public function show(Student $student)
    {
        $resourcePersons = $student->currentCohort()->resourcePersons()->get()->merge(
            $student->getCurrentWorkplaceLearningPeriod()->getResourcePersons()
        );

        $timeslots = $student->currentCohort()->timeslots()->get()->merge(
            $student->getCurrentWorkplaceLearningPeriod()->getTimeslots()
        );

        $exportBuilder = new LearningActivityActingExportBuilder($student->getCurrentWorkplaceLearningPeriod()->learningActivityActing()
            ->with('timeslot', 'resourcePerson', 'resourceMaterial', 'learningGoal', 'competence')
            ->take(8)
            ->orderBy('date', 'DESC')
            ->orderBy('laa_id', 'DESC')
            ->get());

        $activitiesJson = $exportBuilder->getJson();

        $exportTranslatedFieldMapping = $exportBuilder->getFieldLanguageMapping(app()->make('translator'));

        return view('pages.acting.activity')
            ->with('competenceDescription', $student->currentCohort()->competenceDescription)
            ->with('timeslots', $timeslots)
            ->with('resPersons', $resourcePersons)
            ->with('resMaterials', $student->getCurrentWorkplaceLearningPeriod()->getResourceMaterials())
            ->with('learningGoals', $student->getCurrentWorkplaceLearningPeriod()->getLearningGoals())
            ->with('competencies', $student->currentCohort()->competencies()->get())
            ->with('activities', $student->getCurrentWorkplaceLearningPeriod()->getLastActivity(8))
            ->with('activitiesJson', $activitiesJson)
            ->with('exportTranslatedFieldMapping', json_encode($exportTranslatedFieldMapping))
            ->with('workplacelearningperiod', $student->getCurrentWorkplaceLearningPeriod());
    }

    public function edit(LearningActivityActing $learningActivityActing, Student $student)
    {
        $resourcePersons = $student->currentCohort()->resourcePersons()->get()->merge(
            $student->getCurrentWorkplaceLearningPeriod()->getResourcePersons()
        );

        $timeslots = $student->currentCohort()->timeslots()->get()->merge(
            $student->getCurrentWorkplaceLearningPeriod()->getTimeslots()
        );

        return view('pages.acting.activity-edit')
            ->with('activity', $learningActivityActing)
            ->with('timeslots', $timeslots)
            ->with('resPersons', $resourcePersons)
            ->with('resMaterials', $student->getCurrentWorkplaceLearningPeriod()->getResourceMaterials())
            ->with('learningGoals', $student->getCurrentWorkplaceLearningPeriod()->getLearningGoals())
            ->with('competencies', $student->currentCohort()->competencies()->get());
    }

    public function progress(Student $student)
    {
        $exportBuilder = new LearningActivityActingExportBuilder($student->getCurrentWorkplaceLearningPeriod()->learningActivityActing()
            ->with('timeslot', 'resourcePerson', 'resourceMaterial', 'learningGoal', 'competence')
            ->orderBy('date', 'DESC')
            ->get());

        $activitiesJson = $exportBuilder->getJson();

        $exportTranslatedFieldMapping = $exportBuilder->getFieldLanguageMapping(app()->make('translator'));

        return view('pages.acting.progress')
            ->with('activitiesJson', $activitiesJson)
            ->with('exportTranslatedFieldMapping', json_encode($exportTranslatedFieldMapping));
    }

    public function create(
        ActingCreateRequest $request,
        LAAFactory $LAAFactory,
        EvidenceUploadHandler $evidenceUploadHandler
    ) {
        $learningActivityActing = $LAAFactory->createLAA($request->all());

        if ($request->hasFile('evidence')) {
            $evidenceUploadHandler->process($request, $learningActivityActing);
        }

        return redirect()->route('process-acting')->with('success', __('activity.saved-successfully'));
    }

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

        return redirect()->route('process-acting')->with('success', __('activity.saved-successfully'));
    }

    public function delete(LearningActivityActing $learningActivityActing)
    {
        $learningActivityActing->competence()->detach($learningActivityActing->competence);
        $learningActivityActing->delete();

        return redirect()->route('process-acting');
    }
}
