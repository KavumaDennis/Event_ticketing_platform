<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trend extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','event_id', 'title', 'body', 'image'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likes()
    {
        return $this->hasMany(TrendLike::class);
    }

    public function isLikedBy($user)
    {
        $userId = $user instanceof \App\Models\User ? $user->id : $user;
        return $this->likes()->where('user_id', $userId)->exists();
    }

    public function likesCount()
    {
        return $this->likes()->count();
    }

    public function comments()
    {
        return $this->hasMany(TrendComment::class)->latest();
    }

      public function event()
    {
        return $this->belongsTo(Event::class);
    }

}
