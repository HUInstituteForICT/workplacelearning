<?php

namespace App\Http\Controllers\Acting\Export;

use App\Http\Controllers\Controller;
use App\LearningActivityActing;
use App\Repository\Eloquent\LearningActivityActingRepository;
use App\Services\ActingActivityExporter;
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
     * @var ActingActivityExporter
     */
    private $actingActivityExporter;

    public function __construct(LearningActivityActingRepository $actingRepository, ActingActivityExporter $actingActivityExporter)
    {
        $this->actingRepository = $actingRepository;
        $this->actingActivityExporter = $actingActivityExporter;
    }

    public function __invoke(Request $request, CurrentUserResolver $userResolver)
    {

        $activities = $this->actingRepository->getMultipleForUser($userResolver->getCurrentUser(), $request->get('ids'));
        $includeReflections = (bool) $request->get('reflections');

        $document = $this->actingActivityExporter->export($activities, $includeReflections);

        $this->downloadDocument($document, 'activities.docx');
    }
}
