<?php


namespace App\Tips\Services;


use App\Student;
use App\Tips\Models\Like;
use App\Tips\Models\Tip;

class TipManager
{

    /**
     * Create a tip from basic data
     * Tip can be used in next creation step
     * @param array $tipData
     * @return Tip
     */
    public function createTip(array $tipData)
    {
        $tip = new Tip;
        $tip->name = $tipData['name'];
        $tip->showInAnalysis = isset($tipData['showInAnalysis']);

        return $tip;
    }

    /**
     * Enable this tip for selected cohorts
     *
     * @param Tip $tip
     * @param array $data
     * @return Tip
     */
    public function enableCohorts(Tip $tip, array $data)
    {
        $tip->load('enabledCohorts');

        // Delete all current attached cohorts
        $tip->enabledCohorts()->detach();
        // Enable/attach all selected cohorts
        $tip->enabledCohorts()->attach($data['enabledCohorts']);

        return $tip;
    }

    /**
     * Add a new Like to a Tip given by a Student
     *
     * @param Tip $tip
     * @param int $type
     * @param Student $student
     * @return bool whether a new like has been added
     */
    public function likeTip(Tip $tip, $type, Student $student)
    {
        if ((new Like)->
                where('tip_id', '=', $tip->id)
                ->where('student_id', '=', $student->student_id)
                ->count() > 0) {
            return false;
        }

        $like = new Like;
        $like->type = $type;
        $like->tip()->associate($tip);
        $like->student()->associate($student);
        return $like->save();

    }
}