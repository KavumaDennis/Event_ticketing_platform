<?php

namespace App\Http\Controllers;

use App\Services\MtnService;
use App\Models\Event;
use App\Models\TicketPurchase;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;

class MomoController extends Controller
{
    protected $mtn;

    public function __construct(MtnService $mtn)
    {
        $this->mtn = $mtn;
    }

    /**
     * Initiate payment request
     */
    public function pay(Request $request)
    {
        $request->validate([
            'event_id' => 'required|integer',
            'ticket_type' => 'required|string',
            'quantity' => 'required|integer|min:1',
            'total' => 'required|numeric|min:1',
            'phone' => 'required|string',
        ]);

        $event = Event::findOrFail($request->event_id);

        // This MUST match MTN callback externalId
        $externalId = 'event-' . $event->id . '-' . Str::random(6);

        try {
            $referenceId = $this->mtn->requestPayment(
                $request->phone,
                $request->total,
                $externalId
            );
        } catch (\Exception $e) {
            Log::error('MTN Payment Error', ['error' => $e->getMessage()]);

            return response()->json([
                'status' => 'error',
                'message' => 'Payment request failed',
            ], 500);
        }

        if (str_starts_with($referenceId, 'error:')) {
            return response()->json([
                'status' => 'error',
                'message' => $referenceId,
            ], 400);
        }

        $baseTotal = ($event->regular_price ?? 0); // Default or lookup based on type
        // Actually, it's safer to re-calculate based on event prices
        $prices = [
            'regular' => $event->regular_price,
            'vip' => $event->vip_price,
            'vvip' => $event->vvip_price,
        ];
        $calcBaseTotal = ($prices[$request->ticket_type] ?? 0) * $request->quantity;
        
        $feeConfig = config('monetization.service_fee', ['type' => 'percentage', 'amount' => 5]);
        $serviceFee = ($feeConfig['type'] === 'percentage') 
            ? ($calcBaseTotal * $feeConfig['amount'] / 100) 
            : $feeConfig['amount'];

        $purchase = TicketPurchase::create([
            'user_id'      => auth()->id(),
            'event_id'     => $event->id,
            'ticket_type'  => $request->ticket_type,
            'quantity'     => $request->quantity,
            'base_total'   => $calcBaseTotal,
            'service_fee'  => $serviceFee,
            'total'        => $request->total, // Validate this matches calcBaseTotal + serviceFee if needed
            'currency'     => config('app.currency', 'UGX'),
            'phone'        => $request->phone,
            'payment_method' => 'momo',
            'reference_id' => $referenceId,
            'external_id'  => $externalId,
            'status'       => 'pending',
        ]);

        

        return response()->json([
            'status'      => 'success',
            'purchase_id' => $purchase->id,
            'referenceId' => $referenceId,
        ]);
    }

    /**
     * MTN callback handler
     */
    public function callback(Request $request)
    {
        Log::info('MoMo Callback', $request->all());

        // MTN sends externalId, NOT referenceId
        $externalId = $request->input('externalId');
        $status     = strtoupper($request->input('status', 'FAILED'));

        if (!$externalId) {
            return response()->json(['status' => 'error', 'message' => 'Missing externalId']);
        }

        $purchase = TicketPurchase::where('external_id', $externalId)->first();

        if (!$purchase) {
            return response()->json(['status' => 'error', 'message' => 'Purchase not found']);
        }

        // Only update once
        if ($purchase->status !== 'paid') {
            if ($status === 'SUCCESSFUL') {
                $purchase->update([
                    'status'  => 'paid',
                    'paid_at' => now(),
                ]);
            } else {
                $purchase->update(['status' => 'failed']);
            }
        }

        // Generate tickets if paid & not generated
        if ($purchase->status === 'paid' && $purchase->tickets()->count() === 0) {
            $this->generateTickets($purchase);
        }

        return response()->json(['status' => 'received']);
    }

    /**
     * Poll payment status (frontend)
     */
    public function checkPayment(TicketPurchase $purchase)
    {
        // If still pending, actively check status from MTN
        if ($purchase->status === 'pending') {
            try {
                $mtnStatus = $this->mtn->getPaymentStatus($purchase->reference_id);

                if (isset($mtnStatus['status'])) {
                    $remoteStatus = strtoupper($mtnStatus['status']);

                    if ($remoteStatus === 'SUCCESSFUL') {
                        $purchase->update([
                            'status'  => 'paid',
                            'paid_at' => now(),
                        ]);
                    } elseif ($remoteStatus === 'FAILED') {
                        $purchase->update(['status' => 'failed']);
                    }
                }
            } catch (\Exception $e) {
                // Log error if needed
            }
        }

        // Re-fetch to get updated status
        $purchase->refresh();

        if ($purchase->status === 'paid') {
            // Generate tickets if paid & not generated
            if ($purchase->tickets()->count() === 0) {
                $this->generateTickets($purchase);
            }
            
            return response()->json([
                'status'    => 'paid',
                'redirect'  => route('ticket.view', $purchase->id),
            ]);
        }

        return response()->json(['status' => $purchase->status]);
    }

    protected function generateTickets(TicketPurchase $purchase)
    {
        $qrFolder = public_path('storage/qrcodes');
        if (!file_exists($qrFolder)) {
            mkdir($qrFolder, 0777, true);
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
            $qrCode = QrCode::create($ticketCode)->setSize(300)->setMargin(10)->setEncoding(new Encoding('UTF-8'));
            (new PngWriter())->write($qrCode)->saveToFile($qrPath);

            $ticket->update(['qr_code_path' => "storage/qrcodes/{$ticketCode}.png"]);
            Log::info('Ticket created', ['code' => $ticketCode]);
        }

        // Notify User
        \App\Models\Notification::create([
            'user_id' => $purchase->user_id,
            'title' => 'Tickets Confirmed!',
            'message' => "Your payment for {$purchase->event->event_name} was successful. Your tickets are ready!",
            'type' => 'success',
        ]);

        // Notify Organizer
        \App\Models\Notification::create([
            'user_id' => $purchase->event->organizer->user_id,
            'title' => 'Ticket Sale!',
            'message' => "Someone just purchased {$purchase->quantity} ticket(s) for your event: {$purchase->event->event_name}.",
            'type' => 'success',
        ]);

        \App\Models\Referral::processCommission($purchase);

        \App\Jobs\SendTicketNotifications::dispatch($purchase);
    }
}
