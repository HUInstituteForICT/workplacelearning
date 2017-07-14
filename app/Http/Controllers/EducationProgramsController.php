<?php


namespace App\Http\Controllers;


use App\EducationProgram;
use App\EducationProgramsService;
use App\Http\Requests\EducationProgramCreateEntityRequest;
use App\Http\Requests\EducationProgramDeleteEntityRequest;
use Illuminate\Database\Eloquent\Model;

class EducationProgramsController extends Controller
{
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

        return response()->json($program);

    }

    public function createEntity(
        EducationProgram $program,
        EducationProgramsService $programsService,
        EducationProgramCreateEntityRequest $request
    ) {
        $result = $programsService->createEntity((int)$request->get('type'), (string)$request->get('value'), $program);

        if ($result instanceof Model) {
            return response()->json(["status" => "success", "entity" => $result->toArray()]);
        } else {
            throw new \Exception("Unable to create entity of type {$request->get('type')}");
        }
    }

    public function deleteEntity(
        EducationProgramDeleteEntityRequest $request,
        $entityId,
        EducationProgramsService $programsService
    ) {
        if ($programsService->deleteEntity($entityId, (int)$request->get('type'))) {
            return response()->json(["status" => "success"]);
        } else {
            throw new \Exception("Unable to delete entity {$entityId}");
        }

    }
}