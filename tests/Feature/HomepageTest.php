<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

class HomepageTest extends TestCase
{
    use GenericUserTrait;

    public function testHomeActingWithoutActiveWplp(): void
    {
        $user = $this->getUser('acting');
        $wplp = $user->getCurrentWorkplaceLearningPeriod();

        // Remove active wplp
        $user->usersettings()->where('setting_label', '=', 'active_internship')->delete();
        $this->assertDatabaseMissing('usersetting', ['student_id' => $user->student_id, 'setting_label' => 'active_internship']);

        // Check if homepage still loads
        $this->actingAs($user)->get('/acting/home')->assertSuccessful();

        // Refresh the user setting so when it gets set again it actually creates a new entity
        $user->getUserSetting('active_internship', true);

        // Set active wplp again
        $user->setActiveWorkplaceLearningPeriod($wplp);

        $this->assertDatabaseHas('usersetting', ['student_id' => $user->student_id, 'setting_label' => 'active_internship']);

        // Check if homepage still loads
        $this->actingAs($user)->get('/acting/home')->assertSuccessful();
    }

    public function testHomeActingWithActiveWplp(): void
    {
        $user = $this->getUser('acting');
        $this->assertDatabaseHas('usersetting', ['student_id' => $user->student_id, 'setting_label' => 'active_internship']);

        $this->actingAs($user)->get('/acting/home')->assertSuccessful();
    }

    public function testHomeProducingWithoutActiveWplp(): void
    {
        $user = $this->getUser('producing');
        $wplp = $user->getCurrentWorkplaceLearningPeriod();
        $user->usersettings()->where('setting_label', '=', 'active_internship')->delete();
        $this->assertDatabaseMissing('usersetting', ['student_id' => $user->student_id, 'setting_label' => 'active_internship']);

        $this->actingAs($user)->get('/producing/home')->assertSuccessful();

        $user->getUserSetting('active_internship', true);
        $user->setActiveWorkplaceLearningPeriod($wplp);

        $this->assertDatabaseHas('usersetting', ['student_id' => $user->student_id, 'setting_label' => 'active_internship']);
        $this->actingAs($user)->get('/producing/home')->assertSuccessful();
    }

    public function testHomeProducingWithActiveWplp(): void
    {
        $user = $this->getUser('producing');
        $this->assertDatabaseHas('usersetting', ['student_id' => $user->student_id, 'setting_label' => 'active_internship']);

        $this->actingAs($user)->get('/producing/home')->assertSuccessful();
    }
}
