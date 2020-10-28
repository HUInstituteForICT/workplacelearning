<?php


namespace App\Interfaces;


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
}