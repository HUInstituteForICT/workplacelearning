<?php


namespace App\Services;

use App\Cohort;
use App\Interfaces\StudentSystemServiceInterface;
use App\Repository\Eloquent\CohortRepository;
use App\Repository\Eloquent\ResourcePersonRepository;
use App\Repository\Eloquent\StudentRepository;
use App\ResourcePerson;
use App\Student;
use Illuminate\Database\Eloquent\Collection;

class StudentSystemServiceImpl implements StudentSystemServiceInterface
{
    /**
     * @var CohortRepository
     */
    private $cohortRepository;

    /**
     * @var ResourcePersonRepository
     */
    private $resourcePersonRepository;

    /**
     * @var StudentRepository
     */
    private $studentRepository;

    public function __construct(CohortRepository $cohortRepository, ResourcePersonRepository $resourcePersonRepository, StudentRepository $studentRepository)
    {
        $this->cohortRepository = $cohortRepository;
        $this->resourcePersonRepository = $resourcePersonRepository;
        $this->studentRepository = $studentRepository;
    }

    public function getAllStudents(): Collection
    {
        return $this->studentRepository->all();
    }

    public function getAllResourcePersons(): array
    {
        return $this->resourcePersonRepository->all();
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

    public function saveStudent(Student $student): bool
    {
        return $this->studentRepository->save($student);
    }

    public function deleteStudent(Student $student): void
    {
         $this->studentRepository->delete($student);
    }

    public function searchStudents(array $filters, ?int $pages, array $relations)
    {
        return $this->studentRepository->search($filters = [], $pages = 25, $relations = []);
    }

    public function findByEmailOrCanvasId(string $email, string $canvasUserId): ?Student
    {
        return $this->studentRepository->findByEmailOrCanvasId($email, $canvasUserId);
    }

    public function findByStudentNumber(string $studentNumber)
    {
        return $this->studentRepository->findByStudentNumber($studentNumber);
    }

    public function findByLastName(string $teacherLastname)
    {
        return $this->studentRepository->findByLastName($teacherLastname);
    }

    public function getSearchFilters(): array
    {
        return $this->studentRepository->getSearchFilters();
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
    public function cohortsAvailableForStudent(Student $student): array
    {
        return $this->cohortRepository->cohortsAvailableForStudent($student);
    }
}