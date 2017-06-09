<?php

namespace Tests\Feature;

use App\LearningActivityActing;
use App\LearningActivityExportBuilder;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Validator;

class PostalCodeValidationTest extends TestCase
{

    public function testValidPostalCodes()
    {

        $validPostalCodes = collect(['1111aa', '1111 aa', '1111 AA', '1111AA', '']);
        $validPostalCodes->each(function ($postalCode) {
            $validator = Validator::make(['postalCode' => $postalCode], ['postalCode' => 'postalcode']);
            $this->assertTrue($validator->passes());
        });
    }

    public function testInvalidPostalCodes()
    {
        $invalidPostalCodes = collect(['aa', '11', '2a']);
        $invalidPostalCodes->each(function ($postalCode) {
            $validator = Validator::make(['postalCode' => $postalCode], ['postalCode' => 'postalcode']);
            $this->assertTrue($validator->fails(), "Postalcode {$postalCode} is considered valid by rule");
        });
    }
}
