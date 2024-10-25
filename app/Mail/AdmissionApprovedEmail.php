<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdmissionApprovedEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $admission;

    public function __construct($admission)
    {
        $this->admission = $admission;
    }

    public function build()
    {
        $studentID = 'SCC-' . date('y') . '-' . str_pad($this->admission->id, 7, '0', STR_PAD_LEFT);

        return $this->markdown('emails.admission_approved')
                    ->with([
                        'admission' => $this->admission,
                        'studentID' => $studentID,
                    ]);
    }
}


