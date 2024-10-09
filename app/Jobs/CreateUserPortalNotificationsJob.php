<?php

namespace App\Jobs;

use App\Models\UserPortalNotification;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateUserPortalNotificationsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $sender;
    protected $receivers;
    protected $notificationId;
    protected $content;

    /**
     * Create a new job instance.
     *
     * @param User   $sender
     * @param array  $receivers
     * @param int    $notificationId
     * @param string $content
     */
    public function __construct(User $sender, array $receivers, int $notificationId, string $content)
    {
        $this->sender = $sender;
        $this->receivers = $receivers;
        $this->notificationId = $notificationId;
        $this->content = $content;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Create user portal notifications
        //$this->createUserPortalNotifications();

        // Notify mentioned users
        $this->notifyMentionedUser();
    }

    protected function notifyMentionedUser(): void
    {
        // Iterate through receivers and create user portal notifications
        foreach ($this->receivers as $receiver) {

            $notificationData = [
                'portal_notification_id' => $this->notificationId,
                'sender_id' => $this->sender->id,
                'user_id' => $receiver['user_id'],
            ];

            UserPortalNotification::create($notificationData);
        }
    }

    // protected function notifyMentionedUser(): void
    // {
    //     $notificationData = [
    //         'portal_notification_id' => 4,
    //         'sender_id' => $this->sender->id,
    //         'user_id' => $this->mentionedUser->id,
    //     ];

    //     UserPortalNotification::create($notificationData);
    // }
}
