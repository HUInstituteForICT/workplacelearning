<?php
namespace App\Repository\Eloquent;
use App\ReflectionMethodInterviewParticipation;
use App\Student;
use Illuminate\Support\Collection;
class ReflectionMethodBetaParticipationRepository
{
    public function doesStudentParticipate(Student $student): bool
    {
        return ReflectionMethodInterviewParticipation::where('student_id', '=', $student->student_id)
                ->where('participates', '=', true)
                ->count() > 0;
    }
    public function hasStudentDecided(Student $student): bool
    {
        return ReflectionMethodInterviewParticipation::where('student_id', '=', $student->student_id)->count() > 0;
    }
    public function getParticipations(): Collection
    {
        return ReflectionMethodInterviewParticipation::with('student')->where('participates', '=', true)->get();
    }
    public function decideForStudent(Student $student, bool $participates): void
    {
        $participation = new ReflectionMethodInterviewParticipation();
        $participation->participates = $participates;
        $participation->student()->associate($student);
        $participation->save();
    }

} 