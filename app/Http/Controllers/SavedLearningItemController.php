<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Repository\Eloquent\SavedLearningItemRepository;
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

     /**
     * @var SavedLearningItemRepository
     */
    private $savedLearningItemRepository;


    public function __construct(CurrentUserResolver $currentUserResolver, SavedLearningItemRepository $savedLearningItemRepository)
    {
        $this->currentUserResolver = $currentUserResolver;
        $this->savedLearningItemRepository = $savedLearningItemRepository;

    }

    public function index(Request $request)
    {
        $student = $this->currentUserResolver->getCurrentUser();
        $sli = $this->savedLearningItemRepository->all();

        return view('pages.saved-items', [
            'student'   =>   $student,
            'sli'       =>   $sli,
        ]);
    }
}
