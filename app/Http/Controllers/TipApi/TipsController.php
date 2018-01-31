<?php

namespace App\Http\Controllers\TipApi;

use App\Cohort;
use App\EducationProgramType;
use App\Http\Controllers\Controller;
use App\Http\Requests\TipUpdateRequest;
use App\Tips\CollectorDataAggregator;
use App\Tips\DataCollectors\CollectorFactory;
use App\Tips\Statistics\CustomStatistic;
use App\Tips\Statistics\PredefinedStatistic;
use App\Tips\Statistics\PredefinedStatisticHelper;
use App\Tips\Statistics\Variables\CollectedDataStatisticVariable;
use App\Tips\Statistics\Variables\StatisticStatisticVariable;
use App\Tips\Tip;
use App\Tips\TipService;
use Illuminate\Http\Request;

class TipsController extends Controller
{
    private function getStatisticVariables(CollectorFactory $collectorFactory)
    {
        // We prepend the id of collectableVariables with "c-" and the already existing statistics that can be used as variables with "s-"
        // This way we can easily filter them from already in use variables in the front end without complicating with different lists, as I tried before 🙃


        // Get statistic variables (separate because only StatisticStatisticVariable has that relation but we need it)
        // These are also the variables that are already in use by statistics and can NOT be used again
        $statisticVariables = array_merge(
            StatisticStatisticVariable::with('nestedStatistic')->get()->toArray(),
            CollectedDataStatisticVariable::all()->toArray()
        );

        // Collectable statisticVariables available
        $collectibleDataStatisticVariables = [];
        EducationProgramType::all()->each(
            function (EducationProgramType $type) use (&$collectibleDataStatisticVariables, $collectorFactory) {
                $collector = $collectorFactory->buildCollector((new EducationProgramType)->find($type->eptype_id));

                $info = array_map(function ($infoItem) use ($type) {
                    $infoItem['education_program_type'] = $type->eptype_id;
                    $infoItem['id'] = 'c-' . md5($type->eptype_id . $infoItem['name']);
                    $infoItem['findable_id'] = $infoItem['method'] . '-' . $type->eptype_id;

                    return $infoItem;
                }, (new CollectorDataAggregator($collector))->getInformation());

                $collectibleDataStatisticVariables = array_merge($collectibleDataStatisticVariables,
                    $info);
            }
        );

// Disabled because it makes front-end way too complex and seems niche feature as well (feature would allow for nesting statistics as statisticVariable in new statistics)
//        $availableStatisticStatisticVariables = (new CustomStatistic)
//            ->with('educationProgramType')
//            ->orderBy('name', 'ASC')
//            ->get()->map(function (CustomStatistic $statistic) {
//                $statistic = $statistic->toArray();
//                // s- to identify pickable statistic variable in front-end
//                $statistic['id'] = 's-' . $statistic['id'];
//                $statistic['type'] = (new StatisticStatisticVariable)->getType();
//                return $statistic;
//            });

        return array_merge(
            $statisticVariables,
            $collectibleDataStatisticVariables
        /*$availableStatisticStatisticVariables->toArray()*/);
    }

    private function getStatistics()
    {

        $statistics = array_merge(
            (new PredefinedStatistic)->with('educationProgramType')->get()->toArray(),
            (new CustomStatistic)->with('educationProgramType', 'statisticVariableOne',
                'statisticVariableTwo')->get()->toArray()
        );


        // Add predefined statistics to the collection because they can also be chosen but aren't in the DB
        $predefinedProducing = collect(PredefinedStatisticHelper::getProducingData())
            ->map(function (array $predefinedStatistic) {

                $predefinedStatistic['id'] = 'p-p-' . md5($predefinedStatistic['name']);
                $predefinedStatistic['education_program_type'] = 2;
                $predefinedStatistic['type'] = 'predefinedstatistic';

                return $predefinedStatistic;
            })->toArray();

        $predefinedActing = collect(PredefinedStatisticHelper::getActingData())
            ->map(function ($predefinedStatistic) {
                $predefinedStatistic['id'] = 'p-a-' . md5($predefinedStatistic['name']);
                $predefinedStatistic['education_program_type'] = 1;
                $predefinedStatistic['type'] = 'predefinedstatistic';

                return $predefinedStatistic;
            })->toArray();

        return array_merge($statistics, $predefinedActing, $predefinedProducing);
    }

    /**
     * Display a listing of the resource.
     *
     * @param CollectorFactory $collectorFactory
     * @return array
     * @throws \Exception
     */
    public function index(CollectorFactory $collectorFactory)
    {
        $tips = (new Tip)->with('coupledStatistics', 'enabledCohorts')->get();
        $statistics = $this->getStatistics();

        return [
            'educationProgramTypes' => EducationProgramType::all(),
            'tips'                  => $tips,
            'cohorts'               => Cohort::all(),
            'statistics'            => $statistics,
            'statisticVariables'    => $this->getStatisticVariables($collectorFactory),
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param TipService $service
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     */
    public function store(Request $request, TipService $service)
    {
        $tip = $service->createTip(['name' => trans('general.new') . ' Tip', 'shownInAnalysis' => true]);
        $tip->save();

        return (new Tip)->with('coupledStatistics', 'enabledCohorts')->findOrFail($tip->id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return Tip
     */
    public function update(TipUpdateRequest $request, $id)
    {
        /** @var Tip $tip */
        $tip = (new Tip)->findOrFail($id);
        $tip->name = $request->get('name');
        $tip->tipText = $request->get('tipText');
        $tip->showInAnalysis = $request->has('showInAnalysis');
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
    public function destroy($id)
    {
        /** @var Tip $tip */
        $tip = (new Tip)->findOrFail($id);
        $tip->coupledStatistics()->delete();
        $tip->enabledCohorts()->detach();
        $tip->likes()->delete();
        $tip->delete();


        return response()->json([], 200);
    }
}
