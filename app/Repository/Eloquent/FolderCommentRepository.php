<?php

declare(strict_types=1);

namespace App\Repository\Eloquent;

use App\Services\EvidenceFileHandler;
use App\FolderComment;

class FolderCommentRepository
{
    /**
     * @var EvidenceFileHandler
     */
    private $evidenceFileHandler;

    public function __construct(EvidenceFileHandler $evidenceFileHandler)
    {
        $this->evidenceFileHandler = $evidenceFileHandler;
    }

    public function all()
    {
        return FolderComment::all();
    }

    public function save(FolderComment $folderComment): bool
    {
        return $folderComment->save();
    }
}