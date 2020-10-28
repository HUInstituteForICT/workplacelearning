<?php


namespace App\Services;

use App\Interfaces\FolderSystemServiceInterface;
use phpDocumentor\Reflection\Types\Collection;

class FolderSystemServiceImpl implements FolderSystemServiceInterface
{
    public function __construct(){}

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
}