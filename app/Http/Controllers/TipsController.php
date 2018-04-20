<?php

namespace App\Http\Controllers;

use App\Cohort;
use App\Http\Requests\TipCoupleStatisticRequest;
use App\Http\Requests\TipEditRequest;
use App\Http\Requests\TipStoreRequest;
use App\Tips\Statistics\CustomStatistic;
use App\Tips\Statistics\PredefinedStatisticHelper;
use App\Tips\StatisticService;
use App\Tips\Tip;
use App\Tips\TipCoupledStatistic;
use App\Tips\TipService;
use Illuminate\Http\Request;

class TipsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tips = (new Tip)->with(['statistics.educationProgramType', 'likes'])->orderBy('name', 'ASC')->get();


        return view('pages.tips.index')
            ->with('tips', $tips);
    }


    public function create()
    {
        return view('pages.tips.create')
            ->with('tip', new Tip);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param TipStoreRequest $request
     * @param TipService $tipService
     * @return \Illuminate\Http\Response
     */
    public function store(TipStoreRequest $request, TipService $tipService)
    {
        $tip = $tipService->createTip($request->all());

        $tip->save();

        return redirect()->route('tips.select-statistic', ['id' => $tip->id]);
    }

    public function selectStatistic(Tip $tip)
    {
        // Fetch statistics. If none have been coupled yet, fetch all statistics. If there are coupled ones, only show from same EP type
        $statistics = [];
        if ($tip->statistics()->count() === 0) {
            CustomStatistic::with('educationProgramType')->get()->each(function (CustomStatistic $statistic) use (
                &$statistics
            ) {
                $statistics[$statistic->id] = "{$statistic->name} ({$statistic->educationProgramType->eptype_name})";
            })->merge();

            collect(PredefinedStatisticHelper::getProducingData())
                ->each(function ($predefinedStatistic) use (&$statistics
                ) {
                    $statistics["predefined-{$predefinedStatistic['method']}"] = "{$predefinedStatistic['name']} (Producing)";
                });

            collect(PredefinedStatisticHelper::getActingData())
                ->each(function ($predefinedStatistic) use (&$statistics
                ) {
                    $statistics["predefined-{$predefinedStatistic['method']}"] = "{$predefinedStatistic['name']} (Acting)";
                });

        } else {
            $epType = $tip->statistics()->with('educationProgramType')->first()->educationProgramType;
            (new CustomStatistic)->where('education_program_type_id', '=',
                $epType->eptype_id)->get()->each(function (CustomStatistic $statistic) use (&$statistics) {
                $statistics[$statistic->id] = "{$statistic->name} ({$statistic->educationProgramType->eptype_name})";
            });
            if(strtolower($epType->eptype_name) === "acting") {
                collect(PredefinedStatisticHelper::getActingData())
                    ->each(function ($predefinedStatistic) use (&$statistics
                    ) {
                        $statistics["predefined-{$predefinedStatistic['method']}"] = "{$predefinedStatistic['name']} (Acting)";
                    });
            } elseif(strtolower($epType->eptype_name) === "producing") {
                collect(PredefinedStatisticHelper::getProducingData())
                    ->each(function ($predefinedStatistic) use (&$statistics
                    ) {
                        $statistics["predefined-{$predefinedStatistic['method']}"] = "{$predefinedStatistic['name']} (Producing)";
                    });

            }
        }

        $comparisonOperators = collect(TipCoupledStatistic::COMPARISON_OPERATORS)->flatMap(function (array $operator) {
            return [$operator['type'] => $operator['label']];
        });

        return view('pages.tips.addStatistic')
            ->with('tip', $tip)
            ->with('tipCoupledStatistic')
            ->with('statistics', $statistics)
            ->with('comparisonOperators', $comparisonOperators)
            ->with('alreadyCoupledStatistics', $tip->coupledStatistics);
    }

    public function coupleStatistic(TipCoupleStatisticRequest $request, Tip $tip, TipService $tipService, StatisticService $statisticService)
    {

        if(!starts_with($request->get('id'), 'predefined-')) {
            /** @var CustomStatistic $statistic */
            $statistic = (new CustomStatistic)->findOrFail($request->get('id'));
        } else {
            $statistic = $statisticService->createPredefinedStatistic(str_after($request->get('id'), 'predefined-'));
        }


        $tipService->coupleStatistic($tip, $statistic,
            $request->only('comparison_operator', 'threshold', 'multiplyBy100'));

        if ($request->get('save-and') === 'again') {
            return redirect()->route('tips.select-statistic', ['id' => $tip->id]);
        }

        if ($request->get('save-and') === 'continue') {
            return redirect()->route('tips.edit', ['id' => $tip->id]);
        }

        return redirect()->route('tips.index');
    }

    public function decoupleStatistic(Tip $tip, $tipCoupledStatisticId, TipService $tipService)
    {
        $tipService->decoupleStatistic($tip, $tipCoupledStatisticId);

        return redirect()->route('tips.edit', ['id' => $tip->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Tips\Tip $tip
     * @return \Illuminate\Http\Response
     */
    public function show(Tip $tip)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Tips\Tip $tip
     * @return \Illuminate\Http\Response
     */
    public function edit(Tip $tip)
    {
        $tip->load(['statistics.educationProgramType', 'statistics']);


        $cohorts = [];
        if (count($tip->coupledStatistics) > 0) {
            (new Cohort)->leftJoin('educationprogram', 'cohorts.ep_id', '=',
                'educationprogram.ep_id')->where('eptype_id', '=',
                $tip->coupledStatistics->first()->educationProgramType->eptype_id)
                ->orderBy('cohorts.name', 'ASC')
                ->get()->each(function (Cohort $cohort) use (&$cohorts) {
                    $cohorts[$cohort->id] = "{$cohort->name}";
                });
        }


        return view('pages.tips.edit')
            ->with('cohorts', $cohorts)
            ->with('statistics', $tip->coupledStatistics)
            ->with('tip', $tip);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param TipStoreRequest|Request $request
     * @param  \App\Tips\Tip $tip
     * @param TipService $tipService
     * @return \Illuminate\Http\Response
     */
    public function update(TipEditRequest $request, Tip $tip, TipService $tipService)
    {
        $tip = $tipService->setTipData($tip, $request->all());

        return redirect()->route('tips.edit', ['id' => $tip->id]);
    }

    public function updateCohorts(Request $request, Tip $tip, TipService $tipService)
    {
        $tip = $tipService->enableCohorts($tip, $request->all());

        return redirect()->route('tips.edit', ['id' => $tip->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Tips\Tip $tip
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tip $tip)
    {
        $tip->enabledCohorts()->detach();

        $tip->delete();

        return redirect()->route('tips.index');
    }

    public function likeTip(Tip $tip, TipService $tipService, Request $request) {
        $liked = $tipService->likeTip($tip, (int) $request->get('type', 1), $request->user());
        return response()->json(['status' => $liked ? 'success' : 'error']);
    }
}
