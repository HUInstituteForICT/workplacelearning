<?php

namespace App\Validators;

use App\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Validator;

class DateInLearningPeriodValidator
{
    /**
     * @var Request
     */
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function validate($attribute, $value, $parameters, Validator $validator)
    {
        /** @var Student $user */
        $user = $this->request->user();
        $workplaceLearningPeriod = $user->getCurrentWorkplaceLearningPeriod();

        $activityDate = new Carbon($value);

        $validator->addReplacer('date_in_wplp', function ($message, $attribute, $rule, $parameters) use ($activityDate) {
            return str_replace(':date', $activityDate->toDateString(), $message);
        });

        if (null === $workplaceLearningPeriod) {
            return false;
        }

        $startDate = new Carbon($workplaceLearningPeriod->startdate);
        $endDate = new Carbon($workplaceLearningPeriod->enddate);

        return $activityDate->between($startDate, $endDate);
    }
}
