<?php

declare(strict_types=1);

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Services\CurrentUserResolver;
use App\WorkplaceLearningPeriod;
use App\Analysis\Producing\ProducingAnalysisCollector;

class Dashboard extends Controller
{
    /**
     * @var CurrentUserResolver
     */
    private $currentUserResolver;

    public function __construct(CurrentUserResolver $currentUserResolver)
    {
        $this->currentUserResolver = $currentUserResolver;
    }

    public function __invoke(ProducingAnalysisCollector $producingAnalysisCollector)
    {
        $students = $this->currentUserResolver->getCurrentUser()
            ->linkedWorkplaceLearningPeriods
            ->map(static function (WorkplaceLearningPeriod $workplaceLearningPeriod) {
                return $workplaceLearningPeriod->student;
            })->sortByDesc(function($student) {
                return $student->priority()['sharedFoldersWithoutResponse'];
            })->sortByDesc(function($student) {
                return $student->priority()['countDaysFromLastActivity'];
            })->unique('studentnr')->all();

        return view('pages.teacher.dashboard')
            ->with('producingAnalysisCollector', $producingAnalysisCollector)
            ->with('students', $students);
    }
}
