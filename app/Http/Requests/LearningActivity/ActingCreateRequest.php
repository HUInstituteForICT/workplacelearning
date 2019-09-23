<?php

declare(strict_types=1);

namespace App\Http\Requests\LearningActivity;

use App\Reflection\Models\ActivityReflection;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ActingCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'reflection.type'  => ['sometimes', Rule::in(ActivityReflection::TYPES)],
            'reflection.field' => 'sometimes|array',

            'date'          => 'required|date|date_in_wplp',
            'description'   => 'required|max:2000',
            'learned'       => 'max:1000',
            'support_wp'    => 'max:500',
            'support_ed'    => 'max:500',
            'learning_goal' => 'required|exists:learninggoal,learninggoal_id',
            'competence'    => 'required|min:1|max:3',
            'competence.*'  => 'required|exists:competence,competence_id',
            'evidence.*'    => 'file|max:20000',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->sometimes('res_person', 'required|exists:resourceperson,rp_id', function ($input) {
            return $input->res_person !== 'new';
        });

        $validator->sometimes('timeslot', 'required|exists:timeslot,timeslot_id', function ($input) {
            return $input->timeslot !== 'new';
        });

        $validator->sometimes('res_material', 'required|exists:resourcematerial,rm_id', function ($input) {
            return $input->res_material !== 'new' && $input->res_material !== 'none';
        });

        $validator->sometimes('new_rp', 'required|max:45', function ($input) {
            return $input->res_person === 'new';
        });

        $validator->sometimes('new_timeslot', 'required|max:45', function ($input) {
            return $input->timeslot === 'new';
        });

        $validator->sometimes('new_rm', 'required|max:45', function ($input) {
            return $input->res_material === 'new';
        });

        $validator->sometimes('res_material_detail', 'required_unless:res_material,none|max:200', function ($input) {
            return $input->res_material >= 1;
        });
    }
}
