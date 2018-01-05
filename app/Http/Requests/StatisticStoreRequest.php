<?php

namespace App\Http\Requests;

use App\Tips\Statistics\Variables\CollectedDataStatisticVariable;
use Illuminate\Foundation\Http\FormRequest;

class StatisticStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "name" => "required|max:255",
            "operator" => "numeric|min:0|max:3",
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator) {
        $validator->sometimes('statisticVariableOneParameter', 'required|max:255', function($input) {
            return $input->statisticVariableOne['type'] === (new CollectedDataStatisticVariable)->getType() && $input->statisticVariableOne['hasParameters'];
        });

        $validator->sometimes('statisticVariableTwoParameter', 'required|max:255', function($input) {
            return $input->statisticVariableTwo['type'] === (new CollectedDataStatisticVariable)->getType() && $input->statisticVariableTwo['hasParameters'];
        });
    }
}
