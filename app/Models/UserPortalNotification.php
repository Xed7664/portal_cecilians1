<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{PortalNotification};

class UserPortalNotification extends Model
{
    use HasFactory;

    protected $table = 'user_portal_notification';

    protected $fillable = [
        'user_id',
        'portal_notification_id',
        'sender_id',
        'type',
        'related_id',
        'value_1',
        'value_2',
        'read_at', 
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function portalNotification()
    {
        return $this->belongsTo(PortalNotification::class);
    }

    public function notificationUrl()
    {
        // $type = $this->portalNotification->type;

        // if ($type === 'announcement' || $type === 'reaction' || $type === 'comment') {
        //     // Replace 'YOUR_DOMAIN' with your actual domain
        //     // Replace 'profile' and 'posts' with your actual route names
        //     return url("profile/{$this->sender->username}/posts/not_yet_implemented");
        // }

        switch($this->type){
            case 'post': case 'repost':
                return url("profile/{$this->sender->username}/posts/{$this->related_id}");
                break;

            case 'react_post':
                return url("profile/{$this->user->username}/posts/{$this->related_id}");
                break;
        }

        return null; // Return null for other notification types or if no URL is specified
    }
}
