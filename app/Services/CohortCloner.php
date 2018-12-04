<?php

namespace App\Services;

use App\Category;
use App\Cohort;
use App\Competence;
use App\Interfaces\IsTranslatable;
use App\Repository\Eloquent\LanguageLineRepository;
use App\ResourcePerson;
use App\Timeslot;
use Spatie\TranslationLoader\LanguageLine;

class CohortCloner
{
    /**
     * @var LanguageLineRepository
     */
    private $languageLineRepository;

    public function __construct(LanguageLineRepository $languageLineRepository)
    {
        $this->languageLineRepository = $languageLineRepository;
    }

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

    private function cloneLanguageLine(LanguageLine $languageLine, IsTranslatable $entity): void
    {
        $clonedLanguageLine = $languageLine->replicate();
        $clonedLanguageLine->key = $entity->uniqueSlug();

        $clonedLanguageLine->save();
    }

    private function cloneCategories(Cohort $cohort, Cohort $clone): void
    {
        foreach ($cohort->categories as $category) {
            /** @var IsTranslatable|Category $clonedEntity */
            $clonedEntity = $category->replicate();
            $clone->categories()->save($clonedEntity);

            if ($languageLine = $this->languageLineRepository->getLanguageLineForEntityByKey($category->uniqueSlug())) {
                $this->cloneLanguageLine($languageLine, $clonedEntity);
            }
        }
    }

    private function cloneCompetencies(Cohort $cohort, Cohort $clone): void
    {
        foreach ($cohort->competencies as $competence) {
            /** @var IsTranslatable|Competence $clonedEntity */
            $clonedEntity = $competence->replicate();
            $clone->competencies()->save($clonedEntity);

            if ($languageLine = $this->languageLineRepository->getLanguageLineForEntityByKey($competence->uniqueSlug())) {
                $this->cloneLanguageLine($languageLine, $clonedEntity);
            }
        }
    }

    private function cloneResourcePersons(Cohort $cohort, Cohort $clone): void
    {
        foreach ($cohort->resourcePersons as $resourcePerson) {
            /** @var IsTranslatable|ResourcePerson $clonedEntity */
            $clonedEntity = $resourcePerson->replicate();
            $clone->resourcePersons()->save($clonedEntity);

            if ($languageLine = $this->languageLineRepository->getLanguageLineForEntityByKey($resourcePerson->uniqueSlug())) {
                $this->cloneLanguageLine($languageLine, $clonedEntity);
            }
        }
    }

    private function cloneTimeslots(Cohort $cohort, Cohort $clone): void
    {
        foreach ($cohort->timeslots as $timeslot) {
            /** @var IsTranslatable|Timeslot $clonedEntity */
            $clonedEntity = $timeslot->replicate();
            $clone->timeslots()->save($clonedEntity);

            if ($languageLine = $this->languageLineRepository->getLanguageLineForEntityByKey($timeslot->uniqueSlug())) {
                $this->cloneLanguageLine($languageLine, $clonedEntity);
            }
        }
    }
}
