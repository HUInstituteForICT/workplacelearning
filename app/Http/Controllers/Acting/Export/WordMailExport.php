<?php


namespace App\Http\Controllers\Acting\Export;


use App\Http\Controllers\Controller;
use App\Mail\ActingActivitiesWordExportMail;
use App\Mail\TxtExport;
use App\Repository\Eloquent\LearningActivityActingRepository;
use App\Services\ActingActivityExporter;
use App\Services\CurrentUserResolver;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpWord\PhpWord;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class WordMailExport extends Controller
{

    /**
     * @var ActingActivityExporter
     */
    private $actingActivityExporter;
    /**
     * @var LearningActivityActingRepository
     */
    private $actingRepository;
    /**
     * @var CurrentUserResolver
     */
    private $userResolver;

    public function __construct(ActingActivityExporter $actingActivityExporter, LearningActivityActingRepository $actingRepository, CurrentUserResolver $userResolver)
    {
        $this->actingActivityExporter = $actingActivityExporter;
        $this->actingRepository = $actingRepository;
        $this->userResolver = $userResolver;
    }

    public function __invoke(Request $request)
    {

        $student = $this->userResolver->getCurrentUser();

        $email = $request->get('email');
        $comment = $request->get('comment');
        $ids = $request->get('ids');

        $activities = $this->actingRepository->getMultipleForUser($student, $ids);
        $document = $this->actingActivityExporter->export($activities);

        Mail::to($email)->send(new ActingActivitiesWordExportMail($comment, $document));

        return \response(json_encode(['status' => 'success']));
    }


}
