<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PromoCode extends Model
{
    protected $fillable = [
        'code',
        'discount_type',
        'discount_amount',
        'event_id',
        'organizer_id',
        'usage_limit',
        'used_count',
        'expires_at',
        'status',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'status' => 'boolean',
    ];

    public function organizer()
    {
        return $this->belongsTo(Organizer::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public static function normalizedCode(?string $code): ?string
    {
        if (!$code || ! is_string($code)) {
            return null;
        }
        $c = strtoupper(trim($code));

        return $c === '' ? null : $c;
    }

    /**
     * Valid promo for this event/checkout (expires, limits, organizer, optional event restriction).
     */
    public static function findValidForEventCheckout(Event $event, ?string $rawCode): ?self
    {
        $code = self::normalizedCode($rawCode);
        if (! $code) {
            return null;
        }

        // Match code case-insensitively (some DBs/collations are case-sensitive)
        // Prefer event-specific codes when both exist.
        $promo = self::query()
            ->whereRaw('UPPER(code) = ?', [$code])
            ->where(function ($q) use ($event) {
                $q->where('organizer_id', $event->organizer_id)
                    ->orWhere('event_id', $event->id);
            })
            ->orderByRaw('CASE WHEN event_id IS NULL THEN 1 ELSE 0 END')
            ->first();
        if (! $promo || ! $promo->isApplicableToEvent($event) || ! $promo->isValid()) {
            return null;
        }

        return $promo;
    }

    public function isApplicableToEvent(Event $event): bool
    {
        if ($this->organizer_id !== $event->organizer_id) {
            return false;
        }
        if ($this->event_id && (int) $this->event_id !== (int) $event->id) {
            return false;
        }

        return true;
    }

    public function isValid()
    {
        if (! $this->status) {
            return false;
        }
        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }
        if ($this->usage_limit && $this->used_count >= $this->usage_limit) {
            return false;
        }

        return true;
    }

    /**
     * Call once when the purchase transitions to paid the first time.
     */
    public static function redeemForPaidPurchase(?TicketPurchase $purchase): void
    {
        if (! $purchase?->promo_code_id) {
            return;
        }

        $promoId = $purchase->promo_code_id;

        $marked = TicketPurchase::query()
            ->whereKey($purchase->id)
            ->whereNotNull('promo_code_id')
            ->where('promo_redeemed', false)
            ->update(['promo_redeemed' => true]);

        if ($marked === 1) {
            DB::table('promo_codes')->where('id', $promoId)->increment('used_count');
        }
    }
}
