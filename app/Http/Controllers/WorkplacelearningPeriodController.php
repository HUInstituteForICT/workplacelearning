<?php

declare(strict_types=1);


namespace app\Http\Controllers;

// Use the PHP native IntlDateFormatter (note: enable .dll in php.ini)

use App\Category;
use App\Cohort;
use App\Repository\Eloquent\CohortRepository;
use App\Services\CurrentUserResolver;
use App\Workplace;
use App\WorkplaceLearningPeriod;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use InvalidArgumentException;
use Validator;

class WorkPlaceLearningPeriodController extends Controller
{
    /**
     * @var CohortRepository
     */
    private $cohortRepository;

    /**
     * @var CurrentUserResolver
     */
    private $currentUserResolver;

    public function __construct(CohortRepository $cohortRepository, CurrentUserResolver $currentUserResolver)
    {
        $this->middleware('auth');
        $this->cohortRepository = $cohortRepository;
        $this->currentUserResolver = $currentUserResolver;
    }

    public function Update(Request $request)
    {
        $workplacelearingperiod = WorkplaceLearningPeriod::find($request['wplp_id']);
        $workplacelearingperiod->teacher_id = $request['teacher_id'];
        $workplacelearingperiod->save();

        return redirect()->route('admin-linking')->with('success', __('general.edit-saved'));
    }
}
