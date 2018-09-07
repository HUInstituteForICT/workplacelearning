<?php

namespace App\Services;

use App\LearningActivityActing;
use Carbon\Carbon;

class LAAUpdater
{
    public function update(LearningActivityActing $learningActivityActing, array $data): bool
    {
        $learningActivityActing->date = Carbon::parse($data['date'])->format('Y-m-d');
        $learningActivityActing->situation = $data['description'];
        $learningActivityActing->lessonslearned = $data['learned'];
        $learningActivityActing->support_wp = $data['support_wp'];
        $learningActivityActing->support_ed = $data['support_ed'];

        $learningActivityActing->timeslot()->associate($data['timeslot']);
        $learningActivityActing->resourcePerson()->associate($data['res_person']);
        $learningActivityActing->learningGoal()->associate($data['learning_goal']);
        $learningActivityActing->competence()->sync([$data['competence']]);

        if ($data['res_material'] === 'none') {
            $learningActivityActing->resourceMaterial()->dissociate();
        } else {
            $learningActivityActing->resourceMaterial()->associate($data['res_material']);
        }
        $learningActivityActing->res_material_detail = $data['res_material_detail'];



        return $learningActivityActing->save();
    }
}
