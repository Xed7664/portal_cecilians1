<?php

// app/Mail/PreEnrollmentConfirmation.php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PreEnrollmentConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $student;
    public $referenceCode;

    public function __construct($student, $referenceCode)
    {
        $this->student = $student;
        $this->referenceCode = $referenceCode;
    }

    public function build()
    {
        return $this->subject('Pre-Enrollment Confirmation')
                    ->view('emails.pre_enrollment_confirmation');
    }
}

