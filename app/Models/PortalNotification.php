<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PortalNotification extends Model
{
    use HasFactory;

    protected $table = 'portal_notifications';

    protected $fillable = [
        'title',
        'content',
        'type',
    ];

    protected $casts = [
        'type' => 'string',
    ];

    public function userNotifications()
    {
        return $this->hasMany(UserPortalNotification::class);
    }

    /**
     * Replace placeholders in content with actual values.
     *
     * @param array $values
     * @return string
     */
    public function parseContent(array $values)
    {
        $content = $this->content;

        // Regular expression to match placeholders like {Users.username}
        $pattern = '/{Users\.(\w+)}/';
        preg_match_all($pattern, $content, $matches);

        // Replace each placeholder with the corresponding user attribute
        foreach ($matches[1] as $attribute) {
            $replacement = isset($values['sender_id']) ? User::find($values['sender_id'])->$attribute : null;
            $content = str_replace("{Users.$attribute}", $replacement, $content);
        }

        return $content;
    }

}
