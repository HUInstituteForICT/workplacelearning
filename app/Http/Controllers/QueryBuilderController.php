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

    public function showStep(Request $request, $id)
    {
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
                case 2: return $this->step2template($data); break;
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

    public function saveStep(Request $request, $id)
    {
        $oldData = $request->session()->get('builder');

        $request->session()->put('builder', array_replace($request->session()->get('builder'), $request->all()));

        switch($id) {

            case 3:

                if(isset($oldData['analysis_entity']) && $oldData['step'] == 2) {

                    if($request->input('analysis_entity') != $oldData['analysis_entity']) {

                        $request->session()->put('builder', array_merge($request->all(), ['analysis_type' => $oldData['analysis_type']]));
                    }
                }
                break;

            case 4:

                $data = $request->session()->get('builder');

                $result = [];

                switch($data['analysis_type']) {

                    case 'build':
                        $table = (isset($data['analysis_entity']) ? $data['analysis_entity'] : []);
                        $relations = (isset($data['analysis_relation']) ? $data['analysis_relation'] : []);

                        $select = (isset($data['query_data']) ? $data['query_data'] : []);
                        $filters = (isset($data['query_filter']) ? $data['query_filter'] : []);
                        $sort = (isset($data['query_sort']) ? $data['query_sort'] : []);
                        $limit = null;

                        $result = (new Builder)->getData($table, $relations, $select, $filters, $sort, $limit)->toArray();

                        if(isset($result['error']))
                            return json_encode($result);
                        break;

                    case 'template':

                        $analyse = new Analysis();
                        $analyse->query = $data['realQuery'];
                        $result = $analyse->execute();

                        break;

                    case 'custom':
                        $analyse = new Analysis();
                        $analyse->query = $data['customQuery'];
                        $result = $analyse->execute();
                        break;
                }

                $request->session()->put('builder', array_replace($request->session()->get('builder') , ['result' => $result]));
                break;

            case 5:
                $data = $request->session()->get('builder');

                $errors = [];

                if(empty($data['name']))
                    $errors[] = \Lang::get('querybuilder.step4.error-name');

                if(empty($data['cache_duration']) || empty($data['type_time']))
                    $errors[] = \Lang::get('querybuilder.step4.error-cache-duration');

                if(empty($data['type_id']))
                    $errors[] = \Lang::get('querybuilder.step4.error-chart-id');

                if(empty($data['x_axis']) || empty($data['y_axis']))
                    $errors[] = \Lang::get('querybuilder.step4.error-axis');

                if(!empty($errors)) {

                    return json_encode(['error' => $errors]);
                }

                $this->step5($request->session()->get('builder'));
                break;
        }

        $request->session()->put('builder', array_replace($request->session()->get('builder'), ['step' => $id]));
        return json_encode(["step" => $id]);
    }

    private function step2($data)
    {
        $model = new Models;

        $models = $model->getAll();

        $relations = $model->getRelations((isset($data['analysis_entity'])) ? $data['analysis_entity'] : $models[0]);

        return view("pages.analytics.builder.step2-builder", compact('models', 'relations', 'data'));
    }

    private function step2template($data) {

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
            $query = str_replace(["\r", "\n"], '@', $query);
            $query = str_replace("'", "š", $query);
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

        return view("pages.analytics.builder.step2-template", compact("data", "templates", "columnNames"));
    }

    private function step3($data)
    {
        $modelString = 'App\\' . $data['analysis_entity'];
        $mainModel = new $modelString();

        $relations = [];

        $columns[$data['analysis_entity']] = $this->getColumns($data['analysis_entity']);
        $selectedModels = null;

        if(isset($data['analysis_relation'])) {

            foreach($data['analysis_relation'] as $r) {

                $class = class_basename(get_class($mainModel->$r()->getRelated()));

                $relations[] = $class;
                $columns[$class] = $this->getColumns($class);
            }
        }

        return view("pages.analytics.builder.step3-builder-filters",
            compact('data', 'relations', 'columns', 'selectedModels'));
    }

    private function step4($data)
    {
        $labels = [];

        if(isset($data['result'][0]))
            $labels = array_keys(get_object_vars($data['result'][0]));
        elseif($data['analysis_type'] == 'build') {

            $select = (isset($data['query_data']) ? $data['query_data'] : []);
            $labels = array_unique((new Builder)->getSelectFields($select));
        }

        $chartTypes = ChartType::whereNotNull('slug')->get();

        return view("pages.analytics.builder.step4-chart", compact("data", "result", "labels", "chartTypes"));
    }

    private function step5($data)
    {
        switch($data['analysis_type']) {

            case 'build':
                $table = $data['analysis_entity'];

                $relations = [];

                if(!empty($data['analysis_relation']))
                    $relations = $data['analysis_relation'];

                $select = [];
                $filters = [];
                $sort = [];
                $limit = null;

                if(isset($data['query_data']))
                    $select = $data['query_data'];

                if(isset($data['query_filter']))
                    $filters = $data['query_filter'];

                if(isset($data['query_sort']))
                    $sort = $data['query_sort'];

                if(isset($data['query_limit']) && $data['query_limit'])
                    $limit = $data['query_limit'];

                $query = (new Builder())->getQuery($table, $relations, $select, $filters, $sort, $limit);
                break;

            case 'template':
                $query = $data['realQuery'];
                break;

            case 'custom':
                $query = $data['customQuery'];
                break;
        }

        $analysis = new Analysis();
        $analysis->name = $data['name'];
        $analysis->cache_duration = $data['cache_duration'];
        $analysis->type_time = $data['type_time'];
        $analysis->query = $query;
        $analysis->save();

        $chart = new AnalysisChart();
        $chart->analysis_id = $analysis->id;
        $chart->type_id = $data['type_id'];
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

    public function getTables(Request $request)
    {
        $data = $request->session()->get('builder');

        $entities = [];

        if(isset($data['analysis_entity']))
            $entities[$data['analysis_entity']] = \Lang::get('querybuilder.'.$data['analysis_entity']);

        if(isset($data['analysis_relation'])) {

            foreach($data['analysis_relation'] as $relation) {

                $tableString = 'App\\' .$data['analysis_entity'];
                $tableModel = new $tableString();
                $r = class_basename(get_class($tableModel->$relation()->getRelated()));

                $entities[$r] = \Lang::get('querybuilder.'.$r);
            }
        }

        return json_encode($entities);
    }

    public function getColumns($table)
    {
        return (new Models())->getColumns($table);
    }

    public function getRelations($modelString)
    {
        return (new Models())->getRelations($modelString);
    }

    public function testQuery(Request $request)
    {
        $analyse = new Analysis();
        $analyse->query = $request->getContent();
        return $analyse->execute();
    }

    public function executeQuery(Request $request)
    {
        $sessionData = $request->session()->get('builder');

        $table = (isset($sessionData['analysis_entity']) ? $sessionData['analysis_entity'] : []);
        $relations = (isset($sessionData['analysis_relation']) ? $sessionData['analysis_relation'] : []);

        $select = [];
        $filters = [];
        $sort = [];
        $limit = 10;

        if($request->input('query_data'))
            $select = $request->input('query_data');

        if($request->input('query_filter'))
            $filters = $request->input('query_filter');

        if($request->input('query_sort'))
            $sort = $request->input('query_sort');

        if($request->input('query_limit') && $request->input('query_limit') < 10)
            $limit = $request->input('query_limit');

        $result = (new Builder())->getData($table, $relations, $select, $filters, $sort, $limit);

        return $result;
    }

    public function getChart(Request $request)
    {
        $result = $request->session()->get('builder')['result'];

        $chartType = ChartType::find($request->input('type'));

        $slug = 'pie';

        if($chartType)
            $slug = $chartType->slug;

        $x_label = $request->input('x');
        $y_label = $request->input('y');

        $title = $request->input('name');

        return view('pages.analytics.builder.step4-getchart', compact('slug', 'x_label', 'y_label', 'result', 'title'));
    }

    public function getColumnValues($table, $column)
    {
        $query = "select " . $column . " from " . $table . ";";
        return DB::connection('dashboard')->select($query);
    }

}