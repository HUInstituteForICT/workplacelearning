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
     * @var TipEvaluator
     */
    private $tipEvaluator;

    public function __construct(
        CurrentUserResolver $currentUserResolver,
        TipRepository $tipRepository)
    {
        $this->currentUserResolver = $currentUserResolver;
        $this->tipRepository = $tipRepository;
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
        $tips = $this->tipRepository->all();
        $learningperiod = $student->getCurrentWorkplaceLearningPeriod();
        
        $evaluatedTips = [];
        foreach ($tips as $tip) {
            $evaluatedTips[$tip->id] = $evaluator->evaluateForChosenStudent($tip, $student);
        }
        
        return view('pages.teacher.student_details')
            ->with('student', $student)
            ->with('workplace', $workplace)
            ->with('evaluatedTips', $evaluatedTips)
            ->with('numdays', $producingAnalysisCollector->getFullWorkingDaysOfStudent($student))
            ->with('learningperiod', $learningperiod);
    }
}
