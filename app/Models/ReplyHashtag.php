<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReplyHashtag extends Model
{
    use HasFactory;

    protected $fillable = ['reply_id', 'hashtag_id'];

    public function hashtag()
    {
        return $this->belongsTo(Hashtag::class, 'hashtag_id');
    }

    public function replies()
    {
        return $this->belongsTo(Reply::class, 'reply_id');
    }
}
