<?php


namespace App\Services;

use App\Folder;
use App\FolderComment;
use App\Interfaces\FolderSystemServiceInterface;
use App\Repository\Eloquent\FolderCommentRepository;
use App\Repository\Eloquent\FolderRepository;
use Illuminate\Database\Eloquent\Collection;

class FolderSystemServiceImpl implements FolderSystemServiceInterface
{

    /**
     * @var FolderRepository
     */
    private $folderRepository;

    /**
     * @var FolderCommentRepository
     */
    private $folderCommentRepository;

    public function __construct(
        FolderRepository $folderRepository,
        FolderCommentRepository $folderCommentRepository
    ){
        $this->folderRepository = $folderRepository;
        $this->folderCommentRepository = $folderCommentRepository;
    }

    public function getAllFolderComments(): Collection
    {
        return $this->folderCommentRepository->all();
    }

    public function saveFolderComment(FolderComment $folderComment): bool
    {
        return $this->folderCommentRepository->save($folderComment);
    }

    public function getAllFolders(): Collection
    {
        return $this->folderRepository->all();
    }
    public function getFolderCommentsByStudentId(int $studentId): Collection
    {
        // TODO: Implement getFolderCommentsByStudentId() method.
    }
    public function getFoldersByStudentId(int $studentId): Collection
    {
        // TODO: Implement getFoldersByStudentId() method.
    }

    public function getFoldersBySLIId(int $sLIId): Collection
    {
        // TODO: Implement getFoldersBySLIId() method.
    }

    public function saveFolder(Folder $folder): bool
    {
        return $this->folderRepository->save($folder);
    }

    public function findFolderById(int $id, bool $includeDeleted = false): Folder
    {
        return $this->folderRepository->findById($id,$includeDeleted);
    }

    public function restoreFolder(Folder $folder): bool
    {
        return $this->folderRepository->restore($folder);
    }

    public function deleteFolder(Folder $folder): bool
    {
        return $this->folderRepository->delete($folder);
    }
}