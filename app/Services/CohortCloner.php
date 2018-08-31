<?php

namespace App\Services;

use App\Cohort;

class CohortCloner
{
    /**
     * Clone a cohort and its core relationships deeply.
     */
    public function clone(Cohort $cohort): Cohort
    {
        $cohort->load(['categories', 'competencies', 'resourcePersons', 'timeslots']);

        $clone = $this->cloneCohort($cohort);
        $this->cloneCategories($cohort, $clone);
        $this->cloneCompetencies($cohort, $clone);
        $this->cloneResourcePersons($cohort, $clone);
        $this->cloneTimeslots($cohort, $clone);

        $clone->push();
        $clone->refresh();

        return $clone;
    }

    private function cloneCohort(Cohort $cohort): Cohort
    {
        /** @var Cohort $clone */
        $clone = $cohort->replicate();
        $clone->name = 'Copy '.$clone->name;

        $clone->save();

        return $clone;
    }

    private function cloneCategories(Cohort $cohort, Cohort $clone): void
    {
        $clones = [];
        foreach ($cohort->categories as $category) {
            $clones[] = $category->replicate();
        }
        $clone->categories()->saveMany($clones);
    }

    private function cloneCompetencies(Cohort $cohort, Cohort $clone): void
    {
        $clones = [];
        foreach ($cohort->competencies as $competence) {
            $clones[] = $competence->replicate();
        }
        $clone->competencies()->saveMany($clones);
    }

    private function cloneResourcePersons(Cohort $cohort, Cohort $clone): void
    {
        $clones = [];
        foreach ($cohort->resourcePersons as $resourcePerson) {
            $clones[] = $resourcePerson->replicate();
        }
        $clone->resourcePersons()->saveMany($clones);
    }

    private function cloneTimeslots(Cohort $cohort, Cohort $clone): void
    {
        $clones = [];
        foreach ($cohort->timeslots as $timeslot) {
            $clones[] = $timeslot->replicate();
        }
        $clone->timeslots()->saveMany($clones);
    }
}
