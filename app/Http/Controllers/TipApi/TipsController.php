<?php

namespace App\Http\Controllers\TipApi;

use App\Cohort;
use App\EducationProgram;
use App\EducationProgramType;
use App\Http\Controllers\Controller;
use App\Http\Requests\TipUpdateRequest;
use App\Tips\Models\CustomStatistic;
use App\Tips\Models\StatisticVariable;
use App\Tips\Models\Tip;
use App\Tips\Services\TipManager;
use App\Tips\Statistics\PredefinedStatisticHelper;
use Illuminate\Http\Request;

class TipsController extends Controller
{

    private function getStatistics()
    {

        $statistics = array_merge(
            CustomStatistic::with('statisticVariableOne',
                'statisticVariableTwo')->get()->toArray()
        );


        // Add predefined statistics to the collection because they can also be chosen but aren't in the DB
        $predefined = collect(PredefinedStatisticHelper::getData())
            ->map(function (array $predefinedStatistic) {
                if($predefinedStatistic['epType'] === 'Producing') {
                    $predefinedStatistic['id'] = 'p-p-' . md5($predefinedStatistic['name']);
                    $predefinedStatistic['education_program_type'] = 'producing';
                } elseif ($predefinedStatistic['epType'] === 'Acting') {
                    $predefinedStatistic['id'] = 'p-a-' . md5($predefinedStatistic['name']);
                    $predefinedStatistic['education_program_type'] = 'acting';
                }
                $predefinedStatistic['type'] = 'predefinedstatistic';

                return $predefinedStatistic;
            })->toArray();

        return array_merge($statistics, $predefined);
    }

    /**
     * Display a listing of the resource.
     *
     * @return array
     * @throws \Exception
     */
    public function index(): array
    {
        $tips = Tip::with('coupledStatistics', 'enabledCohorts', 'likes', 'studentTipViews', 'moments')->get();
        $statistics = $this->getStatistics();

        return [
            'educationProgramTypes' => EducationProgramType::all(),
            'tips'                  => $tips,
            'cohorts'               => Cohort::all(),
            'educationPrograms'     => (new EducationProgram)->orderBy('ep_name', 'ASC')->get(),
            'statistics'            => $statistics,
            'statisticVariables'    => StatisticVariable::$availableFilters,
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param TipManager $service
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     */
    public function store(Request $request, TipManager $service)
    {
        $tip = $service->createTip(['name'            => trans('tips.new'),
                                    'shownInAnalysis' => true,
        ]);
        $tip->save();

        return Tip::with('coupledStatistics', 'enabledCohorts', 'likes', 'studentTipViews', 'moments')->findOrFail($tip->id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return Tip
     */
    public function update(TipUpdateRequest $request, $id): Tip
    {
        /** @var Tip $tip */
        $tip = (new Tip)->with('coupledStatistics', 'enabledCohorts', 'likes', 'studentTipViews')->findOrFail($id);
        $tip->name = $request->get('name');
        $tip->tipText = $request->get('tipText');
        $tip->showInAnalysis = $request->has('showInAnalysis') ? $request->get('showInAnalysis') : false;
        if($tip->trigger === 'moment') {
            $tip->rangeStart = (int) $request->get('rangeStart');
            $tip->rangeEnd = (int) $request->get('rangeEnd');
        }
        $tip->save();
        $tip->enabledCohorts()->sync($request->get('enabled_cohorts'));

        return $tip;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy($id): \Illuminate\Http\Response
    {
        /** @var Tip $tip */
        $tip = (new Tip)->findOrFail($id);
        $tip->coupledStatistics()->delete();
        $tip->enabledCohorts()->detach();
        $tip->likes()->delete();
        $tip->delete();


        return response()->json([], 200);
    }

    public function updateCohorts(Request $request, Tip $tip, TipManager $tipService)
    {
        $tip = $tipService->enableCohorts($tip, $request->all());

        return redirect()->route('tips.edit', ['id' => $tip->id]);
    }

    public function likeTip(Tip $tip, TipManager $tipService, Request $request) {
        $liked = $tipService->likeTip($tip, (int) $request->get('type', 1), $request->user());
        return response()->json(['status' => $liked ? 'success' : 'error']);
    }
}
