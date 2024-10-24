<?php
namespace App\Mail;

use App\Models\Student;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdmissionApproved extends Mailable
{
    use Queueable, SerializesModels;

    public $studentID;
    public $fullName;

    /**
     * Create a new message instance.
     */
    public function __construct($studentID, $fullName)
    {
        // Assign the passed data to the class properties
        $this->studentID = $studentID;
        $this->fullName = $fullName;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Admission Approved'
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.admission-approved', // Path to your email view
            with: [
                'studentID' => $this->studentID,
                'fullName' => $this->fullName,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}
