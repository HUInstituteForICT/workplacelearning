<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CanvasRegisterRequest extends FormRequest
{
    public function rules(): array
    {
        if ($this->isMethod('get')) {
            return [];
        }

        return [
            'studentnr' => 'required|digits:7|unique:student',
            'education' => [
                'required',
                Rule::exists('educationprogram', 'ep_id')->where(function ($query) {
                    $query->where('disabled', '=', 0);
                }),
            ],
        ];
    }
}
