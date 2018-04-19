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
}
