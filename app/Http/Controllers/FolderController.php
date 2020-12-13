<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Folder;
use App\FolderComment;
use App\Interfaces\FolderSystemServiceInterface;
use App\Interfaces\LearningSystemServiceInterface;
use App\Interfaces\ProgressRegistrySystemServiceInterface;
use App\Interfaces\StudentSystemServiceInterface;
use App\Notifications\FolderFeedbackGiven;
use App\Notifications\FolderSharedWithTeacher;
//use App\Repository\Eloquent\CategoryRepository;
//use App\Repository\Eloquent\FolderCommentRepository;
//use App\Repository\Eloquent\FolderRepository;
//use App\Repository\Eloquent\LearningActivityActingRepository;
//use App\Repository\Eloquent\LearningActivityProducingRepository;
//use App\Repository\Eloquent\ResourcePersonRepository;
//use App\Repository\Eloquent\SavedLearningItemRepository;
use App\Repository\Eloquent\TipRepository;
use App\SavedLearningItem;
use App\Services\CurrentUserResolver;
use App\Tips\Services\TipEvaluator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class FolderController extends Controller
{

    /**
     * @var CurrentUserResolver
     */
    private $currentUserResolver;

//    /**
//     * @var FolderRepository
//     */
//    private $folderRepository;

    /**
     * @var FolderSystemServiceInterface
     */
    private $folderSystemService;


//    /**
//     * @var FolderCommentRepository
//     */
//    private $folderCommentRepository;

//    /**
//     * @var SavedLearningItemRepository
//     */
//    private $savedLearningItemRepository;
//
//    /**
//     * @var TipRepository
//     */
//    private $tipRepository;

//
//    /**
//     * @var LearningActivityProducingRepository
//     */
//    private $learningActivityProducingRepository;
//
//    /**
//     * @var LearningActivityActingRepository
//     */
//    private $learningActivityActingRepository;

    /**
     * @var ProgressRegistrySystemServiceInterface
     */
    private $ProgressRegistrySystemService;


//    /**
//     * @var ResourcePersonRepository
//     */
//    private $resourcePersonRepository;

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
//        FolderRepository $folderRepository,
        FolderSystemServiceInterface $folderSystemService,
//        TipRepository $tipRepository,
//        SavedLearningItemRepository $savedLearningItemRepository,
//        FolderCommentRepository $folderCommentRepository,
//        LearningActivityProducingRepository $learningActivityProducingRepository,
//        LearningActivityActingRepository $learningActivityActingRepository,
//        ResourcePersonRepository $resourcePersonRepository,
        StudentSystemServiceInterface $studentSystemService,
        LearningSystemServiceInterface $learningSystemService,
//        CategoryRepository $categoryRepository,
        ProgressRegistrySystemServiceInterface $ProgressRegistrySystemService
    ) {
        $this->currentUserResolver = $currentUserResolver;
//        $this->folderRepository = $folderRepository;
        $this->folderSystemService = $folderSystemService;
//        $this->folderCommentRepository = $folderCommentRepository;
//        $this->savedLearningItemRepository = $savedLearningItemRepository;
//        $this->tipRepository = $tipRepository;
//        $this->learningActivityProducingRepository = $learningActivityProducingRepository;
//        $this->learningActivityActingRepository = $learningActivityActingRepository;
//        $this->resourcePersonRepository = $resourcePersonRepository;
        $this->studentSystemService = $studentSystemService;
//        $this->categoryRepository = $categoryRepository;
        $this->learningSystemService = $learningSystemService;
        $this-> ProgressRegistrySystemService = $ProgressRegistrySystemService;
    }

    public function index(TipEvaluator $evaluator)
    {
        $student = $this->currentUserResolver->getCurrentUser();
        $tips = $this->ProgressRegistrySystemService->getAllTips();
//        $sli = $this->savedLearningItemRepository->findByStudentnr($student->student_id);

        $sli = $this->ProgressRegistrySystemService->getSavedLearningItemByStudentId($student->student_id);

//        $persons = $this->resourcePersonRepository->all();
        $persons = $this->studentSystemService->getAllResourcePersons();

//        $categories = $this->categoryRepository->all();
        $categories = $this->learningSystemService->getAllCategories();
        $associatedActivities = [];

        $savedActivitiesIds = $sli->filter(function (SavedLearningItem $item) {
            return $item->isActivity();
        })->pluck('item_id')->toArray();

        if ($student->educationProgram->educationprogramType->isActing()) {
//            $allActivities = $this->learningActivityActingRepository->getActivitiesForStudent($student);
            $allActivities = $this->ProgressRegistrySystemService->getLearningActivityActingForStudent($student);
            foreach ($allActivities as $activity) {
                $associatedActivities[$activity->laa_id] = $activity;
            }
        } elseif ($student->educationProgram->educationprogramType->isProducing()) {
//            $allActivities = $this->learningActivityProducingRepository->getActivitiesForStudent($student);
            $allActivities = $this->ProgressRegistrySystemService->getActivitiesProducingForStudent($student);
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

        return view('pages.folders')
            ->with('student', $student)
            ->with('sli', $sli)
            ->with('activities', $associatedActivities)
            ->with('evaluatedTips', $evaluatedTips)
            ->with('resourcePerson', $resourcepersons)
            ->with('categories', $associatedCategories);
    }

    public function create(Request $request)
    {
        $student = $this->currentUserResolver->getCurrentUser();

        $folder = new Folder();
        $folder->title = $request['folder_title'];
        $folder->description = $request['folder_description'] ?? '';
        $folder->student_id = $student->student_id;

        $this->folderSystemService->saveFolder($folder);

        session()->flash('success', __('folder.folder-created'));

        return redirect('folders');
    }

    public function shareFolderWithTeacher(Request $request)
    {
        $student = $this->currentUserResolver->getCurrentUser();
        $folder = Folder::find($request['folder_id']);

        if (!$student->is($folder->student)) {
            return redirect('saved-learning-items')->with('error', __('folder.share-permission'));
        }

        $folderComment = new FolderComment();
        $folderComment->text = $request['folder_comment'];
        $folderComment->folder_id = $request['folder_id'];
        $folderComment->author_id = $student->student_id;
        $this->folderSystemService->saveFolderComment($folderComment);


        /** @var Folder $folder */
        $folder = $this->folderSystemService->findFolderById($request['folder_id']);
        $folder->teacher_id = (int) $request['teacher'];
        $folder->save();

        session()->flash('success', __('folder.folder-shared'));

        $folder->teacher->notify(new FolderSharedWithTeacher($folderComment));

        return redirect('folders');
    }

    public function delete(int $id): RedirectResponse
    {
        $folder = $this->folderSystemService->findFolderById($id, true);
        if (!$folder) {
            throw new \InvalidArgumentException('Unknown folder');
        }
        $student = $this->currentUserResolver->getCurrentUser();

        if (!$student->is($folder->student)) {
            return redirect('saved-learning-items')->with('error', __('folder.no-delete-permission'));
        }

        // remove all items from the folder
        foreach ($folder->savedLearningItems as $sli) {
            $sli->folders()->detach($folder);
            $sli->save();
        }

        if ($folder->trashed()) {
            $this->folderSystemService->restoreFolder($folder);
            session()->flash('success', __('folder.folder-deleted'));
        } else {
            $this->folderSystemService->deleteFolder($folder);
            session()->flash('success', __('folder.folder-deleted'));
        }


        return redirect('folders');
    }

    public function addComment(Request $request)
    {
        $currentUser = $this->currentUserResolver->getCurrentUser();

        $folder = $this->folderSystemService->findFolderById($request['folder_id']);
        $student_id = $folder->student_id;

        $folderComment = new FolderComment();
        $folderComment->text = $request['folder_comment'];
        $folderComment->folder_id = $request['folder_id'];
        $folderComment->author_id = $currentUser->student_id;
        $this->folderSystemService->saveFolderComment($folderComment);

        if ($currentUser->isTeacher() || $currentUser->isAdmin()) {
            $url = route('teacher-student-details', ['student' => $student_id]);

            $folder->student->notify(new FolderFeedbackGiven($folderComment));
        } else {
            $url = route('folders');
        }


        // fixme: what if user is admin? or shouldnt come here in that case
        return redirect($url);
    }

    public function stopSharingFolder(Folder $folder)
    {
        $student = $this->currentUserResolver->getCurrentUser();

        if (!$student->is($folder->student)) {
            return redirect('saved-learning-items')->with('error', __('folder.share-permission'));
        }

        $folder->teacher_id = null;
        $this->folderSystemService->saveFolder($folder);

        return redirect('folders');
    }

    public function AddItemsToFolder(Request $request)
    {
        foreach ($request['check_list'] as $selectedItem) {
            /** @var SavedLearningItem $savedLearningItem */
//            $savedLearningItem = $this->savedLearningItemRepository->findById($selectedItem);
            $savedLearningItem = $this->ProgressRegistrySystemService->getSavedLearningItemById($selectedItem);
            $savedLearningItem->folders()->attach($this->folderSystemService->findFolderById($request['selected_folder_id']));
            $savedLearningItem->save();
        }

        return redirect('folders');
    }
}
