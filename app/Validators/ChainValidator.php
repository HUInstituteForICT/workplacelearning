<?php

namespace App\Validators;

use App\Chain;
use App\Student;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;

class ChainValidator
{
    /** @var Student */
    private $user;

    public function __construct(Request $request)
    {
        $this->user = $request->user();
    }

    public function validate($attribute, $value, $parameters, Validator $validator): bool
    {
        // We can consider absence to mean no chaining
        if ($value === null) {
            return true;
        }

        $chainId = (int) $value;

        // Student selected "don't chain", always allow
        if ($chainId === -1) {
            return true;
        }

        $chain = (new Chain())->find($chainId);

        if ($chain === null) {
            return false;
        }

        // Check if selected chain is bound to same WPLP as student's current WPLP
        return $chain->wplp_id === $this->user->getCurrentWorkplaceLearningPeriod()->wplp_id && $chain->status === 0;
    }
}
