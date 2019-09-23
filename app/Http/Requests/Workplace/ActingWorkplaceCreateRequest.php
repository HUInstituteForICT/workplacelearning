<?php

declare(strict_types=1);

namespace App\Http\Requests\Workplace;

use App\Repository\Eloquent\CohortRepository;
use App\Rules\CohortInStudentEducationProgram;
use App\Services\CurrentUserResolver;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ActingWorkplaceCreateRequest extends FormRequest
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
            'companyHousenr' => 'required|max:9|min:1',

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
            'cohort'               => [
                'required',
                Rule::exists('cohorts', 'id')->where(function (Builder $query) {
                    $query->where('disabled', '=', 0);
                }),
                new CohortInStudentEducationProgram($this->container->get(CurrentUserResolver::class), $this->container->get(CohortRepository::class)),
            ],
        ];
    }
}
