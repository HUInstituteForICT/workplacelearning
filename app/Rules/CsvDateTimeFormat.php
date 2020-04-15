<?php

namespace App\Rules;

use App\Validators\DateInLearningPeriodValidator;
use DateTime;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class csvDateTimeFormat implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $dateTime = DateTime::createFromFormat('m/d/Y', $value);
        $errors = DateTime::getLastErrors();

        if (!empty($errors['warning_count'])) {
            return false;
        }

        return $dateTime !== false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Datum incorrect geformatteerd, moet zijn: dag/maand/jaar';
    }
}
