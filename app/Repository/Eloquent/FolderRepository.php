<?php

declare(strict_types=1);

namespace App\Repository\Eloquent;

use App\Services\EvidenceFileHandler;
use App\Folder;

class FolderRepository
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
        return Folder::all();
    }

    public function save(Folder $folder): bool
    {
        return $folder->save();
    }
}