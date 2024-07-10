<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RepostHashtag extends Model
{
    use HasFactory;

    protected $fillable = [
        'repost_id',
        'hashtag_id',
    ];

    // Define relationships
    public function repost()
    {
        return $this->belongsTo(Repost::class, 'repost_id');
    }

    public function hashtag()
    {
        return $this->belongsTo(Hashtag::class, 'hashtag_id');
    }
}
