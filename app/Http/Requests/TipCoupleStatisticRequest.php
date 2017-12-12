<?php

namespace App\Http\Requests;

use App\Tips\TipCoupledStatistic;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TipCoupleStatisticRequest extends FormRequest
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
        // Checkboxes won't be sent if not checked, so add stub in that case
        if(!$this->request->has('multiplyBy100')) {
            $this->request->add(['multiplyBy100' => false]);
        }

        return [
            'id' => 'required|exists:statistics',
            'comparison_operator' => ['required', Rule::in(array_keys(TipCoupledStatistic::COMPARISON_OPERATORS))],
            'threshold' => 'required|numeric',
            'multiplyBy100' => 'boolean',
            'save-and' => 'required|in:again,continue'
        ];
    }
}
