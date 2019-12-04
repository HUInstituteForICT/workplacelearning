<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Repository\Eloquent\StudentRepository;
use App\Repository\Eloquent\WorkplaceLearningPeriodRepository;
use App\Repository\Eloquent\WorkplaceRepository;
use App\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Services\CurrentUserResolver;

class SavedLearningItemController extends Controller
{
    /**
     * @var CurrentUserResolver
     */
    private $currentUserResolver;


    public function __construct(CurrentUserResolver $currentUserResolver)
    {
        $this->currentUserResolver = $currentUserResolver;
    }

    public function __invoke(Request $request)
    {
        $student = $this->currentUserResolver->getCurrentUser();

        return view('pages.saved-items')->with('student', $student);
    }
}
