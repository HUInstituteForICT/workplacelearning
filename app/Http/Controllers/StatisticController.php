<?php

namespace App\Http\Controllers;

use App\Http\Requests\StatisticStoreRequest;
use App\Tips\ActingCollector;
use App\Tips\CollectibleDataAggregator;
use App\Tips\ProducingCollector;
use App\Tips\Statistic;
use App\Tips\StatisticService;
use App\Tips\StatisticStatisticVariable;
use App\WorkplaceLearningPeriod;
use Illuminate\Http\Request;

class StatisticController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /** @var Statistic[] $statistics */
        $statistics = (new Statistic)->with(['statisticVariableOne', 'statisticVariableTwo'])->orderBy('name',
            'ASC')->get();

        return view('pages.statistics.index')
            ->with('statistics', $statistics);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $educationProgramType = (int) $request->get('type');
        $collector = $educationProgramType === Statistic::EDUCATION_PROGRAM_TYPE_ACTING ?
            new ActingCollector(null, null, new WorkplaceLearningPeriod()) :
            new ProducingCollector(null, null, new WorkplaceLearningPeriod());

        $collectibleDataStatisticVariables = (new CollectibleDataAggregator($collector))->getInformation();

        $availableStatisticStatisticVariables = (new Statistic)
            ->where('educationProgramType', '=', $educationProgramType)
            ->orderBy('name', 'ASC')
            ->get();

        $availableStatisticStatisticVariables->map(function(Statistic $statistic) {
            $statistic->type = (new StatisticStatisticVariable)->getType();
            return $statistic;
        });

        $statisticVariables = array_merge($collectibleDataStatisticVariables, $availableStatisticStatisticVariables->toArray());

        $operators = [
            ["type" => Statistic::OPERATOR_ADD, "label" => "+"],
            ["type" => Statistic::OPERATOR_SUBTRACT, "label" => "-"],
            ["type" => Statistic::OPERATOR_MULTIPLY, "label" => "*"],
            ["type" => Statistic::OPERATOR_DIVIDE, "label" => "/"],
        ];


        return view('pages.statistics.create')
            ->with('statisticVariables', $statisticVariables)
            ->with('operators', $operators);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(StatisticStoreRequest $request, StatisticService $statisticService)
    {
        $statistic = $statisticService->createStatistic($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Tips\Statistic $statistic
     * @return \Illuminate\Http\Response
     */
    public function show(Statistic $statistic)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Tips\Statistic $statistic
     * @return \Illuminate\Http\Response
     */
    public function edit(Statistic $statistic)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Tips\Statistic $statistic
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Statistic $statistic)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Tips\Statistic $statistic
     * @return \Illuminate\Http\Response
     */
    public function destroy(Statistic $statistic)
    {
        //
    }
}
