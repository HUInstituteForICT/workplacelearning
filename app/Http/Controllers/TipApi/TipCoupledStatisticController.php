<?php

namespace App\Http\Controllers\TipApi;

use App\Http\Controllers\Controller;
use App\Http\Requests\TipCoupledStatisticCreateRequest;
use App\Tips\Statistics\CustomStatistic;
use App\Tips\StatisticService;
use App\Tips\Tip;
use App\Tips\TipCoupledStatistic;
use Illuminate\Http\Request;

class TipCoupledStatisticController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param TipCoupledStatisticCreateRequest $request
     * @param StatisticService $statisticService
     * @return TipCoupledStatistic
     * @throws \Exception
     */
    public function store(TipCoupledStatisticCreateRequest $request, StatisticService $statisticService)
    {
        if (!starts_with($request->get('statistic_id'), 'predef-')) {
            /** @var CustomStatistic $statistic */
            $statistic = (new CustomStatistic)->findOrFail($request->get('statistic_id'));
        } else {
            $statistic = $statisticService->createPredefinedStatistic($request->get('method'));
        }
        $tip = (new Tip)->findOrFail($request->get('tip_id'));

        $coupledStatistic = new TipCoupledStatistic([
            'statistic_id'        => $statistic->id,
            'tip_id'              => $tip->id,
            'comparison_operator' => $request->get('comparisonOperator'),
            'threshold'           => $request->get('threshold'),
            'multiplyBy100'       => $request->get('multiplyBy100'),
        ]);

        $coupledStatistic->save();
        if($statistic instanceof CustomStatistic)
        {
            $coupledStatistic->with('educationProgramType', 'statisticVariableOne', 'statisticVariableTwo');
        } else {
            $coupledStatistic->with('educationProgramType');
        }
        return $coupledStatistic;
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
