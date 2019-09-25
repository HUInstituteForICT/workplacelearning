<?php

declare(strict_types=1);

namespace App\Http\Controllers\TipApi;

use App\Http\Controllers\Controller;
use App\Http\Requests\TipCoupledStatisticCreateRequest;
use App\Http\Requests\TipCoupledStatisticUpdateRequest;
use App\Tips\Models\CustomStatistic;
use App\Tips\Models\Tip;
use App\Tips\Models\TipCoupledStatistic;
use App\Tips\Services\StatisticService;
use App\Tips\Statistics\Predefined\PredefinedStatisticsProvider;

class TipCoupledStatisticController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     *
     * @return TipCoupledStatistic
     *
     * @throws \Exception
     */
    public function store(TipCoupledStatisticCreateRequest $request, StatisticService $statisticService)
    {
        // p-a- & p-p- are IDs for predefined statistics (Producing/Acting)
        if (!starts_with($request->get('statistic_id'), ['p-p-', 'p-a-'])) {
            /** @var CustomStatistic $statistic */
            $statistic = CustomStatistic::with('statisticVariableOne',
                'statisticVariableTwo')->findOrFail($request->get('statistic_id'));
        } else {
            $predefinedStatisticClassName = $request->get('className');

            if (!in_array($predefinedStatisticClassName,
                PredefinedStatisticsProvider::getPredefinedStatisticClassNames(), true)) {
                throw new \InvalidArgumentException($predefinedStatisticClassName.' is not a predefined statistics class');
            }

            $statistic = $statisticService->createPredefinedStatistic($predefinedStatisticClassName);
        }
        $tip = (new Tip())->findOrFail($request->get('tip_id'));

        $coupledStatistic = new TipCoupledStatistic([
            'statistic_id'        => $statistic->id,
            'tip_id'              => $tip->id,
            'comparison_operator' => $request->get('comparisonOperator'),
            'threshold'           => $request->get('threshold'),
        ]);

        $coupledStatistic->statistic()->associate($statistic);

        $coupledStatistic->save();

        return $coupledStatistic;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     *
     * @return TipCoupledStatistic
     */
    public function update(TipCoupledStatisticUpdateRequest $request, $id)
    {
        $coupledStatistic = TipCoupledStatistic::with('statistic')->find($id);
        $coupledStatistic->update($request->all());

        return $coupledStatistic;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $coupledStatisic = (new TipCoupledStatistic())->findOrFail($id);
        $coupledStatisic->delete();

        // Just return empty json response - status 200 matters
        return response()->json();
    }
}
