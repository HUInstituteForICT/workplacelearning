<?php

namespace App\Http\Requests\LearningActivity;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class ProducingCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'datum'        => 'required|date|date_in_wplp',
            'omschrijving' => 'required',
            'aantaluren'   => 'required',
            'resource'     => 'required|in:persoon,alleen,internet,boek,new',
            'moeilijkheid' => 'required|exists:difficulty,difficulty_id',
            'status'       => 'required|exists:status,status_id',
            'chain_id'     => 'required|canChain',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->sometimes('newcat', 'sometimes|max:50', function ($input) {
            return $input->category_id === 'new';
        });

        $validator->sometimes('category_id', 'required|exists:category,category_id', function ($input) {
            return $input->category_id !== 'new';
        });

        $validator->sometimes('newswv', 'required|max:50', function ($input) {
            return ($input->personsource === 'new' && $input->resource === 'persoon');
        });

        $validator->sometimes('personsource', 'required|exists:resourceperson,rp_id', function ($input) {
            return ($input->personsource !== 'new' && $input->resource === 'persoon');
        });

        $validator->sometimes('internetsource', 'required|max:250', function ($input) {
            return $input->resource === 'internet';
        });

        $validator->sometimes('booksource', 'required|max:250', function ($input) {
            return $input->resource === 'book';
        });

        $validator->sometimes('newlerenmet', 'required|max:250', function ($input) {
            return $input->resource === 'new';
        });

        $validator->sometimes('aantaluren_custom', 'required|numeric', function ($input) {
            return $input->aantaluren === 'x';
        });
    }
}
