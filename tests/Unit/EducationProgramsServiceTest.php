<?php

namespace Tests\Feature;

use App\Competence;
use App\EducationProgram;
use App\EducationProgramsService;
use App\EducationProgramType;
use App\LearningActivityActing;
use App\LearningActivityProducingExportBuilder;
use App\ResourcePerson;
use App\Timeslot;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Validator;

class EducationProgramsServiceTest extends TestCase
{
    /** @var $educationProgram EducationProgram */
    private $educationProgram;
    /** @var $programsService EducationProgramsService */
    private $programsService;

    public function setUp()
    {
        parent::setUp();
        /** @var EducationProgramType $type */
        $type = EducationProgramType::create(['eptype_name' => 'Acting']);
        $this->educationProgram = EducationProgram::create(['ep_name' => 'Test EP', 'eptype_id' => $type->eptype_id]);
        $this->programsService = new EducationProgramsService();
    }

    public function testCreateEntity()
    {
        $entity = $this->programsService->createEntity(EducationProgramsService::entityTypes["competence"], 'Test program', $this->educationProgram);
        $this->assertInstanceOf(Competence::class, $entity);

        $entity = $this->programsService->createEntity(EducationProgramsService::entityTypes["timeslot"], 'Test program', $this->educationProgram);
        $this->assertInstanceOf(Timeslot::class, $entity);

        $entity = $this->programsService->createEntity(EducationProgramsService::entityTypes["resourcePerson"], 'Test program', $this->educationProgram);
        $this->assertInstanceOf(ResourcePerson::class, $entity);

    }

    public function testDeleteEntity() {
        $createdEntity = $this->programsService->createEntity(EducationProgramsService::entityTypes["resourcePerson"], 'Test program', $this->educationProgram);
        $this->assertInstanceOf(ResourcePerson::class, $createdEntity);

        $result = $this->programsService->deleteEntity($createdEntity->rp_id, EducationProgramsService::entityTypes["resourcePerson"]);
        $this->assertTrue($result);
    }

    public function testUpdateEntity() {
        $createdEntity = $this->programsService->createEntity(EducationProgramsService::entityTypes["resourcePerson"], 'Test program', $this->educationProgram);
        $this->assertInstanceOf(ResourcePerson::class, $createdEntity);

        $newValue = "new name";
        $updatedEntity = $this->programsService->updateEntity($createdEntity->rp_id, ["type" => EducationProgramsService::entityTypes["resourcePerson"], "name" => $newValue]);

        $this->assertEquals($newValue, $updatedEntity->person_label);

    }


}
