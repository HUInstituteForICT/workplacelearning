<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TipUpdateRequest extends FormRequest
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
            'tip.name'            => 'required',
            'tip.tipText'         => 'required|max:1000',
            'tip.enabled_cohorts' => 'array',
        ];
    }
}
