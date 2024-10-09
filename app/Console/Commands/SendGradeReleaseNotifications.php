<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SendGradeReleaseNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-grade-release-notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
{
    // Get grades that are releasing now
    $grades = Grade::where('release_date', '=', now())->get();

    foreach ($grades as $grade) {
        // Send notification email to the student
        Mail::to($grade->student->email)->send(new GradeReleaseNotification($grade));
    }
}

}
