<?php

declare(strict_types=1);

namespace App\Services;

use App\Cohort;
use Illuminate\Database\DatabaseManager;

class CohortManager
{
    /**
     * @var DatabaseManager
     */
    private $databaseManager;

    public function __construct(DatabaseManager $databaseManager)
    {
        $this->databaseManager = $databaseManager;
    }

    public function deleteCohort(Cohort $cohort): bool
    {
        if ($this->deleteRelated($cohort)) {
            return $cohort->delete();
        }

        return false;
    }

    private function deleteRelated(Cohort $cohort): bool
    {
        try {
            $this->databaseManager->transaction(function () use ($cohort) {
                $cohort->categories()->delete();
                $cohort->competencies()->delete();
                $cohort->competenceDescription()->delete();
                $cohort->resourcePersons()->delete();
                $cohort->timeslots()->delete();
                $cohort->tips()->detach();
            });
        } catch (\Throwable $e) {
            return false;
        }

        return true;
    }
}
