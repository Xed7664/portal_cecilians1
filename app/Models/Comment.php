<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'post_id',
        'repost_id',
        'content',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    // public function reactions()
    // {
    //     return $this->hasMany(Reaction::class, 'id');
    // }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    public function isReactedBy(User $user, $type)
    {
        return $this->reactions()->where('user_id', $user->id)->where('type', $type)->exists();
    }

    public function reactions(): MorphMany
    {
        return $this->morphMany(Reaction::class, 'reactable');
    }

    public function mentions()
    {
        return $this->hasMany(Mention::class);
    }

    public function addMention(User $user)
    {
        $mention = new Mention(['user_id' => $user->id]);
        $this->mentions()->save($mention);
    }

    public function react($type)
    {
        $user = Auth::user();

        $this->reactions()->updateOrCreate(
            ['user_id' => $user->id, 'type' => $type],
            ['reacted_at' => now()]
        );
    }

    public function hashtags()
    {
        return $this->belongsToMany(Hashtag::class, 'comment_hashtags', 'comment_id', 'hashtag_id');
    }

    public function addHashtag(string $tag)
    {
        $hashtag = Hashtag::firstOrCreate(['tag' => $tag]);
        $this->hashtags()->attach($hashtag->id);
    }

    public function unreact($type)
    {
        $user = Auth::user();

        $this->reactions()
            ->where('user_id', $user->id)
            ->where('type', $type)
            ->delete();
    }

    public function formatTimestamp()
    {
        $now = now(); // Current timestamp
        $diff = $now->diffInSeconds($this->created_at); // Assuming 'created_at' is the timestamp attribute of your post

        if ($diff < 60) {
            return 'now'; // Less than a minute
        } elseif ($diff < 3600) {
            $minutes = round($diff / 60);
            return $minutes . 'm'; // Minutes
        } elseif ($diff < 86400) {
            $hours = round($diff / 3600);
            return $hours . 'h'; // Hours
        } elseif ($diff < 604800) {
            $days = round($diff / 86400);
            return $days . 'd'; // Days
        } elseif ($diff < 2419200) {
            $weeks = round($diff / 604800);
            return $weeks . 'w'; // Weeks
        } elseif ($diff < 29030400) {
            $months = round($diff / 2419200);
            return $months . 'mo'; // Months
        } else {
            $years = round($diff / 29030400);
            return $years . 'y'; // Years
        }
    }

    public function getContentWithMentionsAndHashtags()
    {
        $content = $this->content;

        // Use htmlspecialchars to prevent HTML encoding
        $escapedContent = htmlspecialchars($content, ENT_QUOTES, 'UTF-8');

        // Replace hashtags with links or other formatting
        $hashtagPattern = '/#(?!&)(\w+)/';
        $escapedContent = preg_replace_callback($hashtagPattern, function ($matches) {
            $hashtag = $matches[1];
            
            // Check if the hashtag exists in your Hashtag model
            $hashtagModel = Hashtag::where('tag', $hashtag)->first();
            if ($hashtagModel) {
                $hashtagLink = "<a href='" . route('hashtag', Str::lower($hashtag)) . "'>#$hashtag</a>";
                return $hashtagLink;
            }
            return '#' . $hashtag; // Keep the original hashtag if it doesn't exist in your Hashtag model
        }, $escapedContent);

        // Replace mentions with links or other formatting
        $mentionPattern = '/@(\w+)/';
        $escapedContent = preg_replace_callback($mentionPattern, function ($matches) {
            $mention = $matches[1];

            // Check if the mention exists in your database
            $mentionedUser = User::where('username', $mention)->first();
            if ($mentionedUser) {
                $mentionLink = "<a href='" . route('profile.show', $mentionedUser->username) . "'>@$mention</a>";
                return $mentionLink;
            }
            return '@' . $mention; // Keep the original mention if the user is not found
        }, $escapedContent);

        // Decode only the "#" character and special case "#039;" to prevent issues
        $decodedContent = str_replace(['&amp;#', '&amp;#039;'], ['&#', '&#039;'], $escapedContent);

        return $decodedContent;
    }
    

}
