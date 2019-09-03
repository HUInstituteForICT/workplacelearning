<?php

namespace Tests\Unit;

use App\Student;
use App\Validators\DateInLearningPeriodValidator;
use App\WorkplaceLearningPeriod;
use Illuminate\Http\Request;
use Illuminate\Validation\Validator;
use Tests\TestCase;

class WplpDateValidationTestTest extends TestCase
{
    private function getRequestMock()
    {
        /** @var Request|\PHPUnit_Framework_MockObject_MockObject $mock */
        $mock = $this->createMock(Request::class);
        $mock->expects($this->once())->method('user')->willReturn($this->getUserMock());

        return $mock;
    }

    private function getUserMock()
    {
        /** @var Student|\PHPUnit_Framework_MockObject_MockObject $mock */
        $mock = $this->createMock(Student::class);
        $mock->expects($this->once())->method('hasCurrentWorkplaceLearningPeriod')->willReturn(true);
        $mock->expects($this->once())->method('getCurrentWorkplaceLearningPeriod')->willReturn($this->getWplpMock());

        return $mock;
    }

    private function getWplpMock()
    {
        /** @var WorkplaceLearningPeriod|\PHPUnit_Framework_MockObject_MockObject $mock */
        $mock = $this->createMock(WorkplaceLearningPeriod::class);
        $mock->method('__get')->willReturn('1-1-2018', '31-1-2018');
//        $mock->method('__get')->with('enddate')->willReturn('31-1-2018');

        return $mock;
    }

    private function getValidatorMock()
    {
        /** @var Validator|\PHPUnit_Framework_MockObject_MockObject $mock */
        $mock = $this->createMock(Validator::class);
        $mock->expects($this->once())->method('addReplacer');

        return $mock;
    }

    public function testDate(): void
    {
        $validator = new DateInLearningPeriodValidator($this->getRequestMock());
        $passes = $validator->validate('date', '10-1-2018', [], $this->getValidatorMock());

        $this->assertTrue($passes);

        // Reinit mocks
        $validator = new DateInLearningPeriodValidator($this->getRequestMock());
        $fails = $validator->validate('date', '10-2-2018', [], $this->getValidatorMock());

        $this->assertFalse($fails);
    }
}
