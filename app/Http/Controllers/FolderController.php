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
        $folder = Folder::find($request['folder_id']);

        if (!$student->is($folder->student)) {
            return redirect('saved-learning-items')->with('error', __('folder.share-permission'));
        }

        $folderComment = new FolderComment();
        $folderComment->text = $request['folder_comment'];
        $folderComment->folder_id = $request['folder_id'];
        $folderComment->author_id = $student->student_id;
        $this->folderCommentRepository->save($folderComment);

        $folder->teacher_id = $request['teacher'];
        $folder->save();

        session()->flash('success', __('folder.folder-shared'));
        
        return redirect('saved-learning-items');
    }

    public function addComment(Request $request) {
        $currentUser = $this->currentUserResolver->getCurrentUser();
        
        $folder = Folder::find($request['folder_id']);
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
