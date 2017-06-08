<?php
namespace App\Http\Controllers;

use App\LearningActivityExportBuilder;
use App\WorkplaceLearningPeriod;
use App\LearningActivityActing;
use App\ResourcePerson;
use App\ResourceMaterial;

use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Validator;

class ActingActivityController extends Controller
{

    public function show()
    {
        // Allow only to view this page if an internship exists.
        if (Auth::user()->getCurrentWorkplaceLearningPeriod() == null) {
            return redirect()->route('profile')->withErrors(['Je kan geen activiteiten registreren zonder (actieve) stage.']);
        }

        $resourcePersons = Auth::user()->getEducationProgram()->getResourcePersons()->merge(
            Auth::user()->getCurrentWorkplaceLearningPeriod()->getResourcePersons()
        );

        $exportBuilder = new LearningActivityExportBuilder(Auth::user()->getCurrentWorkplaceLearningPeriod()->learningActivityActing()
            ->with('timeslot', 'resourcePerson', 'resourceMaterial', 'learningGoal', 'competence')
            ->take(50)
            ->get());

        $activitiesJson = $exportBuilder->getJson();

        $exportTranslatedFieldMapping = $exportBuilder->getFieldLanguageMapping(app()->make('translator'));

        return view('pages.acting.activity')
            ->with('timeslots', Auth::user()->getEducationProgram()->getTimeslots())
            ->with('resPersons', $resourcePersons)
            ->with('resMaterials', Auth::user()->getCurrentWorkplaceLearningPeriod()->getResourceMaterials())
            ->with('learningGoals', Auth::user()->getCurrentWorkplaceLearningPeriod()->getLearningGoals())
            ->with('competencies', Auth::user()->getEducationProgram()->getCompetencies())
            ->with('activities', Auth::user()->getCurrentWorkplaceLearningPeriod()->getLastActivity(8))
            ->with('activitiesJson', $activitiesJson)
            ->with('exportTranslatedFieldMapping', json_encode($exportTranslatedFieldMapping));
    }

    public function edit($id)
    {
        // Allow only to view this page if an internship exists.
        if (Auth::user()->getCurrentWorkplaceLearningPeriod() == null) {
            return redirect()->route('profile')->withErrors(['Je kan geen activiteiten registreren zonder (actieve) stage.']);
        }

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

    public function progress($pagenr)
    {
        return view('pages.acting.progress')->with('page', $pagenr);
    }

    public function create(Request $request)
    {
        // Allow only to view this page if an internship exists.
        if (Auth::user()->getCurrentWorkplaceLearningPeriod() == null) {
            return redirect()->route('profile')->withErrors(['Je kan geen activiteiten registreren zonder (actieve) stage.']);
        }

        $activityActing = new LearningActivityActing;

        $validator = Validator::make($request->all(),
            [
                'date'          => 'required|date|before:' . date('d-m-Y', strtotime('tomorrow')), // TODO Date validation not working
                'description'   => 'required|max:250|regex:/^[ 0-9a-zA-Z\-_,.?!*&%#()\/\\\\\'"\s]+\s*$/',
                'timeslot'      => 'required|exists:timeslot,timeslot_id',
                'learned'       => 'required|max:250|regex:/^[ 0-9a-zA-Z\-_,.?!*&%#()\/\\\\\'"\s]+\s*$/',
                'support_wp'    => 'max:125|regex:/^[ 0-9a-zA-Z\-_,.?!*&%#()\/\\\\\'"\s]+\s*$/', // TODO better regex, allow empty?
                'support_ed'    => 'max:125|regex:/^[ 0-9a-zA-Z\-_,.?!*&%#()\/\\\\\'"\s]+\s*$/', // TODO better regex, allow empty?
                'learning_goal' => 'required|exists:learninggoal,learninggoal_id',
                'competence'    => 'required|exists:competence,competence_id',
            ]);

        // Conditional validation
        $validator->sometimes('res_person', 'required|exists:resourceperson,rp_id', function ($input) {
            return $input->res_person != 'new';
        });

        $validator->sometimes('new_rp', 'required|max:45|regex:/^[ 0-9a-zA-z(),.\/\\\\\']+$/', function ($input) {
            return $input->res_person === 'new';
        });

        $validator->sometimes('new_rm', 'required|max:45|regex:/^[ 0-9a-zA-z(),.\/\\\\\']+$/', function ($input) {
            return $input->res_material === 'new';
        });

        $validator->sometimes('res_material', 'required|exists:resourcematerial,rm_id', function ($input) {
            return $input->res_material != 'new' && $input->res_material != 'none';
        });
        /*$validator->sometimes('res_material_detail', 'required_unless:res_material,none|max:75|url', function($input) {
            return $input->res_material == 1;
        });*/
        //temporarily disabled url validation for res_material_detail field
        $validator->sometimes('res_material_detail', 'required_unless:res_material,none|max:75|regex:/^[ 0-9a-zA-z,.()\/\\\\\']+$/', function ($input) {
            return $input->res_material >= 1;
        });

        if ($validator->fails()) {
            return redirect()->route('process-acting')
                ->withErrors($validator)
                ->withInput();
        }

        if ($request['res_person'] == 'new') {
            $resourcePerson = new ResourcePerson;
            $resourcePerson->person_label = $request['new_rp'];
            $resourcePerson->ep_id = Auth::user()->getEducationProgram()->ep_id;
            $resourcePerson->wplp_id = Auth::user()->getCurrentWorkplaceLearningPeriod()->wplp_id;
            $resourcePerson->save();

            $request['res_person'] = $resourcePerson->rp_id;
        }

        if ($request['res_material'] == 'new') {
            $resourceMaterial = new ResourceMaterial;
            $resourceMaterial->rm_label = $request['new_rm'];
            $resourceMaterial->wplp_id = Auth::user()->getCurrentWorkplaceLearningPeriod()->wplp_id;
            $resourceMaterial->save();

            $request['res_material'] = $resourceMaterial->rm_id;
        }

        // Todo refactor into model->fill($request), all fillable
        $activityActing->wplp_id = Auth::user()->getCurrentWorkplaceLearningPeriod()->wplp_id;
        $activityActing->date = date_format(date_create($request->date, timezone_open("Europe/Amsterdam")), 'Y-m-d H:i:s');
        $activityActing->timeslot_id = $request['timeslot'];
        $activityActing->situation = $request['description'];
        $activityActing->lessonslearned = $request['learned'];
        $activityActing->support_wp = $request['support_wp'];
        $activityActing->support_ed = $request['support_ed'];
        $activityActing->res_person_id = $request['res_person'];
        ($request['res_material'] != 'none') ? $activityActing->res_material_id = $request['res_material'] : null;
        $activityActing->res_material_detail = $request['res_material_detail'];
        $activityActing->learninggoal_id = $request['learning_goal'];
        $activityActing->save();

        $activityActing->competence()->attach($request['competence']);

        return redirect()->route('process-acting')->with('success', 'De leeractiviteit is opgeslagen.');
    }

    public function update(Request $req, $id)
    {
        // Allow only to view this page if an internship exists.
        if (Auth::user()->getCurrentWorkplaceLearningPeriod() == null) {
            return redirect()->route('profile')->withErrors(['Je kan geen activiteiten registreren zonder (actieve) stage.']);
        }

        $validator = Validator::make($req->all(), [
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
        $validator->sometimes('res_person', 'required|exists:resourceperson,rp_id', function ($input) {
            return $input->res_person != 'new';
        });
        $validator->sometimes('res_material', 'required|exists:resourcematerial,rm_id', function ($input) {
            return $input->res_material != 'new' && $input->res_material != 'none';
        });
        /*$validator->sometimes('res_material_detail', 'required_unless:res_material,none|max:75|url', function($input) {
            return $input->res_material == 1;
        });*/
        //temporarily disabled url validation for res_material_detail field
        $validator->sometimes('res_material_detail', 'required_unless:res_material,none|max:75|regex:/^[ 0-9a-zA-z,.()\/\\\\\']+$/', function ($input) {
            return $input->res_material >= 1;
        });

        if ($validator->fails()) {
            return redirect()->route('process-acting-edit', ['id' => $id])
                ->withErrors($validator)
                ->withInput();
        }

        $learningActivity = Auth::user()->getCurrentWorkplaceLearningPeriod()->getLearningActivityActingById($id);
        $learningActivity->date = $req['date'];
        $learningActivity->timeslot_id = $req['timeslot'];
        $learningActivity->situation = $req['description'];
        $learningActivity->lessonslearned = $req['learned'];
        $learningActivity->support_wp = $req['support_wp'];
        $learningActivity->support_ed = $req['support_ed'];
        $learningActivity->res_person_id = $req['res_person'];
        $learningActivity->res_material_id = ($req['res_material'] != 'none') ?  $req['res_material'] : null;
        $learningActivity->res_material_detail = $req['res_material_detail'];
        $learningActivity->learninggoal_id = $req['learning_goal'];
        $learningActivity->save();

        $learningActivity->competence()->sync([$req['competence']]);

        return redirect()->route('process-acting')->with('success', 'De leeractiviteit is aangepast.');
    }
}
