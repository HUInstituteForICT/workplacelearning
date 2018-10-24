<?php

namespace App\Services;

use App\Evidence;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Http\UploadedFile;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\File\Exception\UploadException;

class EvidenceFileHandler
{
    /**
     * @var Filesystem
     */
    private $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * @throws \Exception
     */
    public function store(UploadedFile $evidenceFile): Evidence
    {
        $diskFileName = Uuid::uuid4();
        if (!$evidenceFile->storeAs('activity-evidence', $diskFileName)) {
            throw new UploadException('Unable to upload file');
        }

        return new Evidence([
            'filename' => $evidenceFile->getClientOriginalName(),
            'disk_filename' => $diskFileName,
            'mime' => $evidenceFile->getClientMimeType(),
        ]);
    }

    public function delete(Evidence $evidence): bool
    {
        if ($this->filesystem->exists("activity-evidence/{$evidence->disk_filename}")) {
            return $this->filesystem->delete("activity-evidence/{$evidence->disk_filename}");
        }

        return true;
    }
}
