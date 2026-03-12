<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventCommentLike extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_comment_id',
        'user_id',
    ];

    public function comment()
    {
        return $this->belongsTo(EventComment::class, 'event_comment_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
