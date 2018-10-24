<?php

namespace App\Http\Requests\LearningActivity;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class ActingCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'date' => 'required|date|date_in_wplp',
            'description' => 'required|max:1500',
            'learned' => 'required|max:1000',
            'support_wp' => 'max:500',
            'support_ed' => 'max:500',
            'learning_goal' => 'required|exists:learninggoal,learninggoal_id',
            'competence' => 'required|min:1',
            'competence.*' => 'required|exists:competence,competence_id',
            'evidence.*' => 'file|max:5000',
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

        $validator->sometimes('res_material_detail', 'required_unless:res_material,none|max:75', function ($input) {
            return $input->res_material >= 1;
        });
    }
}
