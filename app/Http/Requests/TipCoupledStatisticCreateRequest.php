<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TipCoupledStatisticCreateRequest extends FormRequest
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
            'statistic_id'       => 'required',
            'tip_id'             => 'required',
            'comparisonOperator' => 'required',
            'threshold'          => 'required|numeric',
        ];
    }
}
