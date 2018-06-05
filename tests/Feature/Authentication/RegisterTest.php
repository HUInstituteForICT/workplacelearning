<?php


class RegisterTest extends \Tests\TestCase
{
    public function testRegistration()
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
            'gender'                => 'male',
            'email'                 => 'test@test.com',
            'password'              => 'johnjohnjohndoe',
            'password_confirmation' => 'johnjohnjohndoe',
            'education'             => $ep->ep_id,
            'secret'                => 'Stage2017',
        ]);

        $response->assertRedirect('/home');
    }
}