<?php

namespace App\Services;

use App\LearningActivityActing;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\File\Exception\UploadException;

class EvidenceUploadHandler
{
    /**
     * @var Filesystem
     */
    private $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public function process(Request $request, LearningActivityActing $learningActivityActing): void
    {
        $evidence = $request->file('evidence');
        $diskFileName = Uuid::uuid4();
        if (!$evidence->storeAs('activity-evidence', $diskFileName)) {
            throw new UploadException('Unable to upload file');
        }

        $this->removePreviousUpload($learningActivityActing);

        $learningActivityActing->evidence_filename = $evidence->getClientOriginalName();
        $learningActivityActing->evidence_disk_filename = $diskFileName;
        $learningActivityActing->evidence_mime = $evidence->getClientMimeType();

        $learningActivityActing->save();
    }

    private function removePreviousUpload(LearningActivityActing $learningActivityActing): void
    {
        if ($learningActivityActing->evidence_disk_filename !== null && $this->filesystem->exists("activity-evidence/{$learningActivityActing->evidence_disk_filename}")) {
            $this->filesystem->delete("activity-evidence/{$learningActivityActing->evidence_disk_filename}");
        }
    }
}
