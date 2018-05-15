<?php
namespace App\Http\Controllers;

use App\LearningActivityActing;
use App\LearningActivityActingExportBuilder;
use App\ResourceMaterial;
use App\ResourcePerson;
use App\Timeslot;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\UnauthorizedException;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\File\Exception\UploadException;
use Validator;

class ActingActivityController extends Controller
{

    public function show()
    {
        // Allow only to view this page if an internship exists.
        if (Auth::user()->getCurrentWorkplaceLearningPeriod() === null) {
            return redirect()->route('profile')->withErrors([Lang::get('errors.activity-no-internship')]);
        }

        $resourcePersons = Auth::user()->currentCohort()->resourcePersons()->get()->merge(
            Auth::user()->getCurrentWorkplaceLearningPeriod()->getResourcePersons()
        );

        $timeslots = Auth::user()->currentCohort()->timeslots()->get()->merge(
            Auth::user()->getCurrentWorkplaceLearningPeriod()->getTimeslots()
        );

        $exportBuilder = new LearningActivityActingExportBuilder(Auth::user()->getCurrentWorkplaceLearningPeriod()->learningActivityActing()
            ->with('timeslot', 'resourcePerson', 'resourceMaterial', 'learningGoal', 'competence')
            ->take(8)
            ->orderBy('date', 'DESC')
            ->orderBy('laa_id', 'DESC')
            ->get());

        $activitiesJson = $exportBuilder->getJson();

        $exportTranslatedFieldMapping = $exportBuilder->getFieldLanguageMapping(app()->make('translator'));

        return view('pages.acting.activity')
            ->with('competenceDescription', Auth::user()->currentCohort()->competenceDescription)
            ->with('timeslots', $timeslots)
            ->with('resPersons', $resourcePersons)
            ->with('resMaterials', Auth::user()->getCurrentWorkplaceLearningPeriod()->getResourceMaterials())
            ->with('learningGoals', Auth::user()->getCurrentWorkplaceLearningPeriod()->getLearningGoals())
            ->with('competencies', Auth::user()->currentCohort()->competencies()->get())
            ->with('activities', Auth::user()->getCurrentWorkplaceLearningPeriod()->getLastActivity(8))
            ->with('activitiesJson', $activitiesJson)
            ->with('exportTranslatedFieldMapping', json_encode($exportTranslatedFieldMapping))
            ->with('workplacelearningperiod', Auth::user()->getCurrentWorkplaceLearningPeriod());
    }

    public function edit($id)
    {
        // Allow only to view this page if an internship exists.
        if (Auth::user()->getCurrentWorkplaceLearningPeriod() == null) {
            return redirect()->route('profile')->withErrors([Lang::get('errors.activity-no-internship')]);
        }

        $activity = Auth::user()->getCurrentWorkplaceLearningPeriod()->getLearningActivityActingById($id);

        if (!$activity) {
            return redirect()->route('process-acting')
                ->withErrors(Lang::get('errors.no-activity-found'));
        }

        $resourcePersons = Auth::user()->currentCohort()->resourcePersons()->get()->merge(
            Auth::user()->getCurrentWorkplaceLearningPeriod()->getResourcePersons()
        );

        $timeslots = Auth::user()->currentCohort()->timeslots()->get()->merge(
            Auth::user()->getCurrentWorkplaceLearningPeriod()->getTimeslots()
        );

        return view('pages.acting.activity-edit')
            ->with('activity', $activity)
            ->with('timeslots', $timeslots)
            ->with('resPersons', $resourcePersons)
            ->with('resMaterials', Auth::user()->getCurrentWorkplaceLearningPeriod()->getResourceMaterials())
            ->with('learningGoals', Auth::user()->getCurrentWorkplaceLearningPeriod()->getLearningGoals())
            ->with('competencies', Auth::user()->currentCohort()->competencies()->get());
    }

    public function progress($pagenr)
    {
        if (Auth::user()->getCurrentWorkplaceLearningPeriod() === null) {
            return redirect()->route('profile')->withErrors([Lang::get("notifications.generic.nointernshipprogress")]);
        }

        $exportBuilder = new LearningActivityActingExportBuilder(Auth::user()->getCurrentWorkplaceLearningPeriod()->learningActivityActing()
            ->with('timeslot', 'resourcePerson', 'resourceMaterial', 'learningGoal', 'competence')
            ->orderBy('date', 'DESC')
            ->get());

        $activitiesJson = $exportBuilder->getJson();

        $exportTranslatedFieldMapping = $exportBuilder->getFieldLanguageMapping(app()->make('translator'));

        return view('pages.acting.progress')
            ->with('activitiesJson', $activitiesJson)
            ->with('exportTranslatedFieldMapping', json_encode($exportTranslatedFieldMapping))
            ->with('page', $pagenr);
    }

    public function create(Request $request)
    {
        // Allow only to view this page if an internship exists.
        if (Auth::user()->getCurrentWorkplaceLearningPeriod() == null) {
            return redirect()->route('profile')->withErrors([Lang::get('errors.activity-no-internship')]);
        }

        $activityActing = new LearningActivityActing;



        $validator = Validator::make($request->all(),
            [
                'date'          => 'required|date|before:tomorrow|after_or_equal:'. strtotime(Auth::user()->getCurrentWorkplaceLearningPeriod()->startdate), // TODO Date validation not working
                'description'   => 'required|max:1000',
                'learned'       => 'required|max:1000',
                'support_wp'    => 'max:500',
                'support_ed'    => 'max:500',
                'learning_goal' => 'required|exists:learninggoal,learninggoal_id',
                'competence'    => 'required|exists:competence,competence_id',
                'evidence' => "file|max:2000000"
            ]);

        // Conditional validation
        $validator->sometimes('res_person', 'required|exists:resourceperson,rp_id', function ($input) {
            return $input->res_person != 'new';
        });
        $validator->sometimes('timeslot', 'required|exists:timeslot,timeslot_id', function($input) {
            return $input->timeslot != 'new';
        });
        $validator->sometimes('res_material', 'required|exists:resourcematerial,rm_id', function ($input) {
            return $input->res_material != 'new' && $input->res_material != 'none';
        });

        $validator->sometimes('new_rp', 'required|max:45', function ($input) {
            return $input->res_person === 'new';
        });
        $validator->sometimes('new_timeslot', 'required|max:45', function ($input) {
            return $input->timeslot === 'new';
        });
        $validator->sometimes('new_rm', 'required|max:45', function ($input) {
            return $input->res_material === 'new';
        });

        /*$validator->sometimes('res_material_detail', 'required_unless:res_material,none|max:75|url', function($input) {
            return $input->res_material == 1;
        });*/
        //temporarily disabled url validation for res_material_detail field
        $validator->sometimes('res_material_detail', 'required_unless:res_material,none|max:75', function ($input) {
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
            $resourcePerson->ep_id = Auth::user()->getEducationProgram()->ep_id; //deprecated, bound to wplp?
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

        if($request['timeslot'] == 'new') {
            $timeslot = new Timeslot;
            $timeslot->timeslot_text = $request['new_timeslot'];
            $timeslot->edprog_id = Auth::user()->getEducationProgram()->ep_id; //deprecated, bound to wplp?
            $timeslot->wplp_id = Auth::user()->getCurrentWorkplaceLearningPeriod()->wplp_id;
            $timeslot->save();

            $request['timeslot'] = $timeslot->timeslot_id;
        }

        if($request->hasFile('evidence')) {
            $evidence = $request->file('evidence');
            $diskFileName = Uuid::uuid4();
            if(!$evidence->storeAs("activity-evidence", $diskFileName)) {
                throw new UploadException("Unable to upload file");
            }
            $activityActing->evidence_filename = $evidence->getClientOriginalName();
            $activityActing->evidence_disk_filename = $diskFileName;
            $activityActing->evidence_mime = $evidence->getClientMimeType();
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

        return redirect()->route('process-acting')->with('success', Lang::get('activity.saved-successfully'));
    }

    public function update(Request $req, $id)
    {
        // Allow only to view this page if an internship exists.
        if (Auth::user()->getCurrentWorkplaceLearningPeriod() == null) {
            return redirect()->route('profile')->withErrors([Lang::get('errors.activity-no-internship')]);
        }

        $validator = Validator::make($req->all(), [
            'date'                  => 'required|date|before:'.date('d-m-Y', strtotime('tomorrow')),
            'description'           => 'required|max:1000',
            'timeslot'              => 'required|exists:timeslot,timeslot_id',
            'new_rp'                => 'required_if:res_person,new|max:45|',
            'new_rm'                => 'required_if:res_material,new|max:45',
            'learned'               => 'required|max:1000',
            'support_wp'            => 'max:500',
            'support_ed'            => 'max:500',
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
        $validator->sometimes('res_material_detail', 'required_unless:res_material,none|max:75', function ($input) {
            return $input->res_material >= 1;
        });

        if ($validator->fails()) {
            return redirect()->route('process-acting-edit', ['id' => $id])
                ->withErrors($validator)
                ->withInput();
        }

        /** @var LearningActivityActing $learningActivity */
        $learningActivity = Auth::user()->getCurrentWorkplaceLearningPeriod()->getLearningActivityActingById($id);

        if ($req->hasFile('evidence')) {
            $evidence = $req->file('evidence');
            $diskFileName = Uuid::uuid4();
            if (!$evidence->storeAs("activity-evidence", $diskFileName)) {
                throw new UploadException("Unable to upload file");
            }
            if ($learningActivity->evidence_disk_filename !== null && Storage::exists("activity-evidence/{$learningActivity->evidence_disk_filename}")) {
                Storage::delete("activity-evidence/{$learningActivity->evidence_disk_filename}");
            }

            $learningActivity->evidence_filename = $evidence->getClientOriginalName();
            $learningActivity->evidence_disk_filename = $diskFileName;
            $learningActivity->evidence_mime = $evidence->getClientMimeType();
        }

        $learningActivity->date = $req['date'];
        $learningActivity->timeslot_id = $req['timeslot'];
        $learningActivity->situation = $req['description'];
        $learningActivity->lessonslearned = $req['learned'];
        $learningActivity->support_wp = $req['support_wp'];
        $learningActivity->support_ed = $req['support_ed'];
        $learningActivity->res_person_id = $req['res_person'];
        $learningActivity->res_material_id = ($req['res_material'] != 'none') ? $req['res_material'] : null;
        $learningActivity->res_material_detail = $req['res_material_detail'];
        $learningActivity->learninggoal_id = $req['learning_goal'];
        $learningActivity->save();

        $learningActivity->competence()->sync([$req['competence']]);

        return redirect()->route('process-acting')->with('success', Lang::get('activity.saved-successfully'));
    }

    public function delete(LearningActivityActing $activity)
    {
        if($activity === null) {
            return redirect()->route('process-acting');
        }
        // Allow only to view this page if an internship exists.
        if (Auth::user()->getCurrentWorkplaceLearningPeriod() == null) {
            return redirect()->route('profile')->withErrors([Lang::get('errors.activity-no-internship')]);
        }

        if(Auth::user()->getCurrentWorkplaceLearningPeriod()->wplp_id !== $activity->wplp_id) {
            throw new UnauthorizedException("No access");
        }

        $activity->competence()->detach($activity->competence()->first()->competence_id);
        $activity->delete();

        return redirect()->route('process-acting');
    }
}
