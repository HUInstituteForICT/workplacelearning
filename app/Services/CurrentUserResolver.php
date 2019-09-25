<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\UnexpectedUser;
use App\Student;
use Illuminate\Contracts\Auth\Guard;

class CurrentUserResolver
{
    /**
     * @var Guard
     */
    private $guard;

    public function __construct(Guard $guard)
    {
        $this->guard = $guard;
    }

    /**
     * @throws UnexpectedUser
     */
    public function getCurrentUser(): Student
    {
        $student = $this->guard->user();

        if ($student instanceof Student) {
            return $student;
        }

        throw new UnexpectedUser('Expected instance of Student::class, instead received '.\get_class($student));
    }
}
