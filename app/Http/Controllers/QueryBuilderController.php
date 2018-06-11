<?php

namespace App\Http\Controllers;

use App\Analysis\QueryBuilder\Builder;
use App\Analysis\QueryBuilder\Models;
use App\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class QueryBuilderController extends Controller
{

    public function showStep(Request $request, $id) {

        if($id == 0 && $request->isMethod('get')) {

            $request->session()->put('builder', []);
            $data = [];
            return view("pages.analytics.builder.step1-type", compact("data"));
        }

        $data = $request->session()->get('builder');

        if($data['analysis_type'] == 'build') {

            switch($id) {
                case 1: return view("pages.analytics.builder.step1-type", compact("data")); break;
                case 2: return $this->step2($data); break;
                case 3: return view("pages.analytics.builder.step3-builder-filters", compact("data")); break;
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

            $request->session()->put('builder', array_replace_recursive($request->session()->get('builder'), $request->all()));
        }

        return json_encode(["step" => $id]);
    }

    private function step2($data) {

        $model = new Models();

        $models = $model->getAll();

        $relations = $model->getRelations('Cohort');

        return view("pages.analytics.builder.step2-builder", compact('models', 'relations', 'data'));
    }

    private function step4($data) {

        $table = $data['analysis_entity'];

        $relations = [];

        if(!empty($data['analysis_relation'])) {
            $relations = $data['analysis_relation'];
        }

        $select = ['category.category_label', \DB::raw('SUM(LearningActivityProducing.duration)')];
        $filters = [['WorkplaceLearningPeriod.cohort_id', '=', '1']];
        $groupBy = ['category.category_label'];

        $result = (new Builder())->getQuery($table, $relations, $select, $filters, $groupBy);

        return view("pages.analytics.builder.step4-chart", compact("data", "result"));
    }

    public function getTables(Request $request) {
        $data = $request->session()->get('builder');

        $entities[$data['analysis_entity']] = \Lang::get('querybuilder.'.$data['analysis_entity']);

        if(isset($data['analysis_relation'])) {

            foreach($data['analysis_relation'] as $relation) {

                $entities[$relation] = \Lang::get('querybuilder.'.$relation);
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

        return json_encode($result);
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