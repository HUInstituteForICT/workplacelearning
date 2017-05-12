<?php

namespace Tests\Feature;

use App\LearningActivityActing;
use App\LearningActivityExportBuilder;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LearningActivityExportBuilderTest extends TestCase
{

    private function buildMock() {

        $mock = \Mockery::mock(LearningActivityActing::class);
//        $mock->date = '12-12';
        $mock->shouldReceive('getAttribute')->with('id')->andReturn('1');
        $mock->shouldReceive('getAttribute')->with('date')->andReturn('12-12');
        $mock->shouldReceive('getAttribute')->with('situation')->andReturn('pressure');
//        $mock->situation = "pressure";
        $mock->shouldReceive('getTimeslot')->times(1)->andReturn('1e lesuur');
        $mock->shouldReceive('getResourcePerson')->times(1)->andReturn('Medestudent');
        $mock->shouldReceive('getResourceMaterial')->times(1)->andReturn('Geen');
        $mock->shouldReceive('getAttribute')->with('lessonslearned')->andReturn('a lot');
//        $mock->lessonslearned = "a lot";
        $mock->shouldReceive('getLearningGoal')->times(1)->andReturn('Leervraag 1');
        $competenceObject = new \StdClass;
        $competenceObject->competence_label = "Interpersoonlijk";
        $mock->shouldReceive('getCompetencies')->times(1)->andReturn($competenceObject);
        $mock->shouldReceive('getAttribute')->with('laa_id')->andReturn('1');
        return $mock;
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testGetJson()
    {
        $exporter = new LearningActivityExportBuilder(collect([$this->buildMock()]));
        $json = $exporter->getJson();

        $this->assertTrue(is_string($json), "Export is not a string, therefore not JSON");

        $decoded = json_decode($json);

        $mapping = [
            "id" => 1,
            "date" => "12-12",
            "situation" => "pressure",
            "timeslot" => "1e lesuur",
            "resourcePerson" => "Medestudent",
            "resourceMaterial" => "Geen",
            "lessonsLearned" => "a lot",
            "learningGoal" => "Leervraag 1",
            "competency" => "Interpersoonlijk",
            "url" => route('process-acting-edit', ["id" => 1])
        ];

        foreach($mapping as $field => $value) {
            $this->assertEquals($value, $decoded[0]->{$field});
        }


    }
}
