<?php

declare(strict_types=1);

namespace app\Http\Controllers\Admin;

// Use the PHP native IntlDateFormatter (note: enable .dll in php.ini)

use App\Repository\Eloquent\WorkplaceLearningPeriodRepository;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UpdateTeacherForWorkplaceLearningPeriod extends Controller
{
    /**
     * @var WorkplaceLearningPeriodRepository
     */
    private $workplaceLearningPeriodRepository;

    public function __construct(WorkplaceLearningPeriodRepository $workplaceLearningPeriodRepository)
    {
        $this->workplaceLearningPeriodRepository = $workplaceLearningPeriodRepository;
    }

    public function __invoke(Request $request)
    {
        $wplpId = $request->request->getInt('wplp_id');
        $workplacelearingperiod = $this->workplaceLearningPeriodRepository->get($wplpId);
        if (!$wplpId) {
            throw new NotFoundHttpException("No WPLP with id {$wplpId} exists");
        }

        $workplacelearingperiod->teacher_id = $request->input('teacher_id');

        $this->workplaceLearningPeriodRepository->save($workplacelearingperiod);

        return redirect()->route('admin-linking')->with('success', __('general.edit-saved'));
    }
}
