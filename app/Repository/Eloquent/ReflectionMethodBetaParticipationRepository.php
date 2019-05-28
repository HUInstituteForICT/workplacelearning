<?php


namespace App\Repository\Eloquent;

use App\Exceptions\UnexpectedUser;
use App\ReflectionMethodBetaParticipation;
use App\Services\CurrentUserResolver;
use App\Student;
use Illuminate\Support\Collection;


class ReflectionMethodBetaParticipationRepository
{

    /**
     * @var CurrentUserResolver
     */
    private $currentUserResolver;

    public function __construct(CurrentUserResolver $currentUserResolver)
    {
        $this->currentUserResolver = $currentUserResolver;
    }

    public function doesCurrentUserParticipate(): bool
    {
        try {
            return $this->doesStudentParticipate($this->currentUserResolver->getCurrentUser());
        } catch (UnexpectedUser $unexpectedUser) {
            return false;
        }
    }

    public function doesStudentParticipate(Student $student): bool
    {
        return ReflectionMethodBetaParticipation::where('student_id', '=', $student->student_id)
                ->where('participates', '=', true)
                ->count() > 0;
    }

    public function hasStudentDecided(Student $student): bool
    {
        return ReflectionMethodBetaParticipation::where('student_id', '=', $student->student_id)->count() > 0;
    }

    public function getParticipations(): Collection
    {
        return ReflectionMethodBetaParticipation::with('student')->where('participates', '=', true)->get();
    }

    public function decideForStudent(Student $student, bool $participates): void
    {
        $participation = new ReflectionMethodBetaParticipation();
        $participation->participates = $participates;
        $participation->student()->associate($student);

        $participation->save();
    }

    public function leaveBeta(Student $student): void
    {
        if (!$this->doesStudentParticipate($student)) {
            return;
        }

        $participation = ReflectionMethodBetaParticipation::where('student_id', '=', $student->student_id)->first();
        $participation->delete();
    }
}