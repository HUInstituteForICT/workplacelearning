<?php

namespace App\Console\Commands;

use App\Notifications\NotificationRouter;
use App\Student;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;

class DailyDigest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:digest:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends all notifications that belongs to users with daily digest preference';

    private $notificationRouter;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(NotificationRouter $notificationRouter)
    {
        parent::__construct();
        $this->notificationRouter = $notificationRouter;
    }


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        /** @var Collection<Student> $users */
        $users = Student::where('digest_period', Student::DAILY)->get();

        $this->notificationRouter->routeForUsers($users);
    }
}
