<?php

declare(strict_types=1);

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Services\CurrentUserResolver;
use App\WorkplaceLearningPeriod;

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

    public function __invoke()
    {
        $students = $this->currentUserResolver->getCurrentUser()
            ->linkedWorkplaceLearningPeriods
            ->map(static function (WorkplaceLearningPeriod $workplaceLearningPeriod) {
                return $workplaceLearningPeriod->student;
            })->unique('studentnr')->all();

        return view('pages.teacher.dashboard')->with('students', $students);
    }
}
