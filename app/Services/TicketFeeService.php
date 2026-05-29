<?php

namespace App\Services;

use App\Models\Organizer;

class TicketFeeService
{
    /**
     * Calculate the platform fee for a ticket purchase.
     *
     * @param int|float $amount Total amount of the ticket(s)
     * @param Organizer|null $organizer
     * @return array {total_fee, fixed_fee, percentage_fee}
     */
    public function calculateFee($amount, Organizer $organizer = null)
    {
        $tierRaw = $organizer ? strtolower((string) $organizer->tier) : 'free';
        $config = config("monetization.tiers.{$tierRaw}");

        if (!$config) {
            $config = config('monetization.tiers.free');
        }

        $fixedFee = (float) ($config['fee_fixed'] ?? 0);
        $percentageFee = ($amount * ((float) ($config['fee_percent'] ?? 0) / 100));

        return [
            'total_fee' => $fixedFee + $percentageFee,
            'fixed_fee' => $fixedFee,
            'percentage_fee' => $percentageFee,
            'tier' => $tierRaw,
        ];
    }

    /**
     * Get the net amount for the organizer after fees.
     */
    public function calculateNet($amount, Organizer $organizer = null)
    {
        $fees = $this->calculateFee($amount, $organizer);
        return $amount - $fees['total_fee'];
    }
}
