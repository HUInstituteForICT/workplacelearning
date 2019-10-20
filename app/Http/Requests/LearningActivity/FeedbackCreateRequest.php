<?php

declare(strict_types=1);

namespace App\Http\Requests\LearningActivity;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class FeedbackCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'notfinished'             => 'required',
            'support_requested'       => 'in:0,1,2',
            'supported_provided_wp'   => 'max:1000',
            'initiatief'              => 'max:1000',
            'progress_satisfied'      => 'in:1,2',
            'vervolgstap_zelf'        => 'max:1000',
            'ondersteuning_werkplek'  => 'max:1000',
            'ondersteuning_opleiding' => 'max:1000',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->sometimes('newnotfinished', 'required|max:1000', function ($input) {
            return $input->notfinished === 'Anders';
        });
    }
}
