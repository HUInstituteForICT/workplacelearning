<?php

namespace App\Console\Commands;

use App\Notifications\NoActivityOfStudent;
use App\Notifications\NotificationRouter;
use App\Student;
use App\WorkplaceLearningPeriod;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;

class NoRegisteredHoursChecker extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'activities:no-recent';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks for each student if they registered hours in the last x days';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(): void
    {
        /** @var Collection<Student> $users */
        $users = Student::where('userlevel', Student::STUDENT)->get();

        /** @var Student $student */
        foreach($users as $student) {

            if(!$student->hasCurrentWorkplaceLearningPeriod()) {
                continue; // No active period
            }

            /** @var WorkplaceLearningPeriod $wplp */
            $wplp = $student->getCurrentWorkplaceLearningPeriod();

            // If the WPLP is over, stop trying to notify
            // If no teacher, noone to notify
            if(!$wplp->hasTeacher() || $wplp->enddate < new \DateTime()) {
                continue;
            }

            $days = $wplp->daysSinceLastActivity();

            // We don't check with greater than (>) because that would result in a new notification each day
            // Just use 5 as the threshold.
            if($days === 5) {
                $wplp->teacher->notify(new NoActivityOfStudent($student));
            }
        }
    }
}
