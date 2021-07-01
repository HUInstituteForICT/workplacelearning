<?php

declare(strict_types=1);

namespace App\Repository\Eloquent;

//use App\Evidence;
use App\GenericLearningActivity ;
use App\Student;

class GenericLearningActivityRepository
{
    /**
     * @var EvidenceRepository
     */
//    private EvidenceRepository $evidenceRepository;
//
//    public function __construct(EvidenceRepository $evidenceRepository)
//    {
//        $this->evidenceRepository = $evidenceRepository;
//    }

    /**
     * @param int $id
     * @return GenericLearningActivity
     */
    public function get(int $id): GenericLearningActivity
    {
        return GenericLearningActivity ::findOrFail($id);
    }

    /**
     * @param int[] $ids
     *
     * @return GenericLearningActivity []
     */
    public function getMultiple(array $ids): array
    {
        return GenericLearningActivity ::findMany($ids)->all();
    }

    /**
     * @param Student $student
     * @param int[] $ids
     *
     * @return GenericLearningActivity []
     */
    public function getMultipleForUser(Student $student, array $ids): array
    {
        return $student->getCurrentWorkplaceLearningPeriod()->genericLearningActivity()->whereIn('gla_id', $ids)->get()->all();
    }

    public function save(GenericLearningActivity $genericLearningActivity): bool
    {
        return $genericLearningActivity->save();
    }

    /**
     * @param Student $student
     * @return GenericLearningActivity []
     */
    public function getActivitiesForStudent(Student $student): array
    {
        return $student->getCurrentWorkplaceLearningPeriod()->genericLearningActivity()
            // TODO: Later wijzigen
            //->with('category', 'difficulty', 'status', 'resourcePerson', 'resourceMaterial', 'chain', 'feedback')
            //->with('timeslot', 'resourcePerson', 'resourceMaterial', 'learningGoal', 'competence')
            ->orderBy('date', 'DESC')
            ->get()->all();
    }

    /**
     * @param Student $student
     * @return array
     */
    public function getActivitiesOfLastActiveDayForStudent(Student $student): array
    {
        /** @var GenericLearningActivity $lastActiveActivity */
        $lastActiveActivity = $student->getCurrentWorkplaceLearningPeriod()->genericLearningActivity()->orderBy('date', 'DESC')->first();

        if (!$lastActiveActivity) {
            return [];
        }

        $dateOfLastActivity = $lastActiveActivity->date;

//TODO: genericlearningactivity heeft geen relatie meer met de tabellen status en difficulty
        return $student->getCurrentWorkplaceLearningPeriod()->genericLearningActivity()
            ->with('category', 'difficulty', 'status', 'resourcePerson', 'resourceMaterial', 'chain', 'feedback')
            ->where('date', '=', $dateOfLastActivity)
            ->get()->all();
    }

    /**
     * @param GenericLearningActivity $genericLearningActivity
     * @return bool
     */
    public function delete(GenericLearningActivity $genericLearningActivity): bool
    {
        try {
//              TODO: niet elke gla heeft een feedback en/of competence
//              $genericLearningActivity->competence()->detach();
//              $learningActivityProducing->feedback()->delete();

//                $genericLearningActivity->evidence->each(function (Evidence $evidence) {
//                $this->evidenceRepository->delete($evidence);
//            });


            return $genericLearningActivity->delete();
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * @param Student $student
     * @return GenericLearningActivity|null
     */
    public function earliestActivityForStudent(Student $student): ?GenericLearningActivity
    {
        $activity = $student->getCurrentWorkplaceLearningPeriod()->genericLearningActivity()->orderBy('date',
            'ASC')->first();

//        if (!$activity instanceof LearningActivityProducing && $activity !== null) {
//            throw new \RuntimeException('Expected result to be null or LearningActivityProducing, instead '.\get_class($activity));
//        }

        return $activity;
    }

    /**
     * @param Student $student
     * @return GenericLearningActivity|null
     */
    public function latestActivityForStudent(Student $student): ?GenericLearningActivity
    {
        $activity = $student->getCurrentWorkplaceLearningPeriod()->genericLearningActivity()->orderBy('date',
            'desc')->first();

//        if (!$activity instanceof LearningActivityProducing && $activity !== null) {
//            throw new \RuntimeException('Expected result to be null or LearningActivityProducing, instead '.\get_class($activity));
//        }

        return $activity;
    }
}

