<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\TicketPurchase;
use App\Models\Ticket;
use App\Services\FlutterwaveService;
use App\Services\FxService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;

class FlutterwaveController extends Controller
{
    protected $flutterwave;

    public function __construct(FlutterwaveService $flutterwave)
    {
        $this->flutterwave = $flutterwave;
    }

    public function initialize(Request $request, FxService $fx)
    {
        $request->validate([
            'event_id' => 'required|integer',
            'ticket_type' => 'required|string',
            'quantity' => 'required|integer|min:1',
            'total' => 'required|numeric|min:1',
            'currency' => 'nullable|string|max:5',
            'email' => 'required|email',
            'phone' => 'nullable|string',
            'name' => 'required|string',
        ]);

        $event = Event::findOrFail($request->event_id);
        $txRef = 'fly-' . $event->id . '-' . Str::random(10);

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

        $baseCurrency = config('app.currency', 'UGX');
        $supportedCurrencies = config('monetization.supported_currencies', [$baseCurrency]);
        $chargeCurrency = strtoupper($request->input('currency', $baseCurrency));
        if (!in_array($chargeCurrency, $supportedCurrencies, true)) {
            $chargeCurrency = $baseCurrency;
        }

        $totalBase = $calcBaseTotal + $serviceFee;
        $fxQuote = $fx->quote((float) $totalBase, $baseCurrency, $chargeCurrency);
        $chargeTotal = $fxQuote['converted'];

        $purchaseData = [
            'user_id'        => auth()->id(),
            'event_id'       => $event->id,
            'ticket_type'    => $request->ticket_type,
            'quantity'       => $request->quantity,
            'base_total'     => $calcBaseTotal,
            'service_fee'    => $serviceFee,
            'total'          => $chargeTotal,
            'currency'       => $chargeCurrency,
            'phone'          => $request->phone,
            'payment_method' => 'flutterwave',
            'external_id'    => $txRef, // tx_ref
            'status'         => 'pending',
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

        $purchase = TicketPurchase::create($purchaseData);

        $paymentData = [
            'tx_ref'       => $txRef,
            'amount'       => $chargeTotal,
            'currency'     => $chargeCurrency,
            'redirect_url' => route('flutterwave.callback'),
            'email'        => $request->email,
            'phone'        => $request->phone,
            'name'         => $request->name,
            'description'  => "Payment for " . $event->event_name . " tickets",
        ];

        $payment = $this->flutterwave->initializePayment($paymentData);

        if (isset($payment['status']) && $payment['status'] === 'success') {
            return response()->json([
                'status' => 'success',
                'link'   => $payment['data']['link'],
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Failed to initialize payment with Flutterwave',
        ], 500);
    }

    public function callback(Request $request)
    {
        $status = $request->status;
        $txRef = $request->tx_ref;
        $transactionId = $request->transaction_id;

        if ($status === 'successful' || $status === 'completed') {
            $verification = $this->flutterwave->verifyTransaction($transactionId);

            if ($verification && isset($verification['status']) && $verification['status'] === 'success' && $verification['data']['status'] === 'successful') {
                $purchase = TicketPurchase::where('external_id', $txRef)->first();

                if ($purchase) {
                    if ($purchase->status !== 'paid') {
                        $purchase->update([
                            'status'  => 'paid',
                            'flw_ref' => $transactionId,
                            'paid_at' => now(),
                        ]);

                        $this->generateTickets($purchase);
                    }

                    return redirect()->route('ticket.view', $purchase->id)->with('success', 'Payment successful!');
                }
            }
        }

        return redirect()->route('home')->with('error', 'Payment failed or was cancelled.');
    }

    protected function generateTickets($purchase)
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
            $qrCode = QrCode::create($ticketCode)
                ->setSize(300)
                ->setMargin(10)
                ->setEncoding(new Encoding('UTF-8'));

            (new PngWriter())
                ->write($qrCode)
                ->saveToFile($qrPath);

            $ticket->update(['qr_code_path' => "storage/qrcodes/{$ticketCode}.png"]);
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
