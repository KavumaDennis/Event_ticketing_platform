<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trend extends Model
{
    use HasFactory;

    protected $with = ['media'];

    protected $fillable = [
        'user_id',
        'event_id',
        'title',
        'body',
        'image',
        'is_sponsored',
        'boost_level',
        'boost_expires_at',
        'location',
        'interest_tags',
        'is_editors_pick',
    ];

    protected $casts = [
        'interest_tags' => 'array',
        'boost_expires_at' => 'datetime',
        'is_sponsored' => 'boolean',
    ];

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

    public function media()
    {
        return $this->hasMany(TrendMedia::class)->orderBy('order');
    }

    /**
     * Get the first media URL (new table or old image column).
     */
    public function getFirstMediaUrlAttribute()
    {
        if ($this->media->isNotEmpty()) {
            return asset('storage/' . $this->media->first()->path);
        }
        return $this->image ? asset('storage/' . $this->image) : asset('default.png');
    }

    /**
     * Get the first media type (image or video).
     */
    public function getFirstMediaTypeAttribute()
    {
        if ($this->media->isNotEmpty()) {
            return $this->media->first()->type;
        }
        return $this->is_video ? 'video' : 'image';
    }

    /**
     * Check if the trend media is a video (backward compatibility or first media).
     */
    public function getIsVideoAttribute()
    {
        if ($this->media->isNotEmpty()) {
            return $this->media->first()->type === 'video';
        }

        if (empty($this->image)) {
            return false;
        }

        $extension = strtolower(pathinfo($this->image, PATHINFO_EXTENSION));
        return in_array($extension, ['mp4', 'mov', 'avi', 'wmv', 'flv', 'webm']);
    }

    /* ===================== SCOPES ===================== */

    public function scopeEditorsPick($query)
    {
        return $query->where('is_editors_pick', true);
    }

    public function scopeSponsored($query)
    {
        return $query->where('is_sponsored', true);
    }

    public function scopeActiveBoost($query)
    {
        return $query->where('boost_level', '>', 0)
                     ->where(function($q) {
                         $q->whereNull('boost_expires_at')
                           ->orWhere('boost_expires_at', '>', now());
                     });
    }

    public function scopeTrendingNear($query, $location)
    {
        return $query->where('location', 'LIKE', "%$location%");
    }

}
