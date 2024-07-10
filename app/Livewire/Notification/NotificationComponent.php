<?php

namespace App\Livewire\Notification;

use Livewire\{Component, WithPagination};
use App\Models\{UserPortalNotification, PortalNotification, User};

class NotificationComponent extends Component
{
    use WithPagination;

    public $notifications;
    public $allContentLoaded = false;
    public $page = 1;

    public function mount()
    {
        $this->notifications = $this->getLatestNotifications(config('portal.notification.first_notification_count'));
    }

    public function render()
    {
        return view('livewire.notification.notification-component', [
            'notifications' => $this->notifications,
        ]);
    }

    public function loadMore()
    {
        if ($this->allContentLoaded) {
            return;
        }

        $this->page++; // Increment the page number
        $newNotifications = $this->getLatestNotifications(config('portal.notification.first_notification_count'));

        if (empty($newNotifications)) {
            $this->allContentLoaded = true;
        } else {
            $this->notifications = collect($this->notifications)->concat($newNotifications);
        }
    }
    
    protected function getLatestNotifications($limit)
    {
        return UserPortalNotification::with(['portalNotification', 'sender', 'user'])
            ->where('user_id', auth()->user()->id)
            ->latest()
            ->paginate($limit, ['*'], 'page', $this->page)
            ->items();
    }

    public function markAsRead($notificationId)
    {
        $notification = UserPortalNotification::find($notificationId);
    
        if ($notification && $notification->user_id === auth()->user()->id) {
            $notification->update(['read_at' => now()]);
        }
    }
    
}
