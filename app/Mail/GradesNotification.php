<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class GradesNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $student; // Add a public property for data

    /**
     * Create a new message instance.
     *
     * @param $student
     */
    public function __construct($student)
    {
        $this->student = $student; // Initialize the property
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Grades Notification',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.grades_notification', // Update this to your actual view path
            with: ['student' => $this->student], // Pass data to the view
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return []; // Add any attachments if needed
    }
}
