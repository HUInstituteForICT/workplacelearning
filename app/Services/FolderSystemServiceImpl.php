<?php


namespace App\Services;

use App\Folder;
use App\Interfaces\FolderSystemServiceInterface;
use App\Repository\Eloquent\FolderRepository;
use phpDocumentor\Reflection\Types\Collection;

class FolderSystemServiceImpl implements FolderSystemServiceInterface
{

    /**
     * @var FolderRepository
     */
    private $folderRepository;

    public function __construct(
        FolderRepository $folderRepository
    ){
        $this->folderRepository = $folderRepository;
    }

    public function getAllFolderComments(): Collection
    {
        // TODO: Implement getAllFolderComments() method.
    }
    public function getAllFolders(): Collection
    {
        // TODO: Implement getAllFolders() method.
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