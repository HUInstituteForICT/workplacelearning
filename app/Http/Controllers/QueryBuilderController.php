<?php

namespace App\Http\Controllers;

use App\Analysis\QueryBuilder\Models;
use Illuminate\Http\Request;

class QueryBuilderController extends Controller
{

    public function showStep(Request $request, $id) {

        if($id == 1 && $request->isMethod('get')) {

            $request->session()->put('builder', []);
            return view("pages.analytics.builder.step1-type");
        }

        $data = $request->session()->get('builder');

        if($data['analysis_type'] == 'build') {

            switch($id) {

                case 2: return $this->step2($data); break;
                case 3: return view("pages.analytics.builder.step3-builder-filters"); break;
                case 4: return view("pages.analytics.builder.step4-chart"); break;
            }
        } elseif($data['analysis_type'] == 'template') {

            switch($id) {
                case 1: return view("pages.analytics.builder.step1-type"); break;
                case 2: return view("pages.analytics.builder.step2-template"); break;
                case 4: return view("pages.analytics.builder.step4-chart"); break;
            }
        } elseif($data['analysis_type'] == 'custom') {

            switch($id) {
                case 1: return view("pages.analytics.builder.step1-type"); break;
                case 2: return view("pages.analytics.builder.step2-custom"); break;
                case 4: return view("pages.analytics.builder.step4-chart"); break;
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

        return view("pages.analytics.builder.step2-builder", compact('models', 'relations'));
    }

    public function getRelations($modelString) {

        //TODO: check if model exists in models array

        $model = new Models();

        $relations = $model->getRelations($modelString);

        return json_encode($relations);
    }
}