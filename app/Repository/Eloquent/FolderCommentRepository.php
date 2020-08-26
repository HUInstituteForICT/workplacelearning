<?php

declare(strict_types=1);

namespace App\Repository\Eloquent;

use App\FolderComment;

class FolderCommentRepository
{
    public function all()
    {
        return FolderComment::all();
    }

    public function save(FolderComment $folderComment): bool
    {
        return $folderComment->save();
    }
}