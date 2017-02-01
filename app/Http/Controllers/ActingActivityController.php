<?php
namespace App\Http\Controllers;

use App\WorkplaceLearningPeriod;
use App\LearningActivityActing;
use App\ActivityForCompetence;

use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

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
        $c = new ActivityForCompetence;

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

        $c->competence_id = $req['competence'];
        $c->learningactivity_id = $a->laa_id;
        $c->save();
    }
}
