<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use App\Models\TicketPurchase;



class User extends Authenticatable
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
        ];
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



}
