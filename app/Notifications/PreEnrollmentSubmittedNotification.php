<?php

namespace App\Notifications;

use App\Models\Student;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\DB;
use App\Models\{PortalNotification, UserPortalNotification};

class PreEnrollmentSubmittedNotification extends Notification
{
    use Queueable;

    public $referenceCode;

    public function __construct($referenceCode)
    {
        $this->referenceCode = $referenceCode;
    }

    public function via($notifiable)
    {
        return ['mail']; // Default to only sending mail
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('Your pre-enrollment has been submitted.')
                    ->line('Reference Code: ' . $this->referenceCode)
                    ->action('View Details', url('/'))
                    ->line('Thank you for enrolling!');
    }

    /**
     * Stores notification in the `portal_notifications` and `user_portal_notifications` tables.
     */
    public function storeInPortalNotifications($notifiable)
    {
        // Insert notification data into `portal_notifications` table
        $portalNotification = PortalNotification::create([
            'title' => 'Pre-Enrollment Submitted',
            'content' => 'Your pre-enrollment has been submitted. Reference Code: ' . $this->referenceCode,
            'type' => 'pre_enrollment',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    
      // Get the related user from the student (notifiable)
    $notifiableUser = $notifiable->user;  // Use $notifiable instead of $student

    
        if ($notifiableUser) {
            // Proceed with inserting the notification dynamically for the user
            UserPortalNotification::create([
                'user_id' => $notifiableUser->id,  // Use the dynamic user ID from `$notifiable`
                'portal_notification_id' => $portalNotification->id,
                'sender_id' => auth()->user()->id,  // Sender is the currently logged-in user
                'type' => 'pre_enrollment',  // Specify the type explicitly
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            return redirect()->back()->with('error', 'User not found.');
        }
    }
    
}
