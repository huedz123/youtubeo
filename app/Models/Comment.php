<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'user_id',
        'video_id',
        'content'
    ];

    // ⚡ Quan hệ với video
    public function video()
    {
        return $this->belongsTo(Video::class);
    }

    // ⚡ Quan hệ với user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}