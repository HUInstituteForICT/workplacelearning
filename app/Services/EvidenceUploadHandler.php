<?php

namespace App\Services;

use App\LearningActivityActing;
use App\Repository\Eloquent\LearningActivityActingRepository;
use Illuminate\Contracts\Filesystem\Filesystem;
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
    public function process(Request $request, LearningActivityActing $learningActivityActing): void
    {
        array_map(function (UploadedFile $evidenceFile) use ($learningActivityActing) {
            $evidence = $this->evidenceFileHandler->store($evidenceFile);

            $learningActivityActing->evidence()->save($evidence);
        }, $request->file('evidence'));
    }
}
