<?php

namespace App\Http\Requests;

use App\Tips\Statistics\PredefinedStatisticHelper;
use App\Tips\TipCoupledStatistic;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
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
        if (!$this->request->has('multiplyBy100')) {
            $this->request->add(['multiplyBy100' => false]);
        }

        $idRule = 'required|exists:statistics';
        if (starts_with($this->request->get('id'),
                'predefined-') && $this->doesPredefinedExist($this->request->get('id'))) {
            $idRule = 'required';
        }

        return [
            'id'                  => $idRule,
            'comparison_operator' => ['required', Rule::in(array_keys(TipCoupledStatistic::COMPARISON_OPERATORS))],
            'threshold'           => 'required|numeric',
            'multiplyBy100'       => 'boolean',
            'save-and'            => 'required|in:again,continue',
        ];
    }

    private function doesPredefinedExist($predefinedString)
    {

        $methodName = Str::after($predefinedString, 'predefined-');

        // Get all predefined statistics (producing, acting..)
        $predefinedStatisticAnnotation = collect(PredefinedStatisticHelper::getProducingData())
            ->merge(
                collect(PredefinedStatisticHelper::getActingData())
            )
            ->first(function ($predefinedStatistic) use ($methodName) {
                return $methodName === $predefinedStatistic['method'];
            });

        return is_array($predefinedStatisticAnnotation);
    }
}
