<?php

declare(strict_types=1);

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Services\CurrentUserResolver;
use App\Student;
use App\WorkplaceLearningPeriod;

class StudentDetails extends Controller
{
    /**
     * @var CurrentUserResolver
     */
    private $currentUserResolver;

    public function __construct(CurrentUserResolver $currentUserResolver)
    {
        $this->currentUserResolver = $currentUserResolver;
    }

    public function __invoke(Student $student)
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
        
        return view('pages.teacher.student_details')
            ->with('student', $student)
            ->with('workplace', $workplace);
    }
}
