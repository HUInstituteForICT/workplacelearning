<?php

namespace App\Http\Controllers;

use App\Analysis;
use App\AnalysisChart;
use App\ChartType;
use App\DashboardChart;
use App\Label;
use Illuminate\Http\Request;

class AnalyticsChartController extends Controller
{
    private $chart;
    private $chartType;
    private $analysis;
    private $dchart;

    public function __construct(AnalysisChart $chart, Analysis $analysis, ChartType $chartType, DashboardChart $dchart)
    {
        $this->chart = $chart;
        $this->chartType = $chartType;
        $this->analysis = $analysis;
        $this->dchart = $dchart;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $analyses = $this->analysis->has('charts')->get();
        return view('pages.charts.index', compact('analyses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $analyses = $this->analysis->all();
        $types = $this->chartType->all();
        return view('pages.charts.create', compact('analyses', 'types'));
    }

    /**
     * Show the form for creating a new resource
     *
     * @param Request $request
     * @return Response
     */
    public function create_step_2(Request $request)
    {
        $data = $request->all();
        $this->validate($request, [
            'analysis_id' => 'required|numeric',
            'type_id' => 'required|numeric'
        ]);
        $analysis = $this->analysis->findOrFail($data['analysis_id']);
        $type = $this->chartType->findOrFail($data['type_id']);
        $name = $data['name'];
        return view('pages.charts.create_step_2', compact('type', 'analysis', 'name'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        // perhaps store this in a session instead to avoid tampering?
        $data = $request->all();
        $analysis = $this->analysis->findOrFail($data['analysis_id']);
        $type = $this->chartType->findOrFail($data['type_id']);

        $chart = new $this->chart;
        $chart->analysis_id = $analysis->id;
        $chart->type_id = $type->id;
        $chart->label = $data['label'];

        $saved = false;
        \DB::transaction(function () use ($chart, $data, &$saved) {
            if ($chart->save()) {
                $chart->labels()->saveMany([
                    new Label(['chart_id' => $chart->id, 'name' => $data['x_axis'], 'type' => 'x']),
                    new Label(['chart_id' => $chart->id, 'name' => $data['y_axis'], 'type' => 'y'])
                ]);
                $saved = true;
            }
        });
        if (!$saved) {
            redirect()
                ->back()
                ->withInput()
                ->withErrors(['The chart has not been created']);
        }

        return redirect()->route('charts.show', $chart->id)
            ->with('success', 'The chart has been created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        $chart = $this->chart->findOrFail($id);
        $chart->load('analysis', 'type', 'labels');
        return view('pages.charts.show', compact('chart'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update($id)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        $chart = $this->chart->findOrFail($id);
        \DB::transaction(function () use ($chart) {
            if (!$chart->delete())
                return redirect()
                    ->back()
                    ->withErrors(['error', "Failed to remove the chart from the database."]);
            $this->dchart->where('chart_id', $chart->id)
                ->delete();
        });

        return redirect()->route('charts.index')
            ->with('success', 'Chart has been removed from the database');
    }

}

?>