<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReviewRequested extends Notification
{
    use Queueable;

    protected $student;
    protected $subject;

    public function __construct($student, $subject)
    {
        $this->student = $student;
        $this->subject = $subject;
    }

    public function via($notifiable)
    {
        return ['mail', 'database']; // You can also use 'mail' or other channels as needed
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line($this->student->name . ' has requested a review for the subject: ' . $this->subject->name)
                    ->action('Review Grades', url('/teacher/grades'))
                    ->line('Please review the grades and respond accordingly.');
    }

    public function toArray($notifiable)
    {
        return [
            'student_id' => $this->student->id,
            'subject_id' => $this->subject->id,
            'message' => $this->student->name . ' has requested a review for ' . $this->subject->name,
        ];
    }
}