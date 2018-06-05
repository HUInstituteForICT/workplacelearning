<?php

namespace Tests\Feature;

use App\Student;

trait GenericUserTrait
{
    public function getUser($type): Student {
        /** @var \App\EducationProgram $ep */
        $ep = factory(\App\EducationProgram::class)->states($type)->create();
        /** @var Student $user */
        $user = factory(Student::class)->create(['ep_id' => $ep->ep_id]);

        /** @var \App\Cohort $cohort */
        $cohort = factory(\App\Cohort::class)->create(['ep_id' => $ep->ep_id]);

        /** @var \App\WorkplaceLearningPeriod $wplp */
        $wplp = factory(\App\WorkplaceLearningPeriod::class)->make([
            'startdate' => \Carbon\Carbon::now()->subDay(5),
            'enddate' => \Carbon\Carbon::now()->addDay(5),
            'cohort_id' => $cohort->id,
        ]);

        $user->workplaceLearningPeriods()->save($wplp);
        $user->setActiveWorkplaceLearningPeriod($wplp);

        if($type === 'acting') {
            $user->getCurrentWorkplaceLearningPeriod()->learningGoals()->create([
                'learninggoal_label' => 'Test',
                'description'        => 'some test label',
            ]);

            $wplp->cohort->competencies()->create(['competence_label' => 'Test competence']);
        }

        return $user;
    }
}