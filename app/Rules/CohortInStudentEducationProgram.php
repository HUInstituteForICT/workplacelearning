<?php

declare(strict_types=1);

namespace App\Rules;

use App\Repository\Eloquent\CohortRepository;
use App\Services\CurrentUserResolver;
use Illuminate\Contracts\Validation\Rule;

class CohortInStudentEducationProgram implements Rule
{
    /**
     * @var CurrentUserResolver
     */
    private $currentUserResolver;
    /**
     * @var CohortRepository
     */
    private $cohortRepository;

    /**
     * Create a new rule instance.
     */
    public function __construct(CurrentUserResolver $currentUserResolver, CohortRepository $cohortRepository)
    {
        $this->currentUserResolver = $currentUserResolver;
        $this->cohortRepository = $cohortRepository;
    }

    public function passes($attribute, $id): bool
    {
        $cohort = $this->cohortRepository->get($id);
        $student = $this->currentUserResolver->getCurrentUser();

        return $cohort->educationProgram->is($student->educationProgram);
    }

    public function message(): string
    {
        return __('validation.cohort_not_in_ep');
    }
}
