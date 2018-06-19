<?php

namespace App\Http\Requests\Chain;

class SaveRequest extends \Illuminate\Foundation\Http\FormRequest
{
    public function authorize(): bool {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|max:255|min:1',
            'status' => 'required|digits_between:0,1'
        ];
    }
}