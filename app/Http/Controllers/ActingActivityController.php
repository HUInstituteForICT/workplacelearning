<?php
namespace App\Http\Controllers;

use App\WorkplaceLearningPeriod;
use App\LearningActivityActing;
use App\ResourcePerson;

use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Validator;

class ActingActivityController extends Controller {
    public function __construct(){
        $this->middleware('auth');
    }

    public function show() {
        $resourcePersons = Auth::user()->getEducationProgram()->getResourcePersons()->union(
                Auth::user()->getCurrentWorkplaceLearningPeriod()->getResourcePersons()
        );

        return view('pages.actingactivity')
            ->with('timeslots', Auth::user()->getEducationProgram()->getTimeslots())
            ->with('resPersons', $resourcePersons)
            ->with('resMaterials', Auth::user()->getCurrentWorkplaceLearningPeriod()->getResourceMaterials())
            ->with('learningGoals', Auth::user()->getCurrentWorkplaceLearningPeriod()->getLearningGoals())
            ->with('competencies', Auth::user()->getEducationProgram()->getCompetencies())
            ->with('activities', Auth::user()->getCurrentWorkplaceLearningPeriod()->getLastActivity(8));
    }

    public function create(Request $req) {
        $a = new LearningActivityActing;

        $v = Validator::make($req->all(), [
            'date'                  => 'required|date|before:'.date('d-m-Y', strtotime('tomorrow')),
            'description'           => 'required|regex:/^[ 0-9a-zA-Z-_,.?!*&%#()\'"]+$/',
            'timeslot'              => 'required|exists:timeslot,timeslot_id',
            //'res_person'            => 'required_unless:res_person,new|exists:resourceperson,rp_id',
            'res_material'          => 'required|exists:resourcematerial,rm_id',
            //'res_material_detail'   => 'required_unless:res_material,none|url',
            'learned'               => 'required|regex:/^[ 0-9a-zA-Z-_,.?!*&%#()\'"]+$/',
            'support_wp'            => 'required|regex:/^[ 0-9a-zA-Z-_,.?!*&%#()\'"]+$/',
            'support_ed'            => 'required|regex:/^[ 0-9a-zA-Z-_,.?!*&%#()\'"]+$/',
            'learning_goal'         => 'required|exists:learninggoal,learninggoal_id',
            'competence'            => 'required|exists:competence,competence_id'
        ]);

        if ($v->fails()) {
            $req->flash();
            return redirect('acting')
                ->withErrors($v)
                ->withInput($req->all());
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
            $m->rm_lavel = $req['new_rm'];
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
        $a->res_material_id = $req['res_material'];
        $a->res_material_detail = $req['res_material_detail'];
        $a->learninggoal_id = $req['learning_goal'];
        $a->save();

        $a->competence()->attach($req['competence']);

        return redirect('acting')->with('success', 'De leeractiviteit is opgeslagen.');
    }
}
