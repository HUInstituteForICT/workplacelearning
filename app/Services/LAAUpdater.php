<?php


namespace App\Services;


use App\LearningActivityActing;

class LAAUpdater
{
    public function update(LearningActivityActing $learningActivityActing, array $data): bool
    {
        $learningActivityActing->date = $data['date'];
        $learningActivityActing->timeslot_id = $data['timeslot'];
        $learningActivityActing->situation = $data['description'];
        $learningActivityActing->lessonslearned = $data['learned'];
        $learningActivityActing->support_wp = $data['support_wp'];
        $learningActivityActing->support_ed = $data['support_ed'];
        $learningActivityActing->res_person_id = $data['res_person'];
        $learningActivityActing->res_material_id = ('none' !== $data['res_material']) ? $data['res_material'] : null;
        $learningActivityActing->res_material_detail = $data['res_material_detail'];
        $learningActivityActing->learninggoal_id = $data['learning_goal'];

        $learningActivityActing->competence()->sync([$data['competence']]);

        return $learningActivityActing->save();
    }
}