<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Referral extends Model
{
    protected $fillable = ['referrer_id', 'referred_id', 'commission_earned'];

    public function referrer()
    {
        return $this->belongsTo(User::class, 'referrer_id');
    }

    public function referred()
    {
        return $this->belongsTo(User::class, 'referred_id');
    }

    public static function processCommission($purchase)
    {
        $referredUser = $purchase->user;
        if (!$referredUser) return;

        $referral = self::where('referred_id', $referredUser->id)->first();
        if ($referral) {
            $commissionRate = config('monetization.referral_commission_rate', 0.05);
            $earned = $purchase->base_total * $commissionRate;

            if ($earned > 0) {
                $referral->increment('commission_earned', $earned);
                $referral->referrer->increment('affiliate_earnings', $earned);

                \App\Models\Notification::create([
                    'user_id' => $referral->referrer_id,
                    'title' => 'Affiliate Commission!',
                    'message' => "You earned " . ($purchase->currency ?? 'UGX') . " " . number_format($earned) . " from a ticket purchase by " . ($referredUser->first_name ?? 'your referral') . ".",
                    'type' => 'success',
                    'notifiable_type' => 'App\Models\User',
                    'notifiable_id' => $referral->referrer_id,
                ]);
            }
        }
    }
}
