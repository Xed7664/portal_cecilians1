<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Mail\GradeReleaseNotification;
use Illuminate\Support\Facades\Mail;
use App\Models\Grade;

class GradeReleaseScheduleCommand extends Command
{
    // Command signature that can be used in the terminal
    protected $signature = 'grades:release';

    // Command description
    protected $description = 'Send grade release notifications to students';

    public function handle()
    {
        // Fetch grades that are scheduled for release today
        $grades = Grade::where('release_date', now()->toDateString())->get();

        foreach ($grades as $grade) {
            Mail::to($grade->student->email)->send(new GradeReleaseNotification($grade));
        }

        $this->info('Grade release notifications sent successfully.');
    }
}

