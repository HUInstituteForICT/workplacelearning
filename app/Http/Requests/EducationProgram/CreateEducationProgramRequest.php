<?php

namespace App\Http\Requests\EducationProgram;

use Illuminate\Foundation\Http\FormRequest;

class CreateEducationProgramRequest extends FormRequest
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
            'ep_name' => 'required',
            'eptype_id' => 'required|exists:educationprogramtype,eptype_id',
        ];
    }
}
