<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class PostalCodeValidationTest extends TestCase
{
    public function testValidPostalCodes(): void
    {
        $validPostalCodes = collect(['1111aa', '1111 aa', '1111 AA', '1111AA', '']);
        $validPostalCodes->each(function ($postalCode): void {
            $validator = Validator::make(['postalCode' => $postalCode], ['postalCode' => 'postalcode']);
            $this->assertTrue($validator->passes());
        });
    }

    public function testInvalidPostalCodes(): void
    {
        $invalidPostalCodes = collect(['aa', '11', '2a']);
        $invalidPostalCodes->each(function ($postalCode): void {
            $validator = Validator::make(['postalCode' => $postalCode], ['postalCode' => 'postalcode']);
            $this->assertTrue($validator->fails(), "Postalcode {$postalCode} is considered valid by rule");
        });
    }
}
