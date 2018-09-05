<?php

namespace App\Services;

use App\LearningActivityActing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\File\Exception\UploadException;

class EvidenceUploadHandler
{
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
        if ($learningActivityActing->evidence_disk_filename !== null && Storage::exists("activity-evidence/{$learningActivityActing->evidence_disk_filename}")) {
            Storage::delete("activity-evidence/{$learningActivityActing->evidence_disk_filename}");
        }
    }
}
