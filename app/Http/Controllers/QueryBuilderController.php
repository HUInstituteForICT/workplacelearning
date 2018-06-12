<?php

namespace App\Http\Controllers;

use App\Analysis\QueryBuilder\Builder;
use App\Analysis\QueryBuilder\Models;
use App\DashboardChart;
use App\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Analysis;
use App\ChartType;
use App\AnalysisChart;
use App\Label;

class QueryBuilderController extends Controller
{
    public function testQuery(Request $request) {
        $analyse = new Analysis();
        $analyse->query = $request->getContent();
        $DataResultQuery = $analyse->execute();
        return $DataResultQuery;
    }

    public function showStep(Request $request, $id) {

        if($id == 0 && $request->isMethod('get')) {

            $request->session()->put('builder', []);
            $data = [];
            return view("pages.analytics.builder.step1-type", compact("data"));
        }

        $data = $request->session()->get('builder');

        if(empty($data['analysis_type']))
            die;

        if($data['analysis_type'] == 'build') {

            switch($id) {
                case 1: return view("pages.analytics.builder.step1-type", compact("data")); break;
                case 2: return $this->step2($data); break;
                case 3: return $this->step3($data); break;
                case 4: return $this->step4($data); break;
            }
        } elseif($data['analysis_type'] == 'template') {

            switch($id) {
                case 1: return view("pages.analytics.builder.step1-type", compact("data")); break;
                case 2: {
                    $templates = Template::all();
                    $needle = "?";

                    foreach ($templates as $template) {
                        $query = $template->query;
                        $parameters = $template->getParameters();

                        foreach($parameters as $parameter) {
                            $pos = strpos($query, $needle);
                            if ($pos !== false) {
                                $query = substr_replace($query, $parameter->name, $pos, strlen($needle));
                            }
                        }
                        $template->query = $query;
                    }
                    $tables = DB::connection('dashboard')->select('SHOW TABLES');
                    $tableNames = array_map(function ($object) {
                        return $object->{'Tables_in_' . DB::connection('dashboard')->getDatabaseName()};
                    }, $tables);


                    $columnNames = [];
                    foreach ($tableNames as $table) {
                        $columnNames[$table] = DB::connection('dashboard')->getSchemaBuilder()->getColumnListing($table);
                    }

                    return view("pages.analytics.builder.step2-template", compact("data", "templates", "tableNames", "columnNames")); break;
                }
                case 4: return $this->step4($data); break;
            }
        } elseif($data['analysis_type'] == 'custom') {

            switch($id) {
                case 1: return view("pages.analytics.builder.step1-type", compact("data")); break;
                case 2: return view("pages.analytics.builder.step2-custom", compact("data")); break;
                case 4: return $this->step4($data); break;
            }
        }
    }

    public function saveStep(Request $request, $id) {

        if($request->isMethod('post')) {

            $request->session()->put('builder', array_replace($request->session()->get('builder'), $request->all()));
        }

        if($id == 5) {

            $this->step5($request->session()->get('builder'));
        }

        return json_encode(["step" => $id]);
    }

    private function step2($data) {

        $model = new Models();

        $models = $model->getAll();

        $relations = [];

        $relations = $model->getRelations((isset($data['analysis_entity'])) ? $data['analysis_entity'] : $models[0]);

        return view("pages.analytics.builder.step2-builder", compact('models', 'relations', 'data'));
    }

    private function step3($data) {

        $modelString = 'App\\' . $data['analysis_entity'];
        $mainModel = new $modelString();

        $relations = [];

        if(isset($data['analysis_relation'])) {

            foreach($data['analysis_relation'] as $r) {

                $relations[] = class_basename(get_class($mainModel->$r()->getRelated()));
            }
        }

        return view("pages.analytics.builder.step3-builder-filters", compact('data', 'relations'));
    }

    private function step4($data) {

        switch($data['analysis_type']) {

            case 'builder':

                $table = $data['analysis_entity'];

                $relations = [];

                if(isset($data['analysis_relation'])) {
                    $relations = $data['analysis_relation'];
                }

                $select = [];
                $filters = [];
                $groupBy = [];
                $limit = null;

                if(isset($data['query_data'])) {

                    $select = $data['query_data'];
                }

                if(isset($data['query_filter'])) {

                    $filters = $data['query_filter'];
                }

                $result = (new Builder())->getData($table, $relations, $select, $filters, $groupBy, 10);

                $labels = array_keys(get_object_vars($result[0]));

                break;

            case 'template':

                $result = 'query resultaat';
                $labels[0] = 'standaard x';
                $labels[1] = 'standaard y';

                break;

            case 'custom':

                $result = 'query resultaat';
                $labels[0] = 'standaard x';
                $labels[1] = 'standaard y';

                break;
        }

        return view("pages.analytics.builder.step4-chart", compact("data", "result", "labels"));
    }

    private function step5($data) {

        $table = $data['analysis_entity'];

        $relations = [];

        if(!empty($data['analysis_relation'])) {
            $relations = $data['analysis_relation'];
        }

        $select = [];
        $filters = [];
        $groupBy = [];
        $limit = null;

        if(isset($data['query_data'])) {

            $select = $data['query_data'];
        }

        if(isset($data['query_filter'])) {

            $filters = $data['query_filter'];
        }

        $query = (new Builder())->getQuery($table, $relations, $select, $filters, $groupBy);

        $analysis = new Analysis();
        $analysis->name = $data['name'];
        $analysis->cache_duration = $data['cache_duration'];
        $analysis->type_time = $data['type_time'];
        $analysis->query = $query;
        $analysis->save();

        $type = ChartType::findOrFail(4);

        $chart = new AnalysisChart();
        $chart->analysis_id = $analysis->id;
        $chart->type_id = $type->id;
        $chart->label = $data['name'];

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

        $position = DB::table('dashboard_charts')->max('position') + 1;

        $dchart = new DashboardChart();
        $dchart->position = $position;
        $dchart->chart_id = $chart->id;
        $dchart->save();

        return true;
    }

    public function getTables(Request $request) {
        $data = $request->session()->get('builder');

        $entities[$data['analysis_entity']] = \Lang::get('querybuilder.'.$data['analysis_entity']);

        if(isset($data['analysis_relation'])) {

            foreach($data['analysis_relation'] as $relation) {

                $tableString = 'App\\' .$data['analysis_entity'];
                $tableModel = new $tableString();
                $r = class_basename(get_class($tableModel->$relation()->getRelated()));

                $entities[$relation] = \Lang::get('querybuilder.'.$r);
            }
        }

        return json_encode($entities);
    }

    public function getColumns($table) {

        $model = new Models();

        $columns = $model->getColumns($table);

        return $columns;
    }

    public function getRelations($modelString) {

        $model = new Models();

        $relations = $model->getRelations($modelString);

        return json_encode($relations);
    }

    public function executeQuery(Request $request) {

        $sessionData = $request->session()->get('builder');

        $table = (isset($sessionData['analysis_entity']) ? $sessionData['analysis_entity'] : []);
        $relations = (isset($sessionData['analysis_relation']) ? $sessionData['analysis_relation'] : []);

        $select = [];
        $filters = [];
        $groupBy = [];
        $limit = null;

        if($request->input('query_data')) {

            $select = $request->input('query_data');
        }

        if($request->input('query_filter')) {

            $filters = $request->input('query_filter');
        }

        $result = (new Builder())->getData($table, $relations, $select, $filters, $groupBy, 10);

        return $result;
    }

    public function getChart() {

        $chart = \App\AnalysisChart::find(7);
        $chart->load('analysis', 'type', 'labels');
        return view('pages.analytics.builder.step4-getchart', compact('chart'));
    }

    public function getColumnValues($table, $column)
    {
        $query = "select " . $column . " from " . $table . ";";
        return DB::connection('dashboard')->select($query);
    }

    public function save(Request $request)
    {
        // Zie voorbeeld in TemplateBuilderController, nog maken.
    }

}