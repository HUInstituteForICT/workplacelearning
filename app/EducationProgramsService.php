<?php

namespace App;

use App\Traits\TranslatableEntity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class EducationProgramsService
{
    const entityTypes = [
        'competence' => 1,
        'timeslot' => 2,
        'resourcePerson' => 3,
        'category' => 4,
    ];

    // Mapping necessary due to inconsistent naming of model properties
    const nameToEntityNameMapping = [
        'competence' => 'competence_label',
        'timeslot' => 'timeslot_text',
        'resourcePerson' => 'person_label',
        'category' => 'category_label',
    ];

    /**
     * @param $entityId int ID of the entity
     * @param $type int type of the entity
     *
     * @return Model|null the found entity or null if it doesn't exist
     *
     * @throws \Exception
     */
    private function getEntity($entityId, $type)
    {
        $entity = null;
        if ($type === 'competence') {
            $entity = Competence::find($entityId);
        } elseif ($type === 'timeslot') {
            $entity = Timeslot::find($entityId);
        } elseif ($type === 'resourcePerson') {
            $entity = ResourcePerson::find($entityId);
        } elseif ($type === 'category') {
            $entity = Category::find($entityId);
        }
        if ($entity instanceof Model) {
            return $entity;
        }
        throw new \Exception("Unable to find entity of type {$type} with ID {$entityId}");
    }

    /**
     * @param $type int type of the entity
     * @param $value string value of the entity (often name)
     * @param EducationProgram $cohort
     *
     * @return false|Model|null
     */
    public function createEntity($type, $value, Cohort $cohort)
    {
        $cohort->refresh();
        /** @var TranslatableEntity|null $result */
        $result = null;
        if ($type === 'competence') {
            $result = $cohort->competencies()->save(new Competence(['competence_label' => $value]));
        } elseif ($type === 'timeslot') {
            $result = $cohort->timeslots()->save(new Timeslot(['timeslot_text' => $value, 'wplp_id' => 0]));
        } elseif ($type === 'resourcePerson') {
            $result = $cohort->resourcePersons()->save(new ResourcePerson(['person_label' => $value, 'wplp_id' => 0]));
        } elseif ($type === 'category') {
            $result = $cohort->categories()->save(new Category(['category_label' => $value, 'wplp_id' => 0]));
        }

        return $result;
    }

    /**
     * @param $entityId int ID of the entity to delete
     * @param $type int Typ eof the entity
     *
     * @return bool|null if the delete was successful
     */
    public function deleteEntity($entityId, $type)
    {
        $entity = $this->getEntity($entityId, $type);

        return $entity->delete();
    }

    /**
     * @param array $data data to save
     *
     * @return bool if the program has been saved
     */
    public function updateProgram(EducationProgram $program, array $data)
    {
        return $program->update($data);
    }

    /**
     * @param $entityId
     *
     * @return Model|null
     *
     * @throws \Exception
     */
    public function updateEntity($entityId, array $data)
    {
        $entity = $this->getEntity($entityId, $data['type']);
        $mappedNameField = EducationProgramsService::nameToEntityNameMapping[$data['type']];
        $entity->$mappedNameField = $data['name'];
        if (!$entity->save()) {
            throw new \Exception('Unable to save entity');
        }

        return $entity;
    }

    /**
     * @param EducationProgram $cohort
     * @param $fileData
     *
     * @return CompetenceDescription the created competence description
     */
    public function handleUploadedCompetenceDescription(Cohort $cohort, $fileData)
    {
        if ($cohort->competenceDescription === null) {
            $cohort->competenceDescription()->save(new CompetenceDescription());
        }
        $competenceDescription = $cohort->competenceDescription()->first();

        Storage::disk('local')->put($competenceDescription->file_name, base64_decode(substr($fileData, 28)));

        return $competenceDescription;
    }

    /**
     * @param $data array contains name and type for the education program
     *
     * @return EducationProgram
     *
     * @throws \Exception if unable to create
     */
    public function createEducationProgram(array $data)
    {
        $program = EducationProgram::create($data);
        if (!$program instanceof Model) {
            throw new \Exception("Unable to create Educationprogram with {$data['name']}");
        }

        return $program;
    }
}
