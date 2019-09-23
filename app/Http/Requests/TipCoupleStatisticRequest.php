<?php

declare(strict_types=1);

//
//namespace App\Http\Requests;
//
//use App\Tips\Models\TipCoupledStatistic;
//use Illuminate\Foundation\Http\FormRequest;
//use Illuminate\Support\Str;
//use Illuminate\Validation\Rule;
//
//class TipCoupleStatisticRequest extends FormRequest
//{
//    /**
//     * Determine if the user is authorized to make this request.
//     */
//    public function authorize(): bool
//    {
//        return true;
//    }
//
//    /**
//     * Get the validation rules that apply to the request.
//     */
//    public function rules(): array
//    {
//        $idRule = 'required|exists:statistics';
//        if (starts_with($this->request->get('id'),
//                'predefined-') && $this->doesPredefinedExist($this->request->get('id'))) {
//            $idRule = 'required';
//        }
//
//        return [
//            'id' => $idRule,
//            'comparison_operator' => ['required', Rule::in(array_keys(TipCoupledStatistic::COMPARISON_OPERATORS))],
//            'threshold' => 'required|numeric',
//        ];
//    }
//
//    private function doesPredefinedExist($predefinedString): bool
//    {
//        $methodName = Str::after($predefinedString, 'predefined-');
//
//        // Get all predefined statistics (producing, acting..)
//        $predefinedStatisticAnnotation = collect(PredefinedStatisticHelper::getData())
//            ->first(function ($predefinedStatistic) use ($methodName) {
//                return $methodName === $predefinedStatistic['method'];
//            });
//
//        return \is_array($predefinedStatisticAnnotation);
//    }
//}
