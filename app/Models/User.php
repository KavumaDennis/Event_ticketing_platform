<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use App\Models\TicketPurchase;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'username',
        'is_admin',
        'google_id',
        'avatar',
        'referral_code',
        'affiliate_earnings',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'affiliate_earnings' => 'decimal:2',
        ];
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($user) {
            if (!$user->referral_code) {
                $user->referral_code = strtoupper(substr(bin2hex(random_bytes(4)), 0, 8));
            }
        });
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function savedEvents()
    {
        return $this->hasMany(SavedEvent::class);
    }

    public function followedOrganizers()
    {
        return $this->belongsToMany(Organizer::class, 'organizer_followers', 'user_id', 'organizer_id');
    }

    public function trends()
    {
        return $this->hasMany(Trend::class);
    }

    // Users who follow this user
    public function followers()
    {
        return $this->hasMany(UserFollow::class, 'following_id');
    }

    // Users this user is following
    public function following()
    {
        return $this->hasMany(UserFollow::class, 'follower_id');
    }

    // Check if current user is following another user
    public function isFollowing($userId)
    {
        return $this->following()->where('following_id', $userId)->exists();
    }

    // Optional accessor for followers count
    public function getFollowersCountAttribute()
    {
        return $this->followers()->count();
    }

    // Optional accessor for following count
    public function getFollowingCountAttribute()
    {
        return $this->following()->count();
    }


    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function organizer()
    {
        return $this->hasOne(Organizer::class);
    }

    public function ticketPurchases()
    {
        return $this->hasMany(TicketPurchase::class);
    }

    public function tickets()
    {
        return $this->hasManyThrough(
            Ticket::class,
            TicketPurchase::class
        );
    }

    public function ticketTransfers()
    {
        return $this->hasMany(TicketTransfer::class, 'sender_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function unreadNotificationsCount()
    {
        return $this->notifications()->whereNull('read_at')->count();
    }

    public function referrals()
    {
        return $this->hasMany(Referral::class, 'referrer_id');
    }

    public function referredBy()
    {
        return $this->hasOne(Referral::class, 'referred_id');
    }

    public function experiences()
    {
        return $this->hasMany(Experience::class);
    }

    public function activeExperiences()
    {
        return $this->experiences()->where('expires_at', '>', now());
    }

    public function getHasActiveExperienceAttribute()
    {
        return $this->activeExperiences()->exists();
    }

    public function getProfilePhotoUrlAttribute()
    {
        if ($this->profile_pic) {
            return asset('storage/' . $this->profile_pic);
        }

        if ($this->avatar) {
            return $this->avatar;
        }

        return asset('default.png');
    }
}
