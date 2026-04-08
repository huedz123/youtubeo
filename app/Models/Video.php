<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $fillable = [
        'user_id', 'title', 'description', 'video_path', 'thumbnail', 'category' 
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function comments() {
        return $this->hasMany(Comment::class)->latest();
    }
    public function votes()
{
    return $this->hasMany(VideoUserVote::class);
}

public function likes()
{
    return $this->votes()->where('type','like');
}

public function dislikes()
{
    return $this->votes()->where('type','dislike');
}
}