<?php


namespace App\Http\Controllers;


use App\EducationProgram;
use App\EducationProgramsService;
use App\Http\Requests\EducationProgram\CreateCompetenceDescriptionRequest;
use App\Http\Requests\EducationProgram\CreateEntityRequest;
use App\Http\Requests\EducationProgram\DeleteEntityRequest;
use App\Http\Requests\EducationProgram\UpdateEntityRequest;
use App\Http\Requests\EducationProgram\UpdateRequest;
use App\Http\Requests\EducationProgramCreateEntityRequest;
use App\Http\Requests\EducationProgramDeleteEntityRequest;
use App\Http\Requests\EducationProgramUpdateRequest;
use Illuminate\Database\Eloquent\Model;

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

    public function getEducationalPrograms()
    {
        return EducationProgram::all();
    }

    public function getEditableProgram(EducationProgram $program)
    {

        $program->competence;
        $program->timeslot;
        $program->resourcePerson;
        $program->competenceDescription;

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

    }
}