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
            'name' => 'max:255|min:1',
            'status' => 'digits_between:0,1'
        ];
    }
}