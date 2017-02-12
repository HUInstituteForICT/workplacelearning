<?php
namespace App\Http\Controllers;

use App\WorkplaceLearningPeriod;
use App\LearningActivityActing;
use App\ResourcePerson;
use App\ResourceMaterial;

use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Validator;

class ActingActivityController extends Controller {

    public function show() {
        $resourcePersons = Auth::user()->getEducationProgram()->getResourcePersons()->merge(
                Auth::user()->getCurrentWorkplaceLearningPeriod()->getResourcePersons()
        );

        return view('pages.acting.activity')
            ->with('timeslots', Auth::user()->getEducationProgram()->getTimeslots())
            ->with('resPersons', $resourcePersons)
            ->with('resMaterials', Auth::user()->getCurrentWorkplaceLearningPeriod()->getResourceMaterials())
            ->with('learningGoals', Auth::user()->getCurrentWorkplaceLearningPeriod()->getLearningGoals())
            ->with('competencies', Auth::user()->getEducationProgram()->getCompetencies())
            ->with('activities', Auth::user()->getCurrentWorkplaceLearningPeriod()->getLastActivity(8));
    }

    public function edit($id) {
        $activity = Auth::user()->getCurrentWorkplaceLearningPeriod()->getLearningActivityActingById($id);

        if (!$activity) {
            return redirect()->route('process-acting')
                ->withErrors('Helaas, er is geen activiteit gevonden.');
        }

        $resourcePersons = Auth::user()->getEducationProgram()->getResourcePersons()->merge(
                Auth::user()->getCurrentWorkplaceLearningPeriod()->getResourcePersons()
        );

        return view('pages.acting.activity-edit')
            ->with('activity', $activity)
            ->with('timeslots', Auth::user()->getEducationProgram()->getTimeslots())
            ->with('resPersons', $resourcePersons)
            ->with('resMaterials', Auth::user()->getCurrentWorkplaceLearningPeriod()->getResourceMaterials())
            ->with('learningGoals', Auth::user()->getCurrentWorkplaceLearningPeriod()->getLearningGoals())
            ->with('competencies', Auth::user()->getEducationProgram()->getCompetencies());
    }

    public function progress($pagenr){
        return view('pages.acting.progress')->with('page', $pagenr);
    }

    public function create(Request $req) {
        $a = new LearningActivityActing;

        $v = Validator::make($req->all(), [
            'date'                  => 'required|date|before:'.date('d-m-Y', strtotime('tomorrow')),
            'description'           => 'required|max:250|regex:/^[ 0-9a-zA-Z\-_,.?!*&%#()\/\\\\\'"\s]+\s*$/',
            'timeslot'              => 'required|exists:timeslot,timeslot_id',
            'new_rp'                => 'required_if:res_person,new|max:45|regex:/^[ 0-9a-zA-z(),.\/\\\\\']+$/',
            'new_rm'                => 'required_if:res_material,new|max:45|regex:/^[ 0-9a-zA-z(),.\/\\\\\']+$/',
            'learned'               => 'required|max:250|regex:/^[ 0-9a-zA-Z\-_,.?!*&%#()\/\\\\\'"\s]+\s*$/',
            'support_wp'            => 'max:125|regex:/^[ 0-9a-zA-Z\-_,.?!*&%#()\/\\\\\'"\s]+\s*$/',
            'support_ed'            => 'max:125|regex:/^[ 0-9a-zA-Z\-_,.?!*&%#()\/\\\\\'"\s]+\s*$/',
            'learning_goal'         => 'required|exists:learninggoal,learninggoal_id',
            'competence'            => 'required|exists:competence,competence_id'
        ]);

        // Conditional validation
        $v->sometimes('res_person', 'required|exists:resourceperson,rp_id', function($input) {
            return $input->res_person != 'new';
        });
        $v->sometimes('res_material', 'required|exists:resourcematerial,rm_id', function($input) {
            return $input->res_material != 'new' && $input->res_material != 'none';
        });
        /*$v->sometimes('res_material_detail', 'required_unless:res_material,none|max:75|url', function($input) {
            return $input->res_material == 1;
        });*/
        //temporarily disabled url validation for res_material_detail field
        $v->sometimes('res_material_detail', 'required_unless:res_material,none|max:75|regex:/^[ 0-9a-zA-z,.()\/\\\\\']+$/', function($input) {
            return $input->res_material >= 1;
        });

        if ($v->fails()) {
            return redirect()->route('process-acting')
                ->withErrors($v)
                ->withInput();
        }

        if ($req['res_person'] == 'new') {
            $p = new ResourcePerson;
            $p->person_label = $req['new_rp'];
            $p->ep_id = Auth::user()->getEducationProgram()->ep_id;
            $p->wplp_id = Auth::user()->getCurrentWorkplaceLearningPeriod()->wplp_id;
            $p->save();

            $req['res_person'] = $p->rp_id;
        }

        if ($req['res_material'] == 'new') {
            $m = new ResourceMaterial;
            $m->rm_label = $req['new_rm'];
            $m->wplp_id = Auth::user()->getCurrentWorkplaceLearningPeriod()->wplp_id;
            $m->save();

            $req['res_material'] = $m->rm_id;
        }

        $a->wplp_id = Auth::user()->getCurrentWorkplaceLearningPeriod()->wplp_id;
        $a->date = $req['date'];
        $a->timeslot_id = $req['timeslot'];
        $a->situation = $req['description'];
        $a->lessonslearned = $req['learned'];
        $a->support_wp = $req['support_wp'];
        $a->support_ed = $req['support_ed'];
        $a->res_person_id = $req['res_person'];
        ($req['res_material'] != 'none') ? $a->res_material_id = $req['res_material'] : null;
        $a->res_material_detail = $req['res_material_detail'];
        $a->learninggoal_id = $req['learning_goal'];
        $a->save();

        $a->competence()->attach($req['competence']);

        return redirect()->route('process-acting')->with('success', 'De leeractiviteit is opgeslagen.');
    }

    public function update(Request $req, $id) {
        $v = Validator::make($req->all(), [
            'date'                  => 'required|date|before:'.date('d-m-Y', strtotime('tomorrow')),
            'description'           => 'required|max:250|regex:/^[ 0-9a-zA-Z\-_,.?!*&%#()\/\\\\\'"\s]+\s*$/',
            'timeslot'              => 'required|exists:timeslot,timeslot_id',
            'new_rp'                => 'required_if:res_person,new|max:45|regex:/^[ 0-9a-zA-z(),.\/\\\\\']+$/',
            'new_rm'                => 'required_if:res_material,new|max:45|regex:/^[ 0-9a-zA-z(),.\/\\\\\']+$/',
            'learned'               => 'required|max:250|regex:/^[ 0-9a-zA-Z\-_,.?!*&%#()\/\\\\\'"\s]+\s*$/',
            'support_wp'            => 'max:125|regex:/^[ 0-9a-zA-Z\-_,.?!*&%#()\/\\\\\'"\s]+\s*$/',
            'support_ed'            => 'max:125|regex:/^[ 0-9a-zA-Z\-_,.?!*&%#()\/\\\\\'"\s]+\s*$/',
            'learning_goal'         => 'required|exists:learninggoal,learninggoal_id',
            'competence'            => 'required|exists:competence,competence_id'
        ]);

        // Conditional validation
        $v->sometimes('res_person', 'required|exists:resourceperson,rp_id', function($input) {
            return $input->res_person != 'new';
        });
        $v->sometimes('res_material', 'required|exists:resourcematerial,rm_id', function($input) {
            return $input->res_material != 'new' && $input->res_material != 'none';
        });
        /*$v->sometimes('res_material_detail', 'required_unless:res_material,none|max:75|url', function($input) {
            return $input->res_material == 1;
        });*/
        //temporarily disabled url validation for res_material_detail field
        $v->sometimes('res_material_detail', 'required_unless:res_material,none|max:75|regex:/^[ 0-9a-zA-z,.()\/\\\\\']+$/', function($input) {
            return $input->res_material >= 1;
        });

        if ($v->fails()) {
            return redirect()->route('process-acting-edit', ['id' => $id])
                ->withErrors($v)
                ->withInput();
        }

        $a = Auth::user()->getCurrentWorkplaceLearningPeriod()->getLearningActivityActingById($id);
        $a->date = $req['date'];
        $a->timeslot_id = $req['timeslot'];
        $a->situation = $req['description'];
        $a->lessonslearned = $req['learned'];
        $a->support_wp = $req['support_wp'];
        $a->support_ed = $req['support_ed'];
        $a->res_person_id = $req['res_person'];
        ($req['res_material'] != 'none') ? $a->res_material_id = $req['res_material'] : null;
        $a->res_material_detail = $req['res_material_detail'];
        $a->learninggoal_id = $req['learning_goal'];
        $a->save();

        $a->competence()->sync([$req['competence']]);

        return redirect()->route('process-acting')->with('success', 'De leeractiviteit is aangepast.');
    }
}
