<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Repository\Eloquent\WorkplaceLearningPeriodRepository;
use App\Rules\CohortInStudentEducationProgram;
use App\WorkplaceLearningPeriod;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Fluent;
use RuntimeException;

class EditWorkplaceLearningPeriodRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Is always within Admin middleware
        return true;
    }

    public function rules(): array
    {
        if (!$this->isMethod('POST')) {
            return [];
        }

        return [
            'workplace.name'       => ['required', 'string', 'max:100'],
            'workplace.street'     => ['required', 'string', 'max:45'],
            'workplace.housenr'    => ['required', 'string', 'max:45'],
            'workplace.postalcode' => ['required', 'string', 'max:45', 'postalcode'],
            'workplace.town'       => ['required', 'string', 'max:45'],
            'workplace.country'    => ['required', 'string', 'max:255'],

            'workplace.person' => ['required', 'max:100', 'min:3'],
            'workplace.phone'  => ['required', 'max:20'],
            'workplace.email'  => ['required', 'email', 'max:255'],

            'workplaceLearningPeriod.days'          => ['required', 'integer', 'min:1'],
            'workplaceLearningPeriod.hours_per_day' => ['required', 'numeric', 'min: 1', 'max:24'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        if (!$this->isMethod('POST')) {
            return;
        }

        /** @var CohortInStudentEducationProgram $cohortInProgramRule */
        $cohortInProgramRule = $this->container->get(CohortInStudentEducationProgram::class);
        // Make the rule perform on the student we're using, instead of authenticated user
        $cohortInProgramRule->setStudent($this->getWorkplaceLearningPeriod()->student);

        // The cohort of a WPLP is only updatable if the WPLP has no activities registered yet
        // Otherwise entities attached to activities will point to different cohorts
        $validator->sometimes('workplaceLearningPeriod.cohort_id', [
            'required',
            'integer',
            'exists:cohorts,id',
        ], function (Fluent $input) {
            $workplaceLearningPeriod = $this->getWorkplaceLearningPeriod();

            return $workplaceLearningPeriod->learningActivityProducing->count() === 0 && $workplaceLearningPeriod->learningActivityActing->count() === 0;
        });
    }

    private function getWorkplaceLearningPeriod(): WorkplaceLearningPeriod
    {
        /** @var WorkplaceLearningPeriodRepository $repository */
        $repository = $this->container->get(WorkplaceLearningPeriodRepository::class);

        $id = $this->get('id');

        if (!$id) {
            throw new RuntimeException('Expected an ID, found none');
        }

        return $repository->get((int) $id);
    }
}
