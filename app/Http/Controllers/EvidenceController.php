<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Evidence;
use App\Repository\Eloquent\EvidenceRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class EvidenceController
{
    /**
     * @var EvidenceRepository
     */
    private $evidenceRepository;

    public function __construct(EvidenceRepository $evidenceRepository)
    {
        $this->evidenceRepository = $evidenceRepository;
    }

    public function remove(Evidence $evidence): RedirectResponse
    {
        if (!$evidence->learningActivity->workplaceLearningPeriod->student->is(Auth::user())) {
            throw new \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException();
        }

        $learningActivity = $evidence->learningActivity;

        $this->evidenceRepository->delete($evidence);

        return redirect()->route('process-acting-edit', ['id' => $learningActivity->laa_id]);
    }

    public function download(Evidence $evidence, string $diskFilename): BinaryFileResponse
    {
        if ($evidence->disk_filename !== $diskFilename) {
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException();
        }

        return response()->download(
            storage_path('app/activity-evidence/'.$evidence->disk_filename),
            $evidence->filename
        );
    }
}
