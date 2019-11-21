<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Analysis;
use App\AnalysisChart;
use App\ChartType;
use App\DashboardChart;
use App\Label;
use App\LearningActivityActing;
use App\LearningActivityProducing;
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
     */
    public function index()
    {
        $analyses = $this->analysis->has('charts')->get();

        return view('pages.charts.index', compact('analyses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $analyses = $this->analysis->all();
        $types = $this->chartType->all();

        return view('pages.charts.create', compact('analyses', 'types'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create_step_2(Request $request)
    {
        $data = $request->all();
        $this->validate($request, [
            'analysis_id' => 'required|numeric',
            'type_id'     => 'required|numeric',
        ]);
        $analysis = $this->analysis->findOrFail($data['analysis_id']);
        $type = $this->chartType->findOrFail($data['type_id']);
        $name = $data['name'];

        return view('pages.charts.create_step_2', compact('type', 'analysis', 'name'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // perhaps store this in a session instead to avoid tampering?
        $data = $request->all();
        $analysis = $this->analysis->findOrFail($data['analysis_id']);
        $type = $this->chartType->findOrFail($data['type_id']);

        $chart = new $this->chart();
        $chart->analysis_id = $analysis->id;
        $chart->type_id = $type->id;
        $chart->label = $data['label'];

        $saved = false;
        \DB::transaction(function () use ($chart, $data, &$saved): void {
            if ($chart->save()) {
                $chart->labels()->saveMany([
                    new Label(['chart_id' => $chart->id, 'name' => $data['x_axis'], 'type' => 'x']),
                    new Label(['chart_id' => $chart->id, 'name' => $data['y_axis'], 'type' => 'y']),
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
     * @param int $id
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
     * @param int $id
     */
    public function edit($id)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     */
    public function update($id)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     */
    public function destroy($id)
    {
        $chart = $this->chart->findOrFail($id);
        \DB::transaction(function () use ($chart) {
            if (!$chart->delete()) {
                return redirect()
                    ->back()
                    ->withErrors(['error', __('charts.removed-fail')]);
            }
            $this->dchart->where('chart_id', $chart->id)
                ->delete();
        });

        return redirect()->route('charts.index')
            ->with('success', __('charts.removed'));
    }

    public function getChartDetails($idLabel)
    {
        $array = explode(';', $idLabel);
        $analysisID = $array[0];
        $label = $array[1];

        $queryResult = (new \App\Analysis())->where('id', $analysisID)->get(['query']);
        if ($queryResult[0] != null) {
            $query = $queryResult[0]['query'];
            if ($query != null) {
                // Splitting the query on new line and space
                $queryArray = preg_split('/[\s]+/', $query);

                // Trying to get the EducationProgramType from the query
                $programTypeID = null;

                $cohort = null;

                for ($i = 0; $i < count($queryArray); ++$i) {
                    $index = stripos($queryArray[$i], 'FROM');
                    if ($index > -1 && strtolower($queryArray[$i + 1]) == 'learningactivityproducing') {
                        $programTypeID = 2;
                        break;
                    }
                    $index = stripos($queryArray[$i], 'FROM');
                    if ($index > -1 && strtolower($queryArray[$i + 1]) == 'learningactivityacting') {
                        $programTypeID = 1;
                        break;
                    }

                    $index = stripos($queryArray[$i], 'cohort_id');

                    if ($index > -1 && $queryArray[$i + 1] == '=' && is_numeric($queryArray[$i + 2])) {
                        $cohort = $queryArray[$i + 2];
                    }
                }

                if ($programTypeID == null || $programTypeID == 2) {
                    $id = (new \App\Category())->where('category_label', $label)
                        ->where(function ($query) use ($cohort): void {
                            if ($cohort != null) {
                                $query->where('cohort_id', $cohort);
                            }
                        })
                        ->pluck('category_id')->toArray();

                    if (!empty($id)) {
                        $details = (new LearningActivityProducing())->whereIn('category_id', $id)->get(['description', 'duration']);

                        return $details;
                    }
                } else {
                    $id = (new \App\Timeslot())->where('timeslot_text', $label)
                        ->where(function ($query) use ($cohort): void {
                            if ($cohort != null) {
                                $query->where('cohort_id', $cohort);
                            }
                        })
                        ->pluck('timeslot_id')->toArray();

                    if (!empty($id)) {
                        $details = (new LearningActivityActing())->whereIn('timeslot_id', $id)->get(['situation as description']);

                        return $details;
                    }
                }
            }
        }

        return [];
    }
}
