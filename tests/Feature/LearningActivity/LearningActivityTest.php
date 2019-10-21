<?php

declare(strict_types=1);

namespace Tests\Feature\LearningActivity;

use App\Competence;
use App\Difficulty;
use App\LearningActivityActing;
use App\ResourceMaterial;
use App\ResourcePerson;
use App\Status;
use App\Timeslot;
use Carbon\Carbon;
use Tests\Feature\GenericUserTrait;

class LearningActivityTest extends \Tests\TestCase
{
    use GenericUserTrait;

    public function testCreateLAA(): void
    {
        $user = $this->getUser('acting');

        /**
         * @noinspection NullPointerExceptionInspection
         *
         * @var \App\LearningGoal
         */
        $learningGoal = $user->getCurrentWorkplaceLearningPeriod()->learningGoals()->first();
        /** @var Competence $competence */
        $competence = $user->getCurrentWorkplaceLearningPeriod()->cohort->competencies()->first();
        $response = $this->actingAs($user)->post('/acting/process/create', [
            'date'                => Carbon::now()->format('d-m-Y'),
            'description'         => 'Some test activity!', // gets saved in $laa->situation !!!!!!
            'timeslot'            => 'new',
            'new_timeslot'        => 'New timeslot label',
            'res_person'          => 'new',
            'new_rp'              => 'Some new res person',
            'res_material_detail' => '',
            'res_material'        => 'new',
            'new_rm'              => 'Some new resource material',
            'learned'             => 'A lot',
            'support_wp'          => 'Nothing',
            'support_ed'          => 'Nothing',
            'learning_goal'       => $learningGoal->learninggoal_id,
            'competence'          => $competence->competence_id,
        ]);

        // Controller redirects back to process creation page -- consider that success
        $response->assertJson(['status' => 'success', 'url' => 'https://localhost/acting/process']);
        $this->assertDatabaseHas('learningactivityacting', ['situation' => 'Some test activity!']);
    }

    public function testCreateLAP(): void
    {
        $user = $this->getUser('producing');

        $difficulty = (new Difficulty(['difficulty_id' => 1, 'makkelijk']))->save();
        $status = (new Status(['status_id' => 1, 'status_label' => 'finished']))->save();

        $response = $this->actingAs($user)->post('/producing/process/create', [
            'datum'         => Carbon::now()->format('d-m-Y'),
            'omschrijving'  => 'Some test activity!',
            'aantaluren'    => '2',
            'category_id'   => 'new',
            'newcat'        => 'some new category',
            'resource'      => 'persoon',
            'personsource'  => 'new',
            'newswv'        => 'new test person',
            'status'        => '1',
            'moeilijkheid'  => '1',
            'chain_id'      => '-1',
            'extrafeedback' => '0',
        ]);

        // Controller redirects back to process creation page -- consider that success
        $response->assertJson(['status' => 'success', 'url' => 'https://localhost/producing/process']);
        $this->assertDatabaseHas('learningactivityproducing', ['description' => 'Some test activity!']);
    }

    public function testEditEditLAA(): void
    {
        $user = $this->getUser('acting');
        $wplp = $user->getCurrentWorkplaceLearningPeriod();
        /** @var LearningActivityActing $learningActivityActing */
        $learningActivityActing = factory(LearningActivityActing::class)->create([
            'wplp_id'       => $wplp->wplp_id,
            'timeslot_id'   => factory(Timeslot::class)->create(['cohort_id' => $wplp->cohort->id]),
            'res_person_id' => factory(ResourcePerson::class)->create([
                'cohort_id' => $wplp->cohort->id,
                'wplp_id'   => $wplp->wplp_id,
            ]),
            'res_material_id' => factory(ResourceMaterial::class)->create([
                'wplp_id' => $wplp->wplp_id,
            ]),
            'learninggoal_id' => $wplp->learningGoals()->first()->learninggoal_id,
        ]);

        $learningActivityActing->competence()->save($wplp->cohort->competencies()->first());

        $response = $this->actingAs($user)->post('acting/process/update/'.$learningActivityActing->laa_id, [
            'date'                => $learningActivityActing->date,
            'description'         => 'new text', // gets saved in $laa->situation !!!!!!
            'timeslot'            => $learningActivityActing->timeslot_id,
            'res_person'          => $learningActivityActing->res_person_id,
            'res_material'        => $learningActivityActing->res_material_id,
            'res_material_detail' => 'Something about books',
            'learned'             => 'A lot',
            'support_wp'          => 'Nothing',
            'support_ed'          => 'Nothing',
            'learning_goal'       => $learningActivityActing->learninggoal_id,
            'competence'          => $learningActivityActing->competence()->first()->competence_id,
        ])
            ->assertJson(['status' => 'success', 'url' => 'https://localhost/acting/process']);

        $this->assertDatabaseHas('learningactivityacting', ['situation' => 'new text']);
    }
}
