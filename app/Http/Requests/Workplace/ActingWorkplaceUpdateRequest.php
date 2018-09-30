<?php

namespace App\Http\Requests\Workplace;

use Illuminate\Foundation\Http\FormRequest;

class ActingWorkplaceUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'companyName'    => 'required|max:255|min:3',
            'companyStreet'  => 'required|max:45|min:3',
            'companyHousenr' => 'required|max:4|min:1',

            'companyPostalcode'    => 'required|postalcode',
            'companyLocation'      => 'required|max:255|min:3',
            'companyCountry'       => 'required|max:255|min:2',
            'contactPerson'        => 'required|max:255|min:3',
            'contactPhone'         => 'required',
            'contactEmail'         => 'required|email|max:255',
            'numdays'              => 'required|integer|min:1',
            'startdate'            => 'required|date|after:'.date('Y-m-d', strtotime('-6 months')),
            'enddate'              => 'required|date|after:startdate',
            'internshipAssignment' => 'required|min:15|max:500',
            'isActive'             => 'sometimes|required|in:1,0',
        ];
    }
}
