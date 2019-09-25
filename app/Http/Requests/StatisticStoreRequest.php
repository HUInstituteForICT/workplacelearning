<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StatisticStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name'                   => 'required|max:255',
            'education_program_type' => 'required|in:acting,producing',
            'select_type'            => 'required|in:count,hours',
            'operator'               => 'numeric|min:0|max:3',
        ];
    }
}
