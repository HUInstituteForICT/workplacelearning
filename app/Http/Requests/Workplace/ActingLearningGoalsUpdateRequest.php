<?php

namespace App\Http\Requests\Workplace;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class ActingLearningGoalsUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'learningGoal.*.label'        => 'min:3|max:50',
            'learningGoal.*.description'  => 'min:3, max:1000',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->sometimes(
            'new_learninggoal_name',
            'required|min:3|max:50',
            function ($input) {
                return strlen($input->new_learninggoal_name) > 0;
            }
        );

        $validator->sometimes(
            'new_learninggoal_description',
            'required|min:3|max:1000',
            function ($input) {
                return strlen($input->new_learninggoal_name) > 0;
            }
        );
    }
}
