<?php

declare(strict_types=1);

namespace App\Services;

use App\GenericLearningActivity;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class EvidenceUploadHandler
{
    /**
     * @var EvidenceFileHandler
     */
    private $evidenceFileHandler;

    public function __construct(EvidenceFileHandler $evidenceFileHandler)
    {
        $this->evidenceFileHandler = $evidenceFileHandler;
    }

    /**
     * @throws \Exception
     */
    public function process(Request $request, GenericLearningActivity $genericLearningActivity): void
    {
        array_map(function (UploadedFile $evidenceFile) use ($genericLearningActivity) {
            $evidence = $this->evidenceFileHandler->store($evidenceFile);

            $genericLearningActivity->evidence()->save($evidence);
        }, $request->file('evidence'));
    }
}
