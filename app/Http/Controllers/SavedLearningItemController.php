<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Folder;
use App\Interfaces\LearningSystemServiceInterface;
use App\Interfaces\ProgressRegistrySystemServiceInterface;
//use App\Repository\Eloquent\CategoryRepository;
use App\Interfaces\StudentSystemServiceInterface;
use App\Repository\Eloquent\FolderRepository;
use App\Repository\Eloquent\LearningActivityActingRepository;
//use App\Repository\Eloquent\LearningActivityProducingRepository;
//use App\Repository\Eloquent\ResourcePersonRepository;
//use App\Repository\Eloquent\SavedLearningItemRepository;
//use App\Repository\Eloquent\TipRepository;
use App\SavedLearningItem;
use App\Services\CurrentUserResolver;
use App\Services\ProgressRegistrySystemServiceImpl;
use App\Tips\Services\TipEvaluator;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use InvalidArgumentException;

class SavedLearningItemController extends Controller
{
    /**
     * @var CurrentUserResolver
     */
    private $currentUserResolver;

//    /**
//     * @var SavedLearningItemRepository
//     */
//    private $savedLearningItemRepository;
//
//    /**
//     * @var TipRepository
//     */
//    private $tipRepository;

//    /**
//     * @var LearningActivityProducingRepository
//     */
//    private $learningActivityProducingRepository;

//    /**
//     * @var LearningActivityActingRepository
//     */
//    private $learningActivityActingRepository;

    /**
     * @var ProgressRegistrySystemServiceInterface
     */
    private $progressRegistrySystemService;

//    /**
////     * @var ResourcePersonRepository
////     */
////    private $resourcePersonRepository;

    /**
     * @var StudentSystemServiceInterface
     */
    private $studentSystemService;

//    /**
//     * @var CategoryRepository
//     */
//    private $categoryRepository;

    /**
     * @var LearningSystemServiceInterface
     */
    private $learningSystemService;


    public function __construct(
        CurrentUserResolver $currentUserResolver,
//        SavedLearningItemRepository $savedLearningItemRepository,
//        TipRepository $tipRepository,
//        LearningActivityProducingRepository $learningActivityProducingRepository,
//        LearningActivityActingRepository $learningActivityActingRepository,
//        ResourcePersonRepository $resourcePersonRepository,
        LearningSystemServiceInterface $learningSystemService,
        StudentSystemServiceInterface $studentSystemService,
//        CategoryRepository $categoryRepository,
        ProgressRegistrySystemServiceInterface $progressRegistrySystemService
    ) {
        $this->currentUserResolver = $currentUserResolver;
//        $this->savedLearningItemRepository = $savedLearningItemRepository;
//        $this->tipRepository = $tipRepository;
//        $this->learningActivityProducingRepository = $learningActivityProducingRepository;
//        $this->learningActivityActingRepository = $learningActivityActingRepository;
//        $this->resourcePersonRepository = $resourcePersonRepository;
        $this->studentSystemService = $studentSystemService;
        $this->learningSystemService = $learningSystemService;
//        $this->categoryRepository = $categoryRepository;
        $this->progressRegistrySystemService = $progressRegistrySystemService;
    }

    public function index(TipEvaluator $evaluator)
    {
        $student = $this->currentUserResolver->getCurrentUser();
        $tips = $this->progressRegistrySystemService->getAllTips();
//        $sli = $this->savedLearningItemRepository->findByStudentnr($student->student_id);
        $sli = $this->progressRegistrySystemService->getSavedLearningItemByStudentId($student->student_id);

//        $persons = $this->resourcePersonRepository->all();
        $persons = $this->studentSystemService->getAllResourcePersons();
        //$categories = $this->categoryRepository->all();
        $categories = $this->learningSystemService->getAllCategories();

        $associatedActivities = [];
        $savedActivitiesIds = $sli->filter(function (SavedLearningItem $item) {
            return $item->category === 'activity';
        })->pluck('item_id')->toArray();

        //TODO StudentSystemService -> LearningsystemService getEducationProgramById()
        if ($student->educationProgram->educationprogramType->isActing()) {
            //TODO ProgressRegistrySystemService ->
//            $allActivities = $this->learningActivityActingRepository->getActivitiesForStudent($student);
            $allActivities = $this->progressRegistrySystemService->getLearningActivityActingForStudent($student);
            foreach ($allActivities as $activity) {
                $associatedActivities[$activity->laa_id] = $activity;
            }
        //TODO StudentSystemService -> LearningsystemService getEducationProgramById()
        } elseif ($student->educationProgram->educationprogramType->isProducing()) {
            //TODO StudentSystemService(studentId) -> ProgressRegistrySystemService getSavedLearningItemsByStudentId()
//            $allActivities = $this->learningActivityProducingRepository->getActivitiesForStudent($student);
            $allActivities = $this->progressRegistrySystemService->getActivitiesProducingForStudent($student);
            foreach ($allActivities as $activity) {
                $associatedActivities[$activity->lap_id] = $activity;
            }
        }

        $resourcepersons = [];
        foreach ($persons as $person) {
            $resourcepersons[$person->rp_id] = $person;
        }

        $associatedCategories = [];
        foreach ($categories as $category) {
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


//        $itemExists = $this->savedLearningItemRepository->itemExists($category, $item_id, $student->student_id);
        $itemExists = $this->progressRegistrySystemService->savedLearningItemExists($category, $item_id, $student->student_id);
        if (!$itemExists) {
            $savedLearningItem = new SavedLearningItem();
            $savedLearningItem->category = $category;
            $savedLearningItem->item_id = $item_id;
            $savedLearningItem->student_id = $student->student_id;
            $savedLearningItem->created_at = date('Y-m-d H:i:s');
            $savedLearningItem->updated_at = date('Y-m-d H:i:s');
//            $this->savedLearningItemRepository->save($savedLearningItem);
            $this->progressRegistrySystemService->saveSavedLearningItem($savedLearningItem);

            $request->session()->flash('success', __('saved_learning_items.saved-succesfully'));
        }

        return new RedirectResponse($url);
    }

    /**
     * @throws AuthorizationException
     */
    public function delete(SavedLearningItem $sli, CurrentUserResolver $currentUserResolver)
    {
        if (!$sli->student->is($currentUserResolver->getCurrentUser())) {
            throw new AuthorizationException('This is not your SLI');
        }

//        $this->savedLearningItemRepository->delete($sli);
        $this->progressRegistrySystemService->deleteSavedLearningItem($sli);

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

        if (($folderId = $request->get('chooseFolder')) === null) {
            throw new InvalidArgumentException('No folder id');
        }

        /** @var Folder $folder */
        //TODO ProgressRegistrySystemService -> FolderSystemService
        $folder = $folderRepository->findById($folderId);

        // TODO needs further implementation
//        $folder = $this->progressRegistryService->findFolderById($folderId);

        if (($sliId = $request->get('sli_id')) === null) {
            throw new InvalidArgumentException('No sli id');
        }


        /** @var SavedLearningItem $savedLearningItem */
        $savedLearningItem = $this->progressRegistrySystemService->getSavedLearningItemById($sliId);

        //TODO should this be moved to the service aswell?

        $folder->savedLearningItems()->attach($savedLearningItem);
        $folder->save();

        return redirect('saved-learning-items');
    }
}
