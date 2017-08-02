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

    // Mapping necessary due to inconsistent naming of model properties
    const nameToEntityNameMapping = [
        1 => "competence_label",
        2 => "timeslot_text",
        3 => "person_label",
    ];

    /**
     * @param $entityId int ID of the entity
     * @param $type int type of the entity
     * @return Model|null the found entity or null if it doesn't exist
     * @throws \Exception
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

    /**
     * @param $type int type of the entity
     * @param $value string value of the entity (often name)
     * @param EducationProgram $program
     * @return false|Model|null
     */
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

    /**
     * @param $entityId int ID of the entity to delete
     * @param $type int Typ eof the entity
     * @return bool|null if the delete was successful
     */
    public function deleteEntity($entityId, $type)
    {
        $entity = $this->getEntity($entityId, $type);

        return $entity->delete();
    }

    /**
     * @param EducationProgram $program
     * @param array $data data to save
     * @return bool if the program has been saved
     */
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


        $entity = $this->getEntity($entityId, $data['type']);
        $mappedNameField = EducationProgramsService::nameToEntityNameMapping[$data['type']];
        $entity->$mappedNameField = $data['name'];
        if (!$entity->save()) {
            throw new \Exception("Unable to save entity");
        }

        return $entity;
    }

    public function handleUploadedCompetenceDescription(EducationProgram $program, $fileData) {

    }


}