<?php

namespace App\Services;

use App\Exceptions\UnexpectedUser;
use App\Student;
use Illuminate\Contracts\Auth\Guard;

class CurrentUserResolverTest extends \PHPUnit\Framework\TestCase
{
    public function testGetCurrentUser(): void
    {
        $student = $this->createMock(Student::class);

        $guard = $this->createMock(Guard::class);
        $guard->expects(self::exactly(2))->method('user')->willReturn($student, new \stdClass());

        $currentUserResolver = new CurrentUserResolver($guard);

        $this->assertSame($student, $currentUserResolver->getCurrentUser());

        $this->expectException(UnexpectedUser::class);
        $currentUserResolver->getCurrentUser();
    }
}
