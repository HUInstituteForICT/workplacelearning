<?php

declare(strict_types=1);

namespace App\Validators;

use Illuminate\Validation\Validator;

class PostalValidator
{
    public function validate($attribute, $value, $parameters, Validator $validator)
    {
        $value = preg_replace('/\s+/', '', $value);
        $validator->addReplacer('postalcode', function ($message, $attribute, $rule, $parameters) use ($value) {
            return str_replace(':value', $value, $message);
        });

        return (bool) preg_match('/^[a-zA-Z0-9]{3,10}$/', $value);
    }
}
