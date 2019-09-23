<?php

declare(strict_types=1);

namespace App\Repository\Eloquent;

use App\Evidence;
use App\Services\EvidenceFileHandler;

class EvidenceRepository
{
    /**
     * @var EvidenceFileHandler
     */
    private $evidenceFileHandler;

    public function __construct(EvidenceFileHandler $evidenceFileHandler)
    {
        $this->evidenceFileHandler = $evidenceFileHandler;
    }

    public function get(int $id): Evidence
    {
        return Evidence::findOrFail($id);
    }

    /**
     * @throws \Exception
     */
    public function delete(Evidence $evidence): bool
    {
        if ($this->evidenceFileHandler->delete($evidence)) {
            return $evidence->delete();
        }
    }
}
