<?php

namespace App\Http\Controllers\TipApi;

use App\Cohort;
use App\EducationProgramType;
use App\Http\Controllers\Controller;
use App\Tips\CollectorDataAggregator;
use App\Tips\DataCollectors\CollectorFactory;
use App\Tips\Statistics\CustomStatistic;
use App\Tips\Statistics\PredefinedStatistic;
use App\Tips\Statistics\PredefinedStatisticHelper;
use App\Tips\Statistics\Variables\StatisticStatisticVariable;
use App\Tips\Tip;
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
                        $predefinedStatistic['education_program_type'] = 2;
                        $predefinedStatistic['type'] = 'predefinedstatistic';

                        return $predefinedStatistic;
                    })->toArray(),
                collect(PredefinedStatisticHelper::getActingData())
                    ->map(function ($predefinedStatistic) {
                        $predefinedStatistic['id'] = 'predef-acting-' . substr(md5($predefinedStatistic['name']), 0, 5);
                        $predefinedStatistic['education_program_type'] = 1;
                        $predefinedStatistic['type'] = 'predefinedstatistic';

                        return $predefinedStatistic;
                    })->toArray()
            );

        return [
            'educationProgramTypes'       => EducationProgramType::all(),
            'tips'                        => (new Tip)->with('coupledStatistics', 'enabledCohorts')->get(),
            'cohorts'                     => Cohort::all(),
            'availableStatistics'         => $availableStatistics,
            'statistics'                  => (new CustomStatistic)->with('educationProgramType', 'statisticVariableOne',
                'statisticVariableTwo')->get()->merge(
                (new PredefinedStatistic)->with('educationProgramType')->get()
            ),
            'availableStatisticVariables' => $statisticVariables,
        ];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
