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

        session()->flash('success', __('folder.succes'));

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

        $folder = Folder::find($request['folder_id']);
        $folder->teacher_id = $request['teacher'];
        $folder->save();

        return redirect('saved-learning-items');
    }
}
