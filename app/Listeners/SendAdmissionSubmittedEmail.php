<?php

// app/Listeners/SendAdmissionApprovedEmail.php
namespace App\Listeners;

use App\Events\AdmissionApproved;
use App\Mail\AdmissionSubmittedEmail;
use Illuminate\Support\Facades\Mail;

class SendAdmissionSubmittedEmail
{
    public function handle(AdmissionApproved $event)
    {
        $admission = $event->admission;
        try {
            Mail::to($admission->email)->send(new AdmissionSubmittedEmail($admission));
            \Log::info('Admission approval email sent to: ' . $admission->email);
        } catch (\Exception $e) {
            \Log::error('Failed to send email: ' . $e->getMessage());
        }
        
    }
}
