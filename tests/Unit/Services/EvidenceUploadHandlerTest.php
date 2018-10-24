<?php

namespace App\Services;

use App\LearningActivityActing;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class EvidenceUploadHandlerTest extends \PHPUnit\Framework\TestCase
{
    public function testProcess(): void
    {
        $uploadedFile = $this->createMock(UploadedFile::class);

        $fileHandler = $this->createMock(EvidenceFileHandler::class);
        $fileHandler->expects(self::once())->method('store')->with($uploadedFile);

        $request = $this->createMock(Request::class);
        $request->expects(self::once())->method('file')->with('evidence')->willReturn([$uploadedFile]);

        $learningActivityActing = $this->createMock(LearningActivityActing::class);
        $learningActivityActing->expects(self::once())->method('evidence');

        $uploadHandler = new EvidenceUploadHandler($fileHandler);

        $uploadHandler->process($request, $learningActivityActing);
    }
}
