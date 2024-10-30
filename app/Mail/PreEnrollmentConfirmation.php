<?php

// app/Mail/PreEnrollmentConfirmation.php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\PDF;

class PreEnrollmentConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $student;
    public $referenceCode;
    public $pdf;

    public function __construct($student, $referenceCode, $pdf)
    {
        $this->student = $student;
        $this->referenceCode = $referenceCode;
        $this->pdf = $pdf;
    }

    public function build()
    {
        return $this->subject('Pre-Enrollment Confirmation')
                    ->view('emails.pre_enrollment_confirmation')
                    ->attachData($this->pdf->output(), 'PreEnrollmentConfirmation.pdf', [
                        'mime' => 'application/pdf',
                    ]);
    }
}

