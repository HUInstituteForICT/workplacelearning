<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Folder;
use App\Http\Controllers\Controller;
use App\LearningActivityActing;
use App\Repository\Eloquent\FolderRepository;
use App\Repository\Eloquent\SavedLearningItemRepository;
use App\Repository\Eloquent\TipRepository;
use App\Repository\Eloquent\ResourcePersonRepository;
use App\Repository\Eloquent\CategoryRepository;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use App\Services\CurrentUserResolver;
use App\SavedLearningItem;
use App\Tips\EvaluatedTip;
use App\Tips\Services\TipEvaluator;
use App\Repository\Eloquent\LearningActivityProducingRepository;
use App\Repository\Eloquent\LearningActivityActingRepository;
use App\LearningActivityProducing;

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
     * @var LearningActivityProducingRepository
     */
    private $learningActivityProducingRepository;

    /**
     * @var LearningActivityActingRepository
     */
    private $learningActivityActingRepository;

    /**
     * @var ResourcePersonRepository
     */
    private $resourcePersonRepository;

     /**
     * @var CategoryRepository
     */
    private $categoryRepository;


    public function __construct(
        CurrentUserResolver $currentUserResolver,
        SavedLearningItemRepository $savedLearningItemRepository,
        TipRepository $tipRepository,
        LearningActivityProducingRepository $learningActivityProducingRepository,
        LearningActivityActingRepository $learningActivityActingRepository,
        ResourcePersonRepository $resourcePersonRepository,
        CategoryRepository $categoryRepository
    ) {
        $this->currentUserResolver = $currentUserResolver;
        $this->savedLearningItemRepository = $savedLearningItemRepository;
        $this->tipRepository = $tipRepository;
        $this->learningActivityProducingRepository = $learningActivityProducingRepository;
        $this->learningActivityActingRepository = $learningActivityActingRepository;
        $this->resourcePersonRepository = $resourcePersonRepository;
        $this->categoryRepository = $categoryRepository;
    }

    public function index(TipEvaluator $evaluator)
    {
        $student = $this->currentUserResolver->getCurrentUser();
        $tips = $this->tipRepository->all();
        $sli = $this->savedLearningItemRepository->findByStudentnr($student->student_id);
        $persons = $this->resourcePersonRepository->all();
        $categories = $this->categoryRepository->all();
        $associatedActivities = [];
        $savedActivitiesIds = $sli->filter(function (SavedLearningItem $item) {
            return $item->category === 'activity';
        })->pluck('item_id')->toArray();

        if ($student->educationProgram->educationprogramType->isActing()) {
            $allActivities = $this->learningActivityActingRepository->getActivitiesForStudent($student);
            foreach($allActivities as $activity) {
                $associatedActivities[$activity->laa_id] = $activity;
            }
        } elseif ($student->educationProgram->educationprogramType->isProducing()) {
            $allActivities = $this->learningActivityProducingRepository->getActivitiesForStudent($student);
            foreach($allActivities as $activity) {
                $associatedActivities[$activity->lap_id] = $activity;
            }
        }

        $resourcepersons = [];
        foreach($persons as $person) {
            $resourcepersons[$person->rp_id] = $person;
        }

        $associatedCategories = [];
        foreach($categories as $category) {
            $associatedCategories[$category->category_id] = $category;
        }

        $evaluatedTips = [];
        foreach ($tips as $tip) {
            $evaluatedTips[$tip->id] = $evaluator->evaluateForChosenStudent($tip, $student);
        }

        return view('pages.saved-items')
            ->with('student', $student)
            ->with('sli', $sli)
            ->with('activities', $associatedActivities)
            ->with('evaluatedTips', $evaluatedTips)
            ->with('resourcePerson', $resourcepersons)
            ->with('categories', $associatedCategories);
    }

    public function createItem(string $category, int $item_id, Request $request)
    {
        $student = $this->currentUserResolver->getCurrentUser();

        if ($student->educationProgram->educationprogramType->isActing()) {
            $url = route('home-acting');
        } else {
            $url = route('home-producing');
        }


        if ($previous = $request->session()->previousUrl()) {
            $url = $previous;
        }

        $itemExists = $this->savedLearningItemRepository->itemExists($category, $item_id, $student->student_id);
        if (!$itemExists) {
            $savedLearningItem = new SavedLearningItem();
            $savedLearningItem->category = $category;
            $savedLearningItem->item_id = $item_id;
            $savedLearningItem->student_id = $student->student_id;
            $savedLearningItem->created_at = date('Y-m-d H:i:s');
            $savedLearningItem->updated_at = date('Y-m-d H:i:s');
            $this->savedLearningItemRepository->save($savedLearningItem);

            session()->flash('success', __('saved_learning_items.saved-succesfully'));
        }

        return redirect($url);
    }

    /**
     * @throws AuthorizationException
     */
    public function delete(SavedLearningItem $sli, CurrentUserResolver $currentUserResolver)
    {
        if(!$sli->student->is($currentUserResolver->getCurrentUser())) {
            throw new AuthorizationException('This is not your SLI');
        }

        $this->savedLearningItemRepository->delete($sli);
        return redirect('saved-learning-items');
    }

    public function removeItemFromFolder(SavedLearningItem $sli, Folder $folder)
    {
        $sli->folders()->detach($folder);
        $sli->save();
        return redirect('folders');
    }

    public function addItemToFolder(Request $request, FolderRepository $folderRepository)
    {

        if(($folderId = $request->get('chooseFolder')) === null) {
            throw new \InvalidArgumentException('No folder id');
        }

        /** @var Folder $folder */
        $folder = $folderRepository->findById($folderId);

        if(($sliId = $request->get('sli_id')) === null) {
            throw new \InvalidArgumentException('No sli id');
        }


        /** @var SavedLearningItem $savedLearningItem */
        $savedLearningItem =  $this->savedLearningItemRepository->findById($sliId);

        $folder->savedLearningItems()->attach($savedLearningItem);
        $folder->save();

        return redirect('saved-learning-items');
    }
}
