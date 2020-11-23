<?php


namespace App\Interfaces;


use App\Folder;
use phpDocumentor\Reflection\Types\Collection;

interface FolderSystemServiceInterface
{
    public function getAllFolders(): Collection;
    public function getAllFolderComments(): Collection;

    //FolderComment domain
    public function getFolderCommentsByStudentId(int $studentId): Collection;

    //Folder domain
    public function getFoldersByStudentId(int $studentId): Collection;
    public function getFoldersBySLIId(int $sLIId) : Collection;


    //FolderController FolderRepository methods.
    // TODO: implement
    public function saveFolder(Folder $folder) : bool;
    public function findFolderById(int $id, bool $includeDeleted = false) : Folder;
    public function restoreFolder(Folder $folder) : bool;
    public function deleteFolder(Folder $folder) : bool;

}