<?php

namespace App\Mail;

use App\Models\Admission;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdmissionSubmittedEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $admission;

    public function __construct(Admission $admission)
    {
        $this->admission = $admission;
    }

    public function build()
    {
        return $this->subject('Your Admission Application Has Been Submitted')
                    ->markdown('emails.admission_submitted')
                    ->with([
                        'admission' => $this->admission,
                        'tracker_code' => $this->admission->tracker_code, // Use the saved tracker code
                    ]);
    }
}
