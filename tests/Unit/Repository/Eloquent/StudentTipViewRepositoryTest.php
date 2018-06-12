<?php


namespace App\Repository\Eloquent;


use App\Student;
use App\Tips\StudentTipView;
use App\Tips\Tip;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder;
use Tests\TestCase;

class StudentTipViewRepositoryTest extends TestCase
{

    public function testCreateForTip()
    {
        /** @var Student|\PHPUnit_Framework_MockObject_MockObject $studentMock */
        $studentMock = $this->createMock(Student::class);
        $studentMock->expects(self::exactly(3))->method('__get')->with('student_id')->willReturn(1);

        $builderMock = $this->createMock(Builder::class);
        $builderMock->expects(self::exactly(2))->method('count')->willReturn(0, 1);

        $hasManyMock = $this->createMock(HasMany::class);
        $hasManyMock->expects(self::once())->method('save')->withAnyParameters();
        $hasManyMock->expects(self::exactly(2))->method('__call')->with('where')->willReturn($builderMock);

        /** @var Tip|\PHPUnit_Framework_MockObject_MockObject $tipMock */
        $tipMock = $this->createMock(Tip::class);
        $tipMock->expects(self::exactly(3))->method('studentTipViews')->willReturn($hasManyMock);

        $repo = new StudentTipViewRepository();
        $repo->createForTip($tipMock, $studentMock);
        $repo->createForTip($tipMock, $studentMock);

    }

    public function testSave()
    {
        /** @var StudentTipView|\PHPUnit_Framework_MockObject_MockObject $mock */
        $mock = $this->createMock(StudentTipView::class);
        $mock->expects(self::once())->method('save');

        $repo = new StudentTipViewRepository();
        $repo->save($mock);
    }
}
