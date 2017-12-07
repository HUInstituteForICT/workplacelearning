<?php

namespace App\Http\Controllers;

use App\Cohort;
use App\Http\Requests\TipStoreRequest;
use App\Tips\Statistic;
use App\Tips\Tip;
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
        $tips = (new Tip)->with(['statistic.educationProgramType'])->orderBy('name', 'ASC')->get();

        $statistics = [];
        (new Statistic)->with('educationProgramType')->get()->each(function (Statistic $statistic) use (&$statistics) {
            $statistics[$statistic->id] = "{$statistic->name} ({$statistic->educationProgramType->eptype_name})";
        });


        return view('pages.tips.index')
            ->with('statistics', $statistics)
            ->with('newTip', new Tip)
            ->with('tips', $tips);
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
        $tip = $tipService->setTipData(new Tip, $request->all());

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
        $tip->load(['statistic.educationProgramType',]);
        $statistics = [];
        (new Statistic)->with('educationProgramType')->get()->each(function (Statistic $statistic) use (&$statistics) {
            $statistics[$statistic->id] = "{$statistic->name} ({$statistic->educationProgramType->eptype_name})";
        });

        $cohorts = [];
        (new Cohort)->leftJoin('educationprogram', 'cohorts.ep_id', '=',
            'educationprogram.ep_id')->where('eptype_id', '=', $tip->statistic->educationProgramType->eptype_id)
            ->orderBy('cohorts.name', 'ASC')
            ->get()->each(function (Cohort $cohort) use (&$cohorts) {
                $cohorts[$cohort->id] = "{$cohort->name}";
            });


        return view('pages.tips.edit')
            ->with('cohorts', $cohorts)
            ->with('statistics', $statistics)
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
    public function update(TipStoreRequest $request, Tip $tip, TipService $tipService)
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
}
