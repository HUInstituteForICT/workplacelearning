<?php

declare(strict_types=1);

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Services\CurrentUserResolver;
use App\Student;
use App\WorkplaceLearningPeriod;
use App\Folder;
use App\FolderComment;
use App\SavedLearningItem;
use App\Tips\EvaluatedTip;
use App\Repository\Eloquent\FolderRepository;
use App\Repository\Eloquent\FolderCommentRepository;
use App\Repository\Eloquent\SavedLearningItemRepository;
use App\Repository\Eloquent\TipRepository;
use App\Tips\Services\TipEvaluator;
use App\Analysis\Producing\ProducingAnalysisCollector;

class StudentDetails extends Controller
{
    /**
     * @var CurrentUserResolver
     */
    private $currentUserResolver;

    /**
     * @var FolderRepository
     */
    private $folderRepository;

    /**
     * @var FolderCommentRepository
     */
    private $folderCommentRepository;

    /**
     * @var SavedLearningItemRepository
     */
    private $savedLearningItemRepository;

     /**
     * @var TipEvaluator
     */
    private $tipEvaluator;

    public function __construct(
        CurrentUserResolver $currentUserResolver,
        SavedLearningItemRepository $savedLearningItemRepository,
        TipRepository $tipRepository,
        FolderRepository $folderRepository,
        FolderCommentRepository $folderCommentRepository)
    {
        $this->currentUserResolver = $currentUserResolver;
        $this->savedLearningItemRepository = $savedLearningItemRepository;
        $this->tipRepository = $tipRepository;
        $this->folderRepository = $folderRepository;
        $this->folderCommentRepository = $folderCommentRepository;
       
    }

    public function __invoke(Student $student, TipEvaluator $evaluator, ProducingAnalysisCollector $producingAnalysisCollector)
    {
        $teacher = $this->currentUserResolver->getCurrentUser();
        
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
        $folders = $this->folderRepository->findByTeacherId($teacher);
        $tips = $this->tipRepository->all();
        $sli = $this->savedLearningItemRepository->findByStudentnr($student->student_id);

        $evaluatedTips = [];
        foreach ($tips as $tip) {
            $evaluatedTips[$tip->id] = $evaluator->evaluateForChosenStudent($tip, $student);
        }

        $allFolderComments = $this->folderCommentRepository->all();
        $period = $student->getCurrentWorkplaceLearningPeriod();


        return view('pages.teacher.student_details')
            ->with('student', $student)
            ->with('workplace', $workplace)
            ->with('folders', $folders)
            ->with('evaluatedTips', $evaluatedTips)
            ->with('sli', $sli)
            ->with('allFolderComments', $allFolderComments)
            ->with('numdays', $producingAnalysisCollector->getFullWorkingDaysOfStudent($student))
            ->with('period', $period);
        


    }
}
