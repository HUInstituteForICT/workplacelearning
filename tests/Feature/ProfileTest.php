<?php

namespace Tests\Feature;

use App\Student;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    public function testSaveProfile(): void
    {
        /** @var Student $user */
        $user = factory(Student::class)->create();

        $this
            ->actingAs($user)
            ->post('/profiel/update',
                [
                    'student_id' => $user->student_id,
                    'firstname' => 'John',
                    'lastname' => 'Doe',
                    'email' => 'john@doe.com',
                    'locale' => 'nl',
                ])
            ->assertRedirect('/profiel')
            ->assertSessionMissing('errors');

        $this->assertDatabaseHas('student', [
            'student_id' => $user->student_id,
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'john@doe.com',
            'locale' => 'nl',
        ]);
    }

    public function testUpdatePassword(): void
    {
        $currentPassword = 'johnjohnjohndoe';
        /** @var Student $user */
        $user = factory(Student::class)->create(['pw_hash' => Hash::make($currentPassword)]);

        $this->actingAs($user)
            ->put('/profiel/change-password', [
                'current_password' => $currentPassword,
                'new_password' => 'johndoe',
                'confirm_password' => 'johndoe',
            ])

            ->assertRedirect('/profiel')
            ->assertSessionMissing('errors');

        $user->refresh();

        $this->assertTrue(Hash::check('johndoe', $user->pw_hash)); // Check if password has been updated
    }
}
