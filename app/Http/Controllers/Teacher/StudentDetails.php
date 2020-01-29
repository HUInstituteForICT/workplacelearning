<?php

declare(strict_types=1);

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Services\CurrentUserResolver;
use App\Student;
use App\WorkplaceLearningPeriod;
use App\Folder;
use App\SavedLearningItem;
use App\Tips\EvaluatedTip;
use App\Repository\Eloquent\TipRepository;
use App\Repository\Eloquent\ResourcePersonRepository;
use App\Repository\Eloquent\LearningActivityProducingRepository;
use App\Repository\Eloquent\LearningActivityActingRepository;
use App\Repository\Eloquent\CategoryRepository;
use App\Repository\Eloquent\SavedLearningItemRepository;
use App\Tips\Services\TipEvaluator;
use App\Analysis\Producing\ProducingAnalysisCollector;

class StudentDetails extends Controller
{
    /**
     * @var CurrentUserResolver
     */
    private $currentUserResolver;

     /**
     * @var TipEvaluator
     */
    private $tipEvaluator;

     /**
     * @var LearningActivityProducingRepository
     */
    private $learningActivityProducingRepository;

    /**
     * @var LearningActivityActingRepository
     */
    private $learningActivityActingRepository;

     /**
     * @var SavedLearningItemRepository
     */
    private $savedLearningItemRepository;

    /**
     * @var ResourcePersonRepository
     */
    private $resourcePersonRepository;

     /**
     * @var CategoryRepository
     */
    private $categoryRepository;

    public function __construct(
        CurrentUserResolver $currentUserResolver,
        TipRepository $tipRepository,
        SavedLearningItemRepository $savedLearningItemRepository,
        LearningActivityProducingRepository $learningActivityProducingRepository,
        LearningActivityActingRepository $learningActivityActingRepository,
        ResourcePersonRepository $resourcePersonRepository,
        CategoryRepository $categoryRepository)
   
    {
        $this->currentUserResolver = $currentUserResolver;
        $this->tipRepository = $tipRepository;
        $this->savedLearningItemRepository = $savedLearningItemRepository;
        $this->learningActivityProducingRepository = $learningActivityProducingRepository;
        $this->learningActivityActingRepository = $learningActivityActingRepository;
        $this->resourcePersonRepository = $resourcePersonRepository;
        $this->categoryRepository = $categoryRepository;
    }

    public function __invoke(Student $student, TipEvaluator $evaluator, ProducingAnalysisCollector $producingAnalysisCollector)
    {
        $teacher = $this->currentUserResolver->getCurrentUser();
        $sli = $this->savedLearningItemRepository->findByStudentnr($student->student_id);
        
        // all wplps of the student where the logged-in teacher is the supervisor.
        $workplaces = $student->getWorkplaceLearningPeriods()
            ->filter(function (WorkplaceLearningPeriod $workplaceLearningPeriod) use ($teacher) {
                return $workplaceLearningPeriod->teacher_id == $teacher->student_id;
            })
            ->map(static function (WorkplaceLearningPeriod $workplaceLearningPeriod) {
                return $workplaceLearningPeriod->workplace;
            })->all();
        
        $currentWorkplace = $student->getCurrentWorkplace();
        $workplace = in_array($currentWorkplace, $workplaces) ? $currentWorkplace : reset($workplaces);
        $tips = $this->tipRepository->all();
        $learningperiod = $student->getCurrentWorkplaceLearningPeriod();
        
        $sharedFolders = $student->folders->filter(function (Folder $folder) {
            return $folder->isShared();
        });

        $persons = $this->resourcePersonRepository->all();
        $categories = $this->categoryRepository->all();
        $associatedActivities = [];
        
        $savedActivitiesIds = $sli->filter(function (SavedLearningItem $item) {
            return $item->category == 'activity';
        })->pluck('item_id')->toArray();

        if ($student->educationProgram->educationprogramType->isActing()) {
            $allActivities = $this->learningActivityActingRepository->getActivitiesForStudent($student);
            foreach($allActivities as $activity) {
                $associatedActivities[$activity->laa_id] = $activity;
            }
        } elseif ($student->educationProgram->educationprogramType->isProducing()) {
            $allActivities = $this->learningActivityProducingRepository->getActivitiesForStudent($student);
            foreach($allActivities as $activity) {
                $associatedActivities[$activity->lap_id] = $activity;
            }
        }
         $resourcepersons = [];
        foreach($persons as $person) {
            $resourcepersons[$person->rp_id] = $person;
        }

        $associatedCategories = [];
        foreach($categories as $category) {
            $associatedCategories[$category->category_id] = $category;
        }

        $evaluatedTips = [];
        foreach ($tips as $tip) {
            $evaluatedTips[$tip->id] = $evaluator->evaluateForChosenStudent($tip, $student);
        }

        return view('pages.teacher.student_details')
            ->with('student', $student)
            ->with('workplace', $workplace)
            ->with('sharedFolders', $sharedFolders)
            ->with('activities', $associatedActivities)
            ->with('evaluatedTips', $evaluatedTips)
            ->with('resourcePerson', $resourcepersons)
            ->with('categories', $associatedCategories)
            ->with('numdays', $producingAnalysisCollector->getFullWorkingDaysOfStudent($student))
            ->with('learningperiod', $learningperiod);
    }
}
