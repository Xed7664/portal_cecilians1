<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hashtag extends Model
{
    use HasFactory;

    protected $fillable = [
        'tag',
    ];

    public function posts()
    {
        return $this->belongsToMany(Post::class, 'post_hashtags', 'hashtag_id', 'post_id');
    }

    public function reposts()
    {
        return $this->belongsToMany(Repost::class, 'repost_hashtags', 'hashtag_id', 'repost_id');
    }

    public function comments()
    {
        return $this->belongsToMany(Comment::class, 'comment_hashtags', 'hashtag_id', 'comment_id');
    }

    public function replies()
    {
        return $this->belongsToMany(Reply::class, 'reply_hashtags', 'hashtag_id', 'reply_id');
    }

}
