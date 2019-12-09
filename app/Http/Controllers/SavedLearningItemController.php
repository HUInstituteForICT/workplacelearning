<?php

declare(strict_types=1);

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Repository\Eloquent\SavedLearningItemRepository;
use App\SavedLearningItem;
use Illuminate\Http\Request;
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
}