<?php

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
            'notfinished' => 'required',
            'support_requested' => 'in:0,1,2',
            'supported_provided_wp' => 'max:150',
            'initiatief' => 'max:500',
            'progress_satisfied' => 'in:1,2',
            'vervolgstap_zelf' => 'max:150',
            'ondersteuning_werkplek' => 'max:150',
            'ondersteuning_opleiding' => 'max:150',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->sometimes('newnotfinished', 'required|max:150', function ($input) {
            return 'Anders' === $input->notfinished;
        });
    }
}
