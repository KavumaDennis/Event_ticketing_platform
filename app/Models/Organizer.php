<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organizer extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        "user_id",
        "business_name",
        'business_email',
        'business_website',
        'organizer_image',
        'tier',
        'default_ticket_price',
        'default_ticket_quantity',
        'default_ticket_type',
        'payout_mobile_money_number',
        'payout_bank_name',
        'payout_account_number',
        'payout_account_name',
        'authorized_emails',
        'contact_email',
        'contact_number',
        'show_logo_in_ticket',
        'description',
        'facebook_url',
        'instagram_url',
        'twitter_url',
        'linkedin_url',
        'ticket_instructions',
        'payout_frequency',
        'tax_id',
        'google_analytics_id',
        'facebook_pixel_id',
        'is_verified',
    ];

    const TIER_FREE = 'free';
    const TIER_PRO = 'pro';
    const TIER_ELITE = 'elite';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function events()
    {
        return $this->hasMany(Event::class, 'organizer_id'); // <-- use organizer_id
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'organizer_followers', 'organizer_id', 'user_id');
    }

    public function members()
    {
        return $this->hasMany(OrganizerMember::class);
    }

    public function hasMember(User $user): bool
    {
        return $this->members()->where('user_id', $user->id)->exists();
    }

    public function hasRole(User $user, array $roles): bool
    {
        return $this->members()
            ->where('user_id', $user->id)
            ->whereIn('role', $roles)
            ->exists();
    }

    public function payoutRequests()
    {
        return $this->hasMany(PayoutRequest::class);
    }

    public function promoCodes()
    {
        return $this->hasMany(PromoCode::class);
    }

    public function getTotalEarnings()
    {
        return TicketPurchase::whereIn('event_id', $this->events->pluck('id'))
            ->where('status', 'paid')
            ->sum('base_total');
    }

    public function getPaidOutAmount()
    {
        return $this->payoutRequests()
            ->whereIn('status', ['completed', 'processing'])
            ->sum('amount');
    }

    public function getAvailableBalance()
    {
        return $this->getTotalEarnings() - $this->getPaidOutAmount();
    }

     protected static function booted()
    {
        static::deleting(function ($organizer) {
            // Delete organizer image
            if ($organizer->organizer_image) {
                Storage::disk('public')->delete($organizer->organizer_image);
            }

            // Delete all events and their images
            foreach ($organizer->events as $event) {
                if ($event->event_image) {
                    Storage::disk('public')->delete($event->event_image);
                }
                $event->delete();
            }
        });
    }

}
