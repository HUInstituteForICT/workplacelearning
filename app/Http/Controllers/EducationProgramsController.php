<?php


namespace App\Http\Controllers;


use App\Competence;
use App\CompetenceDescription;
use App\EducationProgram;
use App\EducationProgramsService;
use App\Http\Requests\EducationProgram\CreateCompetenceDescriptionRequest;
use App\Http\Requests\EducationProgram\CreateEducationProgramRequest;
use App\Http\Requests\EducationProgram\CreateEntityRequest;
use App\Http\Requests\EducationProgram\DeleteEntityRequest;
use App\Http\Requests\EducationProgram\UpdateEntityRequest;
use App\Http\Requests\EducationProgram\UpdateRequest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class EducationProgramsController extends Controller
{
    private $programsService;

    public function __construct(EducationProgramsService $programsService)
    {
        $this->programsService = $programsService;
    }

    public function index()
    {
        return view('pages.education-programs');
    }

    public function getEducationPrograms()
    {
        return EducationProgram::all();
    }

    public function createEducationProgram(CreateEducationProgramRequest $request) {
        $program = $this->programsService->createEducationProgram($request->all());
        return response()->json(["status" => "success", "program" => $program]);
    }

    public function getEditableProgram(EducationProgram $program)
    {

        if($program->eptype_id === 1) {
            $program->competence;
            $program->timeslot;
            $program->competenceDescription;
        } elseif ($program->eptype_id === 2) {
            $program->category = $program->category()->get()->filter(function($category, $key) {
                /** $category Category */
                return $category->wplp_id === 0;
            });

        }

        $program->resourcePerson;


        return response()->json($program);

    }

    public function createEntity(EducationProgram $program, CreateEntityRequest $request)
    {
        $result = $this->programsService->createEntity((int)$request->get('type'), (string)$request->get('value'),
            $program);

        if ($result instanceof Model) {
            return response()->json(["status" => "success", "entity" => $result->toArray()]);
        } else {
            throw new \Exception("Unable to create entity of type {$request->get('type')}");
        }
    }

    public function deleteEntity(DeleteEntityRequest $request, $entityId)
    {
        if ($this->programsService->deleteEntity($entityId, (int)$request->get('type'))) {
            return response()->json(["status" => "success"]);
        } else {
            throw new \Exception("Unable to delete entity {$entityId}");
        }

    }

    public function updateEntity(UpdateEntityRequest $request, $entityId)
    {
        $entity = $this->programsService->updateEntity((int)$entityId, $request->all());

        $mappedNameField = EducationProgramsService::nameToEntityNameMapping[$request->get('type')];

        return response()->json(["status" => "success", "entity" => $entity, "mappedNameField" => $mappedNameField]);
    }

    public function updateProgram(EducationProgram $program, UpdateRequest $request)
    {
        if (!$this->programsService->updateProgram($program, $request->all())) {
            throw new \Exception("Unable to update program {$program->ep_name}");
        }

        return response()->json(["status" => "success", "program" => $program]);
    }

    public function createCompetenceDescription(EducationProgram $program, CreateCompetenceDescriptionRequest $request)
    {
        $competenceDescription = $this->programsService->handleUploadedCompetenceDescription($program,
            $request->get('file'));

        return response()->json(["status" => "success", "competence_description" => $competenceDescription]);
    }

    public function removeCompetenceDescription(EducationProgram $program) {
        /** @var CompetenceDescription $competenceDescription */
        $competenceDescription = $program->competenceDescription;
        if($competenceDescription !== null) {
            Storage::disk('local')->delete($competenceDescription->file_name);
            $competenceDescription->delete();
        }

        return response()->json(["status" => "success"]);

    }
}