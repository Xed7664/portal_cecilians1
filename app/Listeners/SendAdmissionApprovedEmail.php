<?php

// app/Listeners/SendAdmissionApprovedEmail.php
namespace App\Listeners;

use App\Events\AdmissionApproved;
use App\Mail\AdmissionApprovedEmail;
use Illuminate\Support\Facades\Mail;

class SendAdmissionApprovedEmail
{
    public function handle(AdmissionApproved $event)
    {
        $admission = $event->admission;
        try {
            Mail::to($admission->email)->send(new AdmissionApprovedEmail($admission));
            \Log::info('Admission approval email sent to: ' . $admission->email);
        } catch (\Exception $e) {
            \Log::error('Failed to send email: ' . $e->getMessage());
        }
        
    }
}
