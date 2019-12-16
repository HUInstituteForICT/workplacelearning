<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Repository\Eloquent\SavedLearningItemRepository;
use App\Repository\Eloquent\TipRepository;
use App\Repository\Eloquent\FolderRepository;
use App\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Services\CurrentUserResolver;
use App\SavedLearningItem;
use App\Tips\EvaluatedTip;
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

    /**
     * @var FolderRepository
     */
    private $folderRepository;


    public function __construct(
        CurrentUserResolver $currentUserResolver,
        SavedLearningItemRepository $savedLearningItemRepository,
        TipRepository $tipRepository,
        FolderRepository $folderRepository
    ) {
        $this->currentUserResolver = $currentUserResolver;
        $this->savedLearningItemRepository = $savedLearningItemRepository;
        $this->tipRepository = $tipRepository;
        $this->folderRepository = $folderRepository;
        

    }

    public function index(TipEvaluator $evaluator)
    {
        $student = $this->currentUserResolver->getCurrentUser();
        $tips = $this->tipRepository->all();
        $sli = $this->savedLearningItemRepository->findByStudentnr($student->student_id);
        $folders = $this->folderRepository->all();

        $evaluatedTips = [];
        foreach ($tips as $tip) {
            $evaluatedTips[$tip->id] = $evaluator->evaluate($tip);
        }

        return view('pages.saved-items', [
            'student' => $student,
            'sli' => $sli,
            'tips' => $tips,
            'folders'   =>   $folders,
            'evaluatedTips' => $evaluatedTips,
        ]);
    }

    public function createItem($category, $item_id)
    {
        $student = $this->currentUserResolver->getCurrentUser();

        if ($student->educationProgram->educationprogramType->isActing()) {
            $url = route('home-acting');
        } else {
            $url = route('home-producing');
        }

        $itemExists = $this->savedLearningItemRepository->itemExists($category, $item_id, $student->student_id);
        if (!$itemExists) {
            $savedLearningItem = new SavedLearningItem();
            $savedLearningItem->category = $category;
            $savedLearningItem->item_id = $item_id;
            $savedLearningItem->student_id = $student->student_id;
            $this->savedLearningItemRepository->save($savedLearningItem);

            session()->flash('success', __('saved_learning_items.saved-succesfully'));
        }

        return redirect($url);
    }

    public function updateFolder(Request $request)
    {
        $savedLearningItem = SavedLearningItem::find($request['sli_id']);
        $folderId = $request['chooseFolder'];
        $savedLearningItem->folder = $folderId;
        $savedLearningItem->save();

        return redirect('saved-learning-items');
    }
}
