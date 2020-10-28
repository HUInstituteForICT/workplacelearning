<?php


namespace App\Interfaces;

use App\Cohort;
use App\ResourcePerson;
use App\Student;
use phpDocumentor\Reflection\Types\Collection;

interface StudentSystemServiceInterface
{
    public function getAllStudents(): Collection;
    public function getAllResourcePersons(): Collection;
    public function getAllCohorts(): Collection;

    //Student domain
    public function getStudentsByEPId(int $epId): Collection;
    public function getStudentByWPLId(int $wplId): Student;
    public function getStudentByFolderId(int $folderId): Student;
    public function getStudentByFolderCommentId(int $folderCommentId): Student;
    public function getStudentBySLIId(int $sliId): Student;
    public function getStudentByWorkplaceId(int $workplaceId): Student;

    //ResourcePerson domain
    public function getResourcePersonsByEPId(int $epId): Collection;
    public function getResourcePersonsByWPLId(int $wplId): Collection;
    public function getResourcePersonByLAPId(int $lapId): ResourcePerson;
    public function getResourcePersonByLAAId(int $laaId): ResourcePerson;
    
    //Cohort domain
    public function getCohortsByEPId(int $epId): Collection;
    public function getCohortByWPLId(int $wplId): Cohort;
    public function getCohortByCategoryId(int $catId): Cohort;
    public function getCohortByCompetenceId(int $compId): Cohort;
    public function getCohortByCompetenceDescriptionId(int $compDescrId): Cohort;
    public function getCohortByTimeslotId(int $timeslotId): Cohort;











}