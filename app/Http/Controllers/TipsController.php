<?php

namespace App\Http\Controllers;

use App\Cohort;
use App\Http\Requests\TipCoupleStatisticRequest;
use App\Http\Requests\TipEditRequest;
use App\Http\Requests\TipStoreRequest;
use App\Tips\Statistic;
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
            (new Statistic)->with('educationProgramType')->get()->each(function (Statistic $statistic) use (&$statistics
            ) {
                $statistics[$statistic->id] = "{$statistic->name} ({$statistic->educationProgramType->eptype_name})";
            });
        } else {
            $epType = $tip->statistics()->with('educationProgramType')->first()->educationProgramType;
            (new Statistic)->where('education_program_type_id', '=',
                $epType->eptype_id)->get()->each(function (Statistic $statistic) use (&$statistics) {
                $statistics[$statistic->id] = "{$statistic->name} ({$statistic->educationProgramType->eptype_name})";
            });
        }

        $comparisonOperators = collect(TipCoupledStatistic::COMPARISON_OPERATORS)->flatMap(function (array $operator) {
            return [$operator['type'] => $operator['label']];
        });

        return view('pages.tips.addStatistic')
            ->with('tip', $tip)
            ->with('tipCoupledStatistic')
            ->with('statistics', $statistics)
            ->with('comparisonOperators', $comparisonOperators)
            ->with('alreadyCoupledStatistics', $tip->statistics);
    }

    public function coupleStatistic(TipCoupleStatisticRequest $request, Tip $tip, TipService $tipService)
    {
        /** @var Statistic $statistic */
        $statistic = (new Statistic)->findOrFail($request->get('id'));
        $tipService->coupleStatistic($tip, $statistic,
            $request->only('comparison_operator', 'threshold', 'multiplyBy100'));

        if ($request->get('save-and') === 'again') {
            return redirect()->route('tips.select-statistic', ['id' => $tip->id]);
        } elseif ($request->get('save-and') === 'continue') {
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
        $tip->load(['statistics.educationProgramType',]);


        $cohorts = [];
        if (count($tip->statistics) > 0) {
            (new Cohort)->leftJoin('educationprogram', 'cohorts.ep_id', '=',
                'educationprogram.ep_id')->where('eptype_id', '=',
                $tip->statistics->first()->educationProgramType->eptype_id)
                ->orderBy('cohorts.name', 'ASC')
                ->get()->each(function (Cohort $cohort) use (&$cohorts) {
                    $cohorts[$cohort->id] = "{$cohort->name}";
                });
        }


        return view('pages.tips.edit')
            ->with('cohorts', $cohorts)
            ->with('statistics', $tip->statistics)
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
        $liked = $tipService->likeTip($tip, $request->user());
        return response()->json(['status' => $liked ? 'success' : 'error']);
    }
}
