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

        $purchase = TicketPurchase::create([
            'user_id'      => auth()->id(),
            'event_id'     => $event->id,
            'ticket_type'  => $request->ticket_type,
            'quantity'     => $request->quantity,
            'total'        => $request->total,
            'currency'     => config('app.currency', 'UGX'),
            'phone'        => $request->phone,
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

                Log::info('Ticket created', ['code' => $ticketCode]);
            }
        }

        return response()->json(['status' => 'received']);
    }

    /**
     * Poll payment status (frontend)
     */
    public function checkPayment(TicketPurchase $purchase)
    {
        if ($purchase->status === 'paid') {
            if ($purchase->tickets()->count() === 0) {
                return response()->json(['status' => 'pending']);
            }

            return response()->json([
                'status'    => 'paid',
                'redirect'  => route('ticket.view', $purchase->id),
            ]);
        }

        return response()->json(['status' => $purchase->status]);
    }
}
