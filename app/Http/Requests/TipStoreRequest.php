<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TipStoreRequest extends FormRequest
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
            'name' => 'required|max:255',
//            "threshold" => 'required|numeric|min:0.1|max:1',
//            "tipText" => "required",
//            "statistic.id" => "exists:statistics,id"
        ];
    }
}
