<?php

namespace App\Services;

use App\Models\Event;
use App\Models\PromoCode;

class EventCheckoutPricing
{
    /**
     * @return array{
     *   promo: PromoCode|null,
     *   promo_code_id: int|null,
     *   gross_base_total: float,
     *   discount_amount: float,
     *   base_total: float,
     *   service_fee: float,
     *   platform_fee_percent: float|null,
     *   total_base: float,
     * }
     */
    public static function compute(Event $event, string $ticketType, int $quantity, ?string $promoCodeRaw): array
    {
        $quantity = max(1, $quantity);

        $prices = [
            'regular' => (float) ($event->regular_price ?? 0),
            'vip' => (float) ($event->vip_price ?? 0),
            'vvip' => (float) ($event->vvip_price ?? 0),
        ];

        $unitPrice = $prices[$ticketType] ?? 0;
        $grossBase = round($unitPrice * $quantity, 2);

        $promo = PromoCode::findValidForEventCheckout($event, $promoCodeRaw);
        $discount = 0.0;

        if ($promo) {
            if ($promo->discount_type === 'percentage') {
                $discount = round($grossBase * ((float) $promo->discount_amount) / 100, 2);
            } else {
                $discount = round(min($grossBase, (float) $promo->discount_amount), 2);
            }
        }

        $baseAfterDiscount = round(max(0, $grossBase - $discount), 2);

        $event->loadMissing('organizer');
        $tierKey = strtolower((string) ($event->organizer->tier ?? 'free'));
        $tierCfg = config("monetization.tiers.$tierKey", config('monetization.tiers.free'));

        $feeFixed = isset($tierCfg['fee_fixed']) ? (float) $tierCfg['fee_fixed'] : 0;
        $feePercent = isset($tierCfg['fee_percent']) ? (float) $tierCfg['fee_percent'] : null;

        if ($feePercent === null) {
            $global = config('monetization.service_fee', ['type' => 'percentage', 'amount' => 5]);
            $serviceFee = ($global['type'] === 'percentage')
                ? round($baseAfterDiscount * ((float) $global['amount'] / 100), 2)
                : round((float) $global['amount'], 2);
            $platformFeePercent = $global['type'] === 'percentage' ? (float) $global['amount'] : null;
        } else {
            $serviceFee = round($feeFixed + $baseAfterDiscount * ($feePercent / 100), 2);
            $platformFeePercent = $feePercent;
        }

        $totalBase = round($baseAfterDiscount + $serviceFee, 2);

        return [
            'promo' => $promo,
            'promo_code_id' => $promo?->id,
            'gross_base_total' => $grossBase,
            'discount_amount' => $discount,
            'base_total' => $baseAfterDiscount,
            'service_fee' => $serviceFee,
            'platform_fee_percent' => $platformFeePercent,
            'total_base' => $totalBase,
        ];
    }
}
