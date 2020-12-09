<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Competence;
use App\LearningActivityActing;
use App\ResourceMaterial;
use App\ResourcePerson;
use App\Services\LearningActivityActingExportBuilder;
use App\Timeslot;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Translation\Translator;
use Tests\TestCase;

class LearningActivityExportBuilderTest extends TestCase
{
    private function buildMock()
    {
        $mock = \Mockery::mock(LearningActivityActing::class);

        $mock->shouldReceive('offsetExists')->andReturn(true);

        $mock->shouldReceive('getAttribute')->with('id')->andReturn('1');
        $mock->shouldReceive('getAttribute')->with('date')->andReturn(Carbon::createFromDate(2017, 10, 10));
        $mock->shouldReceive('getAttribute')->with('situation')->andReturn('pressure');

        $timeslot = new Timeslot();
        $timeslot->timeslot_text = '1e lesuur';
        $mock->shouldReceive('getAttribute')->with('timeslot')->times(1)->andReturn($timeslot);

        $resourcePerson = new ResourcePerson();
        $resourcePerson->person_label = 'Medestudent';
        $mock->shouldReceive('getAttribute')->with('resourcePerson')->times(1)->andReturn($resourcePerson);

        $resourceMaterial = new ResourceMaterial();
        $resourceMaterial->rm_label = 'Geen';
        $mock->shouldReceive('getAttribute')->with('resourceMaterial')->times(2)->andReturn($resourceMaterial);

        $mock->shouldReceive('getAttribute')->with('lessonslearned')->andReturn('a lot');
//        $mock->shouldReceive('getLearningGoal')->times(1)->andReturn('Leervraag 1');

        $learningGoal = new \StdClass();
        $learningGoal->description = 'Description test';
        $learningGoal->learninggoal_label = 'Leervraag 1';
        $mock->shouldReceive('getAttribute')->with('learningGoal')->times(2)->andReturn($learningGoal);

        $mock->shouldReceive('getAttribute')->with('support_wp')->andReturn('support from wp');
        $mock->shouldReceive('getAttribute')->with('support_ed')->andReturn('support from ed');

        $competenceObject = new Competence();
        $competenceObject->competence_label = 'Interpersoonlijk';
        $collection = new Collection([$competenceObject]);
        $mock->shouldReceive('getAttribute')->with('competence')->times(1)->andReturn($collection);
        $mock->shouldReceive('getAttribute')->with('laa_id')->andReturn('1');

        $mock->shouldReceive('getAttribute')->with('evidence')->andReturn(collect([]));
        $mock->shouldReceive('getAttribute')->with('reflection')->andReturn(null);

        return $mock;
    }

    /**
     * A basic test example.
     */
    public function testGetJson(): void
    {
        $this->markTestSkipped("Skip this test because its not coded correctly");
        $exporter = new LearningActivityActingExportBuilder($this->createMock(Translator::class));

        $json = $exporter->getJson([$this->buildMock()], null);

        $this->assertTrue(is_string($json), 'Export is not a string, therefore not JSON');

        $decoded = json_decode($json);

        $mapping = [
            'id'                      => 1,
            'date'                    => '10-10-2017',
            'situation'               => 'pressure',
            'timeslot'                => '1e lesuur',
            'resourcePerson'          => 'Medestudent',
            'resourceMaterial'        => 'Geen',
            'lessonsLearned'          => 'a lot',
            'learningGoal'            => 'Leervraag 1',
            'learningGoalDescription' => 'Description test',
            'supportWp'               => 'support from wp',
            'supportEd'               => 'support from ed',
            'competence'              => ['Interpersoonlijk'],
            'url'                     => route('process-acting-edit', [1]),
        ];

        foreach ($mapping as $field => $value) {
            if (is_array($value)) {
                $this->assertContains($value[0], $decoded[0]->{$field});
            } else {
                $this->assertEquals($value, $decoded[0]->{$field}, "{$field}: expected({$value}) got({$decoded[0]->{$field}})");
            }
        }
    }
}
