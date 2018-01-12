<?php

namespace App\Http\Controllers;

use App\EducationProgramType;
use App\Http\Requests\StatisticStoreRequest;
use App\Tips\CollectorDataAggregator;
use App\Tips\DataCollectors\CollectorFactory;
use App\Tips\Statistics\CustomStatistic;
use App\Tips\Statistics\Variables\StatisticStatisticVariable;
use App\Tips\StatisticService;
use App\Tips\Tip;
use DB;
use Illuminate\Http\Request;
use Lang;

class StatisticController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /** @var CustomStatistic[] $statistics */
        $statistics = (new CustomStatistic)->with(['educationProgramType'])->orderBy('name',
            'ASC')->get();

        $educationProgramTypes = EducationProgramType::all();

        return view('pages.statistics.index')
            ->with('statistics', $statistics)
            ->with('educationProgramTypes', $educationProgramTypes);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @param CollectorFactory $collectorFactory
     * @return \Illuminate\Http\Response
     * @internal param StatisticService $statisticService
     */
    public function create(Request $request, CollectorFactory $collectorFactory)
    {
        $educationProgramTypeId = (int)$request->get('id');
        $collector = $collectorFactory->buildCollector((new EducationProgramType)->find($educationProgramTypeId));

        $collectibleDataStatisticVariables = (new CollectorDataAggregator($collector))->getInformation();

        $availableStatisticStatisticVariables = (new CustomStatistic)
            ->where('education_program_type_id', '=', $educationProgramTypeId)
            ->orderBy('name', 'ASC')
            ->get();

        $availableStatisticStatisticVariables->map(function (CustomStatistic $statistic) {
            $statistic->type = (new StatisticStatisticVariable)->getType();

            return $statistic;
        });

        $statisticVariables = array_merge($collectibleDataStatisticVariables,
            $availableStatisticStatisticVariables->toArray());

        $operators = CustomStatistic::OPERATORS;

        return view('pages.statistics.create')
            ->with('statisticVariables', $statisticVariables)
            ->with('operators', $operators)
            ->with('educationProgramTypeId', $educationProgramTypeId);
    }

    /**
     * Store a newly created statistic
     *
     * @param StatisticStoreRequest $request
     * @param StatisticService $statisticService
     * @return \Illuminate\Http\Response
     */
    public function store(StatisticStoreRequest $request, StatisticService $statisticService)
    {
        $statistic = $statisticService->createStatistic($request->all());

        return response()->json(["status" => "success"]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Tips\Statistics\CustomStatistic $statistic
     * @return \Illuminate\Http\Response
     */
    public function destroy(CustomStatistic $statistic)
    {
        $isUsedByOtherStatistics = (new StatisticStatisticVariable)->where('nested_statistic_id', '=',
                $statistic->id)->count() > 0;
        if ($isUsedByOtherStatistics) {

            $names = DB::select("SELECT name FROM statistics WHERE 
                                        statistic_variable_one_id IN (SELECT id FROM statistic_variables WHERE nested_statistic_id = ?)
                                        OR statistic_variable_two_id IN (SELECT id FROM statistic_variables WHERE nested_statistic_id = ?)",
                [$statistic->id, $statistic->id]);

            $namesString = implode(",", array_map(function ($nameObj) {
                return $nameObj->name;
            }, $names));

            return redirect()->route('statistics.index')->withErrors(Lang::get('statistics.is-nested-by-others',
                ["statistics" => $namesString]));
        }

        $statistic->statisticVariableOne()->delete();
        $statistic->statisticVariableTwo()->delete();
        $statistic->tips()->detach();
        $statistic->delete();

        return redirect()->route('statistics.index');
    }
}
