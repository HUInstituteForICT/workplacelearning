<?php


namespace App;


use Dompdf\Exception;
use Illuminate\Database\Eloquent\Model;

class EducationProgramsService
{
    const entityTypes = [
        "competence"     => 1,
        "timeslot"       => 2,
        "resourcePerson" => 3,
    ];

    const nameToEntityNameMapping = [
        1 => "competence_label",
        2 => "timeslot_text",
        3 => "person_label",
    ];

    /**
     * @param $entityId int
     * @param $type int
     * @return null|Model
     */
    private function getEntity($entityId, $type)
    {
        $entity = null;
        if ($type === 1) {
            $entity = Competence::find($entityId);
        } elseif ($type === 2) {
            $entity = Timeslot::find($entityId);
        } elseif ($type === 3) {
            $entity = ResourcePerson::find($entityId);
        }
        if ($entity instanceof Model) {
            return $entity;
        } else {
            throw new \Exception("Unable to find entity of type {$type} with ID {$entityId}");
        }
    }

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
        $entity = $this->getEntity($entityId, $type);

        return $entity->delete();
    }

    public function updateProgram(EducationProgram $program, array $data)
    {
        return $program->update($data);
    }

    /**
     * @param $entityId
     * @param array $data
     * @return Model|null
     * @throws \Exception
     */
    public function updateEntity($entityId, array $data)
    {
        // Mapping necessary due to inconsistent naming of model properties


        $entity = $this->getEntity($entityId, $data['type']);
        $mappedNameField = EducationProgramsService::nameToEntityNameMapping[$data['type']];
        $entity->$mappedNameField = $data['name'];
        if (!$entity->save()) {
            throw new \Exception("Unable to save entity");
        }

        return $entity;
    }


}