<?php

namespace Tests\Feature;

use App\Competence;
use App\Cohort;
use App\EducationProgram;
use App\EducationProgramsService;
use App\EducationProgramType;
use App\ResourcePerson;
use App\Timeslot;
use Tests\TestCase;

class EducationProgramsServiceTest extends TestCase
{
    /** @var $educationProgram EducationProgram */
    private $educationProgram;
    /** @var $programsService EducationProgramsService */
    private $programsService;
    /** @var $cohort Cohort */
    private $cohort;

    public function setUp()
    {
        parent::setUp();
        /** @var EducationProgramType $type */
        $type = EducationProgramType::create(['eptype_name' => 'Acting']);
        $this->educationProgram = EducationProgram::create(['ep_name' => 'Test EP', 'eptype_id' => $type->eptype_id]);
        $this->cohort = Cohort::create(['name' => 'Test cohort', 'description' => 'Test description', 'ep_id' => $this->educationProgram->ep_id]);
        $this->programsService = new EducationProgramsService();
    }

    public function testCreateEntity()
    {
        $entity = $this->programsService->createEntity('competence', 'Test program', $this->cohort);
        $this->assertInstanceOf(Competence::class, $entity);

        $entity = $this->programsService->createEntity('timeslot', 'Test program', $this->cohort);
        $this->assertInstanceOf(Timeslot::class, $entity);

        $entity = $this->programsService->createEntity('resourcePerson', 'Test program', $this->cohort);
        $this->assertInstanceOf(ResourcePerson::class, $entity);
    }

    public function testDeleteEntity()
    {
        $createdEntity = $this->programsService->createEntity('resourcePerson', 'Test program', $this->cohort);
        $this->assertInstanceOf(ResourcePerson::class, $createdEntity);

        $result = $this->programsService->deleteEntity($createdEntity->rp_id, 'resourcePerson');
        $this->assertTrue($result);
    }

    public function testUpdateEntity()
    {
        $createdEntity = $this->programsService->createEntity('resourcePerson', 'Test program', $this->cohort);
        $this->assertInstanceOf(ResourcePerson::class, $createdEntity);

        $newValue = 'new name';
        $updatedEntity = $this->programsService->updateEntity($createdEntity->rp_id, ['type' => 'resourcePerson', 'name' => $newValue]);

        $this->assertEquals($newValue, $updatedEntity->person_label);
    }
}
