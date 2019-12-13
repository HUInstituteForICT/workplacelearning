<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Repository\Eloquent\FolderRepository;
use App\Folder;
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


    public function __construct(CurrentUserResolver $currentUserResolver, FolderRepository $folderRepository)
    {
        $this->currentUserResolver = $currentUserResolver;
        $this->folderRepository = $folderRepository;
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
}
