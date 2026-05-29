<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Notification;
use App\Models\PromoCode;
use App\Models\Ticket;
use App\Models\TicketPurchase;
use App\Services\EventCheckoutPricing;
use App\Services\FxService;
use App\Services\MtnService;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class MomoController extends Controller
{
    protected MtnService $mtn;

    public function __construct(MtnService $mtn)
    {
        $this->mtn = $mtn;
    }

    /**
     * Initiate payment request
     */
    public function pay(Request $request, FxService $fx)
    {
        $request->validate([
            'event_id'    => 'required|integer',
            'ticket_type' => 'required|in:regular,vip,vvip',
            'quantity'    => 'required|integer|min:1',
            'total'       => 'nullable|numeric|min:0',
            'promo_code'  => 'nullable|string|max:80',
            'phone'       => 'required|string',
            'currency'    => 'nullable|string|max:5',
        ]);

        $event = Event::findOrFail($request->event_id);

        // Must match MTN callback externalId
        $externalId = 'event-' . $event->id . '-' . Str::upper(Str::random(8));

        $baseCurrency = config('app.currency', 'UGX');
        $chargeCurrency = strtoupper($request->input('currency', $baseCurrency));

        if ($chargeCurrency !== $baseCurrency) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Mobile Money only supports ' . $baseCurrency,
            ], 422);
        }

        $ticketType = strtolower((string) $request->ticket_type);

        $pricing = EventCheckoutPricing::compute(
            $event,
            $ticketType,
            (int) $request->quantity,
            $request->input('promo_code')
        );

        $desiredPromo = PromoCode::normalizedCode($request->input('promo_code'));

        if ($desiredPromo && !$pricing['promo_code_id']) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Invalid or expired promo code.',
            ], 422);
        }

        $calcBaseTotal = $pricing['base_total'];
        $serviceFee = $pricing['service_fee'];
        $totalBase = $pricing['total_base'];

        $fxQuote = $fx->quote(
            (float) $totalBase,
            $baseCurrency,
            $chargeCurrency
        );

        $chargeTotal = $fxQuote['converted'];

        try {
            /**
             * IMPORTANT:
             * Ensure your MtnService sends this exact externalId to MTN.
             */
            $referenceId = $this->mtn->requestPayment(
                $request->phone,
                $chargeTotal,
                $externalId
            );
        } catch (\Throwable $e) {

            Log::error('MTN Payment Request Failed', [
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'status'  => 'error',
                'message' => 'Payment request failed.',
            ], 500);
        }

        if (str_starts_with($referenceId, 'error:')) {
            return response()->json([
                'status'  => 'error',
                'message' => $referenceId,
            ], 400);
        }

        $purchaseData = [
            'user_id'       => auth()->id(),
            'event_id'      => $event->id,
            'ticket_type'   => $ticketType,
            'quantity'      => $request->quantity,
            'base_total'    => $calcBaseTotal,
            'service_fee'   => $serviceFee,
            'total'         => $chargeTotal,
            'currency'      => $chargeCurrency,
            'phone'         => $request->phone,
            'payment_method'=> 'momo',
            'reference_id'  => $referenceId,
            'external_id'   => $externalId,
            'status'        => 'pending',
        ];

        if (Schema::hasColumn('ticket_purchases', 'total_base')) {
            $purchaseData['total_base'] = $totalBase;
        }

        if (Schema::hasColumn('ticket_purchases', 'base_currency')) {
            $purchaseData['base_currency'] = $baseCurrency;
        }

        if (Schema::hasColumn('ticket_purchases', 'fx_rate')) {
            $purchaseData['fx_rate'] = $fxQuote['rate'];
        }

        if (Schema::hasColumn('ticket_purchases', 'fx_source')) {
            $purchaseData['fx_source'] = $fxQuote['provider'];
        }

        if (Schema::hasColumn('ticket_purchases', 'fx_at')) {
            $purchaseData['fx_at'] = $fxQuote['timestamp'];
        }

        if (Schema::hasColumn('ticket_purchases', 'promo_code_id')) {
            $purchaseData['promo_code_id'] = $pricing['promo_code_id'];
            $purchaseData['promo_redeemed'] = false;
        }

        if (Schema::hasColumn('ticket_purchases', 'platform_fee_percent')) {
            $pct = $pricing['platform_fee_percent'];

            $purchaseData['platform_fee_percent'] = $pct === null
                ? null
                : round((float) $pct, 4);
        }

        if (Schema::hasColumn('ticket_purchases', 'tickets_generated')) {
            $purchaseData['tickets_generated'] = false;
        }

        $purchase = TicketPurchase::create($purchaseData);

        return response()->json([
            'status'      => 'success',
            'purchase_id' => $purchase->id,
            'referenceId' => $referenceId,
        ]);
    }

    /**
     * MTN Callback Handler
     */
    public function callback(Request $request)
    {
        Log::info('MoMo Callback Received', [
            'externalId' => $request->input('externalId'),
            'status'     => $request->input('status'),
        ]);

        $externalId = $request->input('externalId');

        if (!$externalId) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Missing externalId',
            ], 400);
        }

        $purchase = TicketPurchase::where('external_id', $externalId)->first();

        if (!$purchase) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Purchase not found',
            ], 404);
        }

        try {

            /**
             * IMPORTANT:
             * Verify payment directly with MTN.
             */
            $mtnStatus = $this->mtn->getPaymentStatus(
                $purchase->reference_id
            );

            $verifiedStatus = strtoupper(
                $mtnStatus['status'] ?? 'FAILED'
            );

            DB::transaction(function () use ($purchase, $verifiedStatus) {

                $lockedPurchase = TicketPurchase::lockForUpdate()
                    ->find($purchase->id);

                if ($lockedPurchase->status !== 'paid') {

                    if ($verifiedStatus === 'SUCCESSFUL') {

                        $lockedPurchase->update([
                            'status'  => 'paid',
                            'paid_at' => now(),
                        ]);

                        PromoCode::redeemForPaidPurchase($lockedPurchase);

                    } elseif ($verifiedStatus === 'FAILED') {

                        $lockedPurchase->update([
                            'status' => 'failed',
                        ]);
                    }
                }

                if (
                    $lockedPurchase->status === 'paid' &&
                    (
                        !Schema::hasColumn('ticket_purchases', 'tickets_generated') ||
                        !$lockedPurchase->tickets_generated
                    )
                ) {

                    $this->generateTickets($lockedPurchase);

                    if (Schema::hasColumn('ticket_purchases', 'tickets_generated')) {

                        $lockedPurchase->update([
                            'tickets_generated' => true,
                        ]);
                    }
                }
            });

        } catch (\Throwable $e) {

            Log::error('MoMo Callback Processing Failed', [
                'message' => $e->getMessage(),
                'purchase_id' => $purchase->id,
            ]);

            return response()->json([
                'status'  => 'error',
                'message' => 'Callback processing failed',
            ], 500);
        }

        return response()->json([
            'status' => 'received',
        ]);
    }

    /**
     * Poll Payment Status
     */
    public function checkPayment(TicketPurchase $purchase)
    {
        try {

            if ($purchase->status === 'pending') {

                $mtnStatus = $this->mtn->getPaymentStatus(
                    $purchase->reference_id
                );

                $remoteStatus = strtoupper(
                    $mtnStatus['status'] ?? 'FAILED'
                );

                DB::transaction(function () use ($purchase, $remoteStatus) {

                    $lockedPurchase = TicketPurchase::lockForUpdate()
                        ->find($purchase->id);

                    if ($lockedPurchase->status !== 'paid') {

                        if ($remoteStatus === 'SUCCESSFUL') {

                            $lockedPurchase->update([
                                'status'  => 'paid',
                                'paid_at' => now(),
                            ]);

                            PromoCode::redeemForPaidPurchase($lockedPurchase);

                        } elseif ($remoteStatus === 'FAILED') {

                            $lockedPurchase->update([
                                'status' => 'failed',
                            ]);
                        }
                    }

                    if (
                        $lockedPurchase->status === 'paid' &&
                        (
                            !Schema::hasColumn('ticket_purchases', 'tickets_generated') ||
                            !$lockedPurchase->tickets_generated
                        )
                    ) {

                        $this->generateTickets($lockedPurchase);

                        if (Schema::hasColumn('ticket_purchases', 'tickets_generated')) {

                            $lockedPurchase->update([
                                'tickets_generated' => true,
                            ]);
                        }
                    }
                });
            }

        } catch (\Throwable $e) {

            Log::error('Payment Polling Failed', [
                'message' => $e->getMessage(),
                'purchase_id' => $purchase->id,
            ]);
        }

        $purchase->refresh();

        if ($purchase->status === 'paid') {

            return response()->json([
                'status'   => 'paid',
                'redirect' => route('ticket.view', $purchase->id) . '?join_group=1',
            ]);
        }

        return response()->json([
            'status' => $purchase->status,
        ]);
    }

    /**
     * Generate Tickets
     */
    protected function generateTickets(TicketPurchase $purchase): void
    {
        $qrFolder = public_path('storage/qrcodes');

        if (!File::exists($qrFolder)) {
            File::makeDirectory($qrFolder, 0755, true);
        }

        for ($i = 0; $i < $purchase->quantity; $i++) {

            $ticketCode = strtoupper(Str::random(12));

            $ticket = Ticket::create([
                'ticket_purchase_id' => $purchase->id,
                'event_id'           => $purchase->event_id,
                'ticket_code'        => $ticketCode,
                'ticket_type'        => $purchase->ticket_type,
                'quantity'           => 1,
            ]);

            $qrPath = "{$qrFolder}/{$ticketCode}.png";

            $qrCode = QrCode::create($ticketCode)
                ->setSize(300)
                ->setMargin(10)
                ->setEncoding(new Encoding('UTF-8'));

            (new PngWriter())
                ->write($qrCode)
                ->saveToFile($qrPath);

            $ticket->update([
                'qr_code_path' => "storage/qrcodes/{$ticketCode}.png",
            ]);

            Log::info('Ticket Created', [
                'ticket_code' => $ticketCode,
            ]);
        }

        // Notify User
        Notification::create([
            'user_id' => $purchase->user_id,
            'title'   => 'Tickets Confirmed!',
            'message' => "Your payment for {$purchase->event->event_name} was successful. Your tickets are ready!",
            'type'    => 'success',
        ]);

        // Notify Organizer
        Notification::create([
            'user_id' => $purchase->event->organizer->user_id,
            'title'   => 'Ticket Sale!',
            'message' => "Someone just purchased {$purchase->quantity} ticket(s) for your event: {$purchase->event->event_name}.",
            'type'    => 'success',
        ]);

        /**
         * Queue notifications instead of blocking request
         */
        \App\Jobs\SendTicketNotifications::dispatch($purchase);
    }
}
