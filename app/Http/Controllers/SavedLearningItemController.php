<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Repository\Eloquent\SavedLearningItemRepository;
use App\Repository\Eloquent\TipRepository;
use App\Tips\EvaluatedTip;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Services\CurrentUserResolver;
use App\Tips\Services\TipEvaluator;

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

    public function __construct(
        CurrentUserResolver $currentUserResolver,
        SavedLearningItemRepository $savedLearningItemRepository,
        TipRepository $tipRepository
    ) {
        $this->currentUserResolver = $currentUserResolver;
        $this->savedLearningItemRepository = $savedLearningItemRepository;
        $this->tipRepository = $tipRepository;

    }

    public function index(TipEvaluator $evaluator)
    {
        $student = $this->currentUserResolver->getCurrentUser();
        $tips = $this->tipRepository->all();
        $sli = $this->savedLearningItemRepository->findByStudentnr($student->student_id);

        $evaluatedTips = [];
        foreach ($tips as $tip) {
            $evaluatedTips[] = $evaluator->evaluate($tip);
        }

        return view('pages.saved-items', [
            'student' => $student,
            'sli' => $sli,
            'tips' => $tips,
            'evaluatedTips' => $evaluatedTips,
        ]);
    }
}
