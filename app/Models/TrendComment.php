<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrendComment extends Model
{
    protected $fillable = ['trend_id', 'user_id', 'comment'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function trend()
    {
        return $this->belongsTo(Trend::class);
    }

    // Polymorphic likes
    public function likes()
    {
        return $this->morphMany(TrendCommentLike::class, 'likeable');
    }

    public function isLikedBy(User $user)
    {
        return $this->likes()->where('user_id', $user->id)->exists();
    }
}



