<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\{User, Mention, Comment, CommentHashtag, Reply, Hashtag, Repost};

/**
 * @property int $views_count
 */
class Post extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasUlids;

    protected $fillable = ['user_id', 'content', 'status', 'disable_comment', 'views_count'];

    // Define the user relationship (a post belongs to a user)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Define the revisions relationship (a post has many revisions)
    public function revisions()
    {
        return $this->hasMany(PostRevision::class);
    }

    public function reposts()
    {
        return $this->hasMany(Repost::class, 'post_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function reactions(): MorphMany
    {
        return $this->morphMany(Reaction::class, 'reactable');
    }

    public function mentions()
    {
        return $this->hasMany(Mention::class);
    }

    public function hashtags()
    {
        return $this->belongsToMany(Hashtag::class, 'post_hashtags', 'post_id', 'hashtag_id');
    }

    public function isReactedBy(User $user, string $type): bool
    {
        return $this->reactions()
            ->where('user_id', $user->id)
            ->where('type', $type)
            ->exists();
    }

    public function formatViewsCount($count)
    {
        //$viewsCount = $this->attributes['views_count'];

        if ($count < 1000) {
            return number_format($count);
        } elseif ($count < 1000000) {
            return number_format($count / 1000, 1) . 'k';
        } else {
            return number_format($count / 1000000, 1) . 'M';
        }
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

    public function react(string $reactionType, User $user)
    {
        // Check if the user has already reacted with the same type
        if (!$this->isReactedBy($user, $reactionType)) {
            // User is reacting with the selected type
            $reaction = new Reaction([
                'type' => $reactionType,
                'user_id' => $user->id,
            ]);

            $this->reactions()->save($reaction);
        }
    }

    public function unreact(string $reactionType, User $user)
    {
        // Check if the user has already reacted with the same type
        if ($this->isReactedBy($user, $reactionType)) {
            // User is unreacting, remove the reaction
            $this->reactions()
                ->where('user_id', $user->id)
                ->where('type', $reactionType)
                ->delete();
        }
    }

    public function addMention(User $user)
    {
        $mention = new Mention(['user_id' => $user->id]);
        $this->mentions()->save($mention);
    }

    public function addHashtag(string $tag)
    {
        $hashtag = Hashtag::firstOrCreate(['tag' => $tag]);
        $this->hashtags()->attach($hashtag->id);
    }

    public function markAsDeleted()
    {
        $this->status = 'deleted';
        $this->save();
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
    
    public function encryptId()
    {
        return Crypt::encryptString($this->id);
    }

    public static function decryptId($encryptedId)
    {
        try {
            return (int) Crypt::decryptString($encryptedId);
        } catch (DecryptException $e) {
            return null;
        }
    }
}
