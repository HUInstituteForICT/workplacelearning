<?php

declare(strict_types=1);

namespace App\Http\Controllers\Acting\Export;

use App\Http\Controllers\Controller;
use App\Repository\Eloquent\LearningActivityActingRepository;
use App\Services\ActivityExporter;
use App\Services\CurrentUserResolver;
use App\Traits\PhpWordDownloader;
use Illuminate\Http\Request;

class WordExport extends Controller
{
    use PhpWordDownloader;
    /**
     * @var LearningActivityActingRepository
     */
    private $actingRepository;
    /**
     * @var ActivityExporter
     */
    private $actingActivityExporter;

    public function __construct(LearningActivityActingRepository $actingRepository, ActivityExporter $actingActivityExporter)
    {
        $this->actingRepository = $actingRepository;
        $this->actingActivityExporter = $actingActivityExporter;
    }

    public function __invoke(Request $request, CurrentUserResolver $userResolver)
    {
        $activities = $this->actingRepository->getMultipleForUser($userResolver->getCurrentUser(), $request->get('ids'));
        $includeReflections = (bool) $request->get('reflections');

        $document = $this->actingActivityExporter->export($activities, $includeReflections);

        $this->downloadDocument($document, __('process_export.activity-export-filename').'.docx');
    }
}
