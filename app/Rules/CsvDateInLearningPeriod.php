<?php

namespace App\Rules;

use App\Validators\DateInLearningPeriodValidator;
use DateTime;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class csvDateInLearningPeriod implements Rule
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
        $validator = Validator::make(['datum' => $value], ['datum' => ['required', new CsvDateTimeFormat]]);

        if($validator->fails()) {
            return true;
        }
        else {
            $learningPeriodValidator = Validator::make(['datum' => $value], ['datum' => 'date_in_wplp']);

            if($learningPeriodValidator->fails()) {
                return false;
            }
            else {
                return true;
            }
        }

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Datum moet binnen werkplek periode vallen';
    }
}
