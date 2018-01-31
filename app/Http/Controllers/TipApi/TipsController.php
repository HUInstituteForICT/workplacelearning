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
use App\Tips\Statistics\Variables\StatisticStatisticVariable;
use App\Tips\Tip;
use App\Tips\TipService;
use Illuminate\Http\Request;

class TipsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param CollectorFactory $collectorFactory
     * @return array
     * @throws \Exception
     */
    public function index(CollectorFactory $collectorFactory)
    {
        // Collect statisticVariables available
        $collectibleDataStatisticVariables = [];
        EducationProgramType::all()->each(
            function (EducationProgramType $type) use (&$collectibleDataStatisticVariables, $collectorFactory) {
                $collector = $collectorFactory->buildCollector((new EducationProgramType)->find($type->eptype_id));

                $info = array_map(function ($infoItem) use ($type) {
                    $infoItem['education_program_type_id'] = $type->eptype_id;
                    $infoItem['id'] = 'collectable-' . substr(md5($type->eptype_id . $infoItem['name']), 0, 5);

                    return $infoItem;
                }, (new CollectorDataAggregator($collector))->getInformation());

                $collectibleDataStatisticVariables = array_merge($collectibleDataStatisticVariables,
                    $info);
            }
        );


        $availableStatisticStatisticVariables = (new CustomStatistic)
//            ->where('education_program_type_id', '=', $educationProgramTypeId)
            ->orderBy('name', 'ASC')
            ->get();

        $availableStatisticStatisticVariables->map(function (CustomStatistic $statistic) {
            $statistic->type = (new StatisticStatisticVariable)->getType();

            return $statistic;
        });

        $statisticVariables = array_merge($collectibleDataStatisticVariables,
            $availableStatisticStatisticVariables->toArray());

        $availableStatistics =
            array_merge(
                (new CustomStatistic)
                    ->with('educationProgramType', 'statisticVariableOne', 'statisticVariableTwo')->get()->toArray(),
                collect(PredefinedStatisticHelper::getProducingData())
                    ->map(function ($predefinedStatistic) {
                        $predefinedStatistic['id'] = 'predef-producing-' . substr(md5($predefinedStatistic['name']), 0,
                                5);
                        $predefinedStatistic['education_program_type'] = 2; // Todo Check which one of these is actually necessary in front end
                        $predefinedStatistic['education_program_type_id'] = 2;
                        $predefinedStatistic['type'] = 'predefinedstatistic';

                        return $predefinedStatistic;
                    })->toArray(),
                collect(PredefinedStatisticHelper::getActingData())
                    ->map(function ($predefinedStatistic) {
                        $predefinedStatistic['id'] = 'predef-acting-' . substr(md5($predefinedStatistic['name']), 0, 5);
                        $predefinedStatistic['education_program_type'] = 1;
                        $predefinedStatistic['education_program_type_id'] = 1; // Todo Check which one of these is actually necessary in front end
                        $predefinedStatistic['type'] = 'predefinedstatistic';

                        return $predefinedStatistic;
                    })->toArray()
            );

        $findAvailableStatisticByid = function ($id) use ($availableStatistics) {
            foreach ($availableStatistics as $stat) {
                if ($stat['id'] == $id) {
                    return $stat;
                }
            }
            throw new \Exception("Can't find availableStatistic for id ${id}");
        };

//        dump($availableStatistics);

        return [
            'educationProgramTypes'       => EducationProgramType::all(),
            'tips'                        => (new Tip)->with('coupledStatistics', 'enabledCohorts')->get(),
            'cohorts'                     => Cohort::all(),
            'availableStatistics'         => $availableStatistics,
            'statistics'                  => (new CustomStatistic)->with('educationProgramType', 'statisticVariableOne',
                'statisticVariableTwo')->get()->merge(
                (new PredefinedStatistic)->with('educationProgramType')->get()->each(function (
                    PredefinedStatistic $statistic
                ) {
                    $data = ($statistic->educationProgramType->eptype_id === 1 ? PredefinedStatisticHelper::getActingData() : PredefinedStatisticHelper::getProducingData());
                    foreach ($data as $entry) {
                        if ($entry['name'] === $statistic->name) {
                            $statistic->valueParameterDescription = $entry['valueParameterDescription'];
                        }
                    }
                })
            ),
            'availableStatisticVariables' => $statisticVariables,
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
     */
    public function destroy($id)
    {
        //
    }
}
