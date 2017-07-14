<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class EducationProgramsService
{
    CONST entityTypes = [
        "competence"     => 1,
        "timeslot"       => 2,
        "resourcePerson" => 3,
    ];


    public function createEntity($type, $value, EducationProgram $program)
    {
        $result = null;
        if ($type === 1) {
            $result = $program->competence()->save(new Competence(["competence_label" => $value]));
        } elseif ($type === 2) {
            $result = $program->timeslot()->save(new Timeslot(["timeslot_text" => $value]));
        } elseif ($type === 3) {
            $result = $program->resourcePerson()->save(new ResourcePerson(["person_label" => $value, "wplp_id" => 0]));
        };

        return $result;

    }

    public function deleteEntity($entityId, $type)
    {
        $entity = null;
        if($type === 1) {
            $entity = Competence::find($entityId);
        } elseif($type === 2) {
            $entity = Timeslot::find($entityId);
        } elseif($type === 3) {
            $entity = ResourcePerson::find($entityId);
        }
        if($entity instanceof Model) {
            return $entity->delete();
        }
        return false;
    }


}