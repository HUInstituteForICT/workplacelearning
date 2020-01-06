<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Repository\Eloquent\FolderRepository;
use App\Repository\Eloquent\FolderCommentRepository;
use App\Folder;
use App\FolderComment;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Services\CurrentUserResolver;
use Illuminate\Http\RedirectResponse;

class FolderController extends Controller
{

    /**
     * @var CurrentUserResolver
     */
    private $currentUserResolver;

    /**
     * @var FolderRepository
     */
    private $folderRepository;

    /**
     * @var FolderCommentRepository
     */
    private $folderCommentRepository;


    public function __construct(
    CurrentUserResolver $currentUserResolver, 
    FolderRepository $folderRepository,
    FolderCommentRepository $folderCommentRepository)
    {
        $this->currentUserResolver = $currentUserResolver;
        $this->folderRepository = $folderRepository;
        $this->folderCommentRepository = $folderCommentRepository;
    }

    public function create(Request $request)
    {
        $student = $this->currentUserResolver->getCurrentUser();

        $folder = new Folder();
        $folder->title = $request['folder_title'];
        $folder->description = $request['folder_description'];
        $folder->student_id = $student->student_id;

        $this->folderRepository->save($folder);

        session()->flash('success', __('folder.folder-created'));

        return redirect('saved-learning-items');
    }

    public function shareFolderWithTeacher(Request $request)
    {
        $student = $this->currentUserResolver->getCurrentUser();

        $folderComment = new FolderComment();
        $folderComment->text = $request['folder_comment'];
        $folderComment->folder_id = $request['folder_id'];
        $folderComment->author_id = $student->student_id;
        $this->folderCommentRepository->save($folderComment);

        $folder = $this->folderRepository->findById($request['folder_id']);
        $folder->teacher_id = $request['teacher'];
        $folder->save();

        session()->flash('success', __('folder.folder-shared'));

        return redirect('saved-learning-items');
    }

    public function delete(Folder $folder): RedirectResponse
    {
        $student = $this->currentUserResolver->getCurrentUser();

        if (!$student->is($folder->student)) {
            return redirect('saved-learning-items')->with('error', __('folder.no-delete-permission'));
        }

        // remove all items from the folder
        foreach ($folder->savedLearningItems as $sli) {
            $sli->folder = null;
            $sli->save();
        }

        $this->folderRepository->delete($folder);
        session()->flash('success', __('folder.folder-deleted'));

        return redirect('saved-learning-items');
    }

    public function addComment(Request $request) {
        $currentUser = $this->currentUserResolver->getCurrentUser();
        
        $folder = $this->folderRepository->findById($request['folder_id']);
        $student_id = $folder->student_id;

        $folderComment = new FolderComment();
        $folderComment->text = $request['folder_comment'];
        $folderComment->folder_id = $request['folder_id'];
        $folderComment->author_id = $currentUser->student_id;
        $this->folderCommentRepository->save($folderComment);

        if ($currentUser->isTeacher()) {
            $url = route('teacher-student-details', ['student' => $student_id]);
        } elseif ($currentUser->isStudent()) {
            $url = route('saved-learning-items');
        }

        return redirect($url);
    }
}
