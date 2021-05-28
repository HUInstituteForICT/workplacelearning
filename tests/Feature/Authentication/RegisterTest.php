<?php

declare(strict_types=1);

/**
 * @group failed
 */
class RegisterTest extends \Tests\TestCase
{
    public function testRegistration(): void
    {
        $response = $this->get('/');
        $response->assertRedirect('/login');

        $response = $this->get('/register');
        $response->assertSee('Registreer');

        /** @var \App\EducationProgram $ep */
        $ep = factory(\App\EducationProgram::class)->create();

        $response = $this->post('/register', [
            'studentnr'             => '1234567',
            'firstname'             => 'John',
            'lastname'              => 'Doe',
            'email'                 => 'test@test.com',
            'password'              => 'johnjohnjohndoe',
            'password_confirmation' => 'johnjohnjohndoe',
            'education'             => $ep->ep_id,
            'secret'                => 'Stage2017',
            'privacy'               => 1
        ]);

        $response->assertRedirect('/home');
    }
}
