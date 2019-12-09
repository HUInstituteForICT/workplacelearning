<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Repository\Eloquent\SavedLearningItemRepository;
use App\Repository\Eloquent\TipRepository;
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

         /**
     * @var TipRepository
     */
    private $tipRepository;


    public function __construct(CurrentUserResolver $currentUserResolver, SavedLearningItemRepository $savedLearningItemRepository, TipRepository $tipRepository)
    {
        $this->currentUserResolver = $currentUserResolver;
        $this->savedLearningItemRepository = $savedLearningItemRepository;
        $this->tipRepository = $tipRepository;

    }

    public function index(Request $request)
    {
        $student = $this->currentUserResolver->getCurrentUser();
        $tips = $this->tipRepository->all();
        $sli = $this->savedLearningItemRepository->findByStudentnr($student->student_id);

        return view('pages.saved-items', [
            'student'   =>   $student,
            'sli'       =>   $sli,
            'tips'      =>   $tips,
        ]);
    }
}
