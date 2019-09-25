<?php

declare(strict_types=1);

namespace App\Services;

use App\Evidence;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Http\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\UploadException;

class EvidenceFileHandlerTest extends \PHPUnit\Framework\TestCase
{
    public function testStore(): void
    {
        $uploadedFile = $this->createMock(UploadedFile::class);
        $uploadedFile->expects(self::once())->method('storeAs')->withAnyParameters()->willReturn(false);

        $filesystem = $this->createMock(Filesystem::class);

        $fileHandler = new EvidenceFileHandler($filesystem);

        $this->expectException(UploadException::class);
        $fileHandler->store($uploadedFile);

        $uploadedFile = $this->createMock(UploadedFile::class);
        $uploadedFile->expects(self::once())->method('storeAs')->withAnyParameters()->willReturn(true);

        $fileHandler->store($uploadedFile);
    }

    public function testDelete(): void
    {
        $evidence = $this->createMock(Evidence::class);
        $evidence->expects(self::exactly(2))->method('__get')->with('disk_filename')->willReturn('some_fileName');

        $filesystem = $this->createMock(Filesystem::class);
        $filesystem->expects(self::once())->method('exists')->with('activity-evidence/some_fileName')->willReturn(true);
        $filesystem->expects(self::once())->method('delete')->with('activity-evidence/some_fileName')->willReturn(true);

        $fileHandler = new EvidenceFileHandler($filesystem);

        $result = $fileHandler->delete($evidence);

        $this->assertTrue($result);
    }
}
