<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentHashtag extends Model
{
    use HasFactory;

    protected $fillable = ['comment_id', 'hashtag_id'];

    public function hashtag()
    {
        return $this->belongsTo(Hashtag::class, 'hashtag_id');
    }

    public function comment()
    {
        return $this->belongsTo(Comment::class, 'comment_id');
    }
}
