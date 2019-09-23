<?php

declare(strict_types=1);

use App\Student;

class LoginTest extends \Tests\TestCase
{
    public function testLogin(): void
    {
        /** @var Student $user */
        $user = factory(Student::class)->create(['pw_hash' => Hash::make('test123')]);

        $response = $this->post('/login', ['email' => $user->email, 'password' => 'test123']);
        $response->assertRedirect('/home');
    }
}
