<?php

namespace App\Http\Controllers;

use App\Analysis\QueryBuilder\Models;
use Illuminate\Http\Request;

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
                case 4: return view("pages.analytics.builder.step4-chart", compact("data")); break;
            }
        } elseif($data['analysis_type'] == 'template') {

            switch($id) {
                case 1: return view("pages.analytics.builder.step1-type", compact("data")); break;
                case 2: return view("pages.analytics.builder.step2-template", compact("data")); break;
                case 4: return view("pages.analytics.builder.step4-chart", compact("data")); break;
            }
        } elseif($data['analysis_type'] == 'custom') {

            switch($id) {
                case 1: return view("pages.analytics.builder.step1-type", compact("data")); break;
                case 2: return view("pages.analytics.builder.step2-custom", compact("data")); break;
                case 4: return view("pages.analytics.builder.step4-chart", compact("data")); break;
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

    public function getRelations($modelString) {

        //TODO: check if model exists in models array

        $model = new Models();

        $relations = $model->getRelations($modelString);

        return json_encode($relations);
    }
}