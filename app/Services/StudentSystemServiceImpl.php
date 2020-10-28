<?php


namespace App\Services;

use App\Cohort;
use App\Interfaces\StudentSystemServiceInterface;
use App\ResourcePerson;
use App\Student;
use phpDocumentor\Reflection\Types\Collection;

class StudentSystemServiceImpl implements StudentSystemServiceInterface
{
    public function __construct()
    {
    }

    public function getAllStudents(): Collection
    {
        // TODO: Implement getAllStudents() method.
    }

    public function getAllResourcePersons(): Collection
    {
        // TODO: Implement getAllResourcePersons() method.
    }

    public function getAllCohorts(): Collection
    {
        // TODO: Implement getAllCohorts() method.
    }

    public function getStudentsByEPId(int $epId): Collection
    {
        // TODO: Implement getStudentsByEPId() method.
    }

    public function getStudentByWPLId(int $wplId): Student
    {
        // TODO: Implement getStudentByWPLId() method.
    }

    public function getStudentByFolderId(int $folderId): Student
    {
        // TODO: Implement getStudentByFolderId() method.
    }

    public function getStudentByFolderCommentId(int $folderCommentId): Student
    {
        // TODO: Implement getStudentByFolderCommentId() method.
    }

    public function getStudentBySLIId(int $sliId): Student
    {
        // TODO: Implement getStudentBySLIId() method.
    }

    public function getStudentByWorkplaceId(int $workplaceId): Student
    {
        // TODO: Implement getStudentByWorkplaceId() method.
    }

    public function getResourcePersonsByEPId(int $epId): Collection
    {
        // TODO: Implement getResourcePersonsByEPId() method.
    }

    public function getResourcePersonsByWPLId(int $wplId): Collection
    {
        // TODO: Implement getResourcePersonsByWPLId() method.
    }

    public function getResourcePersonByLAPId(int $lapId): ResourcePerson
    {
        // TODO: Implement getResourcePersonByLAPId() method.
    }

    public function getResourcePersonByLAAId(int $laaId): ResourcePerson
    {
        // TODO: Implement getResourcePersonByLAAId() method.
    }

    public function getCohortsByEPId(int $epId): Collection
    {
        // TODO: Implement getCohortsByEPId() method.
    }

    public function getCohortByWPLId(int $wplId): Cohort
    {
        // TODO: Implement getCohortByWPLId() method.
    }

    public function getCohortByCategoryId(int $catId): Cohort
    {
        // TODO: Implement getCohortByCategoryId() method.
    }

    public function getCohortByCompetenceId(int $compId): Cohort
    {
        // TODO: Implement getCohortByCompetenceId() method.
    }

    public function getCohortByCompetenceDescriptionId(int $compDescrId): Cohort
    {
        // TODO: Implement getCohortByCompetenceDescriptionId() method.
    }

    public function getCohortByTimeslotId(int $timeslotId): Cohort
    {
        // TODO: Implement getCohortByTimeslotId() method.
    }
}