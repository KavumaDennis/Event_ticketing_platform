<?php

namespace App\Http\Controllers;

use App\Models\Event; // ✅ CORRECT
use App\Services\FxService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Show payment page for selected event, ticket type, and quantity
     */
    public function paymentPage(Request $request, Event $event, FxService $fx)
    {
        $ticketType = $request->ticket_type;
        $quantity = $request->quantity;

        // Validate inputs
        if (!$ticketType || !$quantity) {
            return redirect()->back()->with('error', 'Please select ticket type and quantity.');
        }

        $prices = [
            'regular' => $event->regular_price,
            'vip' => $event->vip_price,
            'vvip' => $event->vvip_price,
        ];

        $baseTotal = ($prices[$ticketType] ?? 0) * (int) $quantity;

        // Calculate Service Fee
        $feeConfig = config('monetization.service_fee', ['type' => 'percentage', 'amount' => 5]);
        $serviceFee = ($feeConfig['type'] === 'percentage')
            ? ($baseTotal * $feeConfig['amount'] / 100)
            : $feeConfig['amount'];

        $totalBase = $baseTotal + $serviceFee;

        $baseCurrency = config('app.currency', 'UGX');
        $supportedCurrencies = config('monetization.supported_currencies', [$baseCurrency]);
        $currency = strtoupper($request->query('currency', $baseCurrency));
        if (!in_array($currency, $supportedCurrencies, true)) {
            $currency = $baseCurrency;
        }

        $fxQuote = $fx->quote((float) $totalBase, $baseCurrency, $currency);
        $total = $fxQuote['converted'];
        $fxRate = $fxQuote['rate'];
        $fxProvider = $fxQuote['provider'];

        return view('payment.index', compact(
            'event',
            'ticketType',
            'quantity',
            'baseTotal',
            'serviceFee',
            'total',
            'totalBase',
            'baseCurrency',
            'currency',
            'fxRate',
            'fxProvider',
            'supportedCurrencies'
        ));
    }

    public function fxQuote(Request $request, FxService $fx)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'from' => 'required|string|max:5',
            'to' => 'required|string|max:5',
        ]);

        $quote = $fx->quote((float) $request->amount, $request->from, $request->to);

        return response()->json([
            'rate' => $quote['rate'],
            'converted' => $quote['converted'],
            'provider' => $quote['provider'],
            'timestamp' => $quote['timestamp']->toDateTimeString(),
        ]);
    }

    /**
     * Handles form submission and redirects to MoMo payment
     */
    public function processPayment(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'ticket_type' => 'required|in:regular,vip,vvip',
            'quantity' => 'required|integer|min:1',
            'phone' => 'required|string', // Add phone validation for MoMo
            'currency' => 'nullable|string|max:5',
        ]);

        $event = Event::findOrFail($request->event_id);

        // PRICE LOOKUP
        $prices = [
            'regular' => $event->regular_price,
            'vip' => $event->vip_price,
            'vvip' => $event->vvip_price,
        ];

        $baseTotal = ($prices[$request->ticket_type] ?? 0) * $request->quantity;

        // Recalculate Service Fee for server-side validation/consistency
        $feeConfig = config('monetization.service_fee', ['type' => 'percentage', 'amount' => 5]);
        $serviceFee = ($feeConfig['type'] === 'percentage')
            ? ($baseTotal * $feeConfig['amount'] / 100)
            : $feeConfig['amount'];

        $totalBase = $baseTotal + $serviceFee;
        $baseCurrency = config('app.currency', 'UGX');
        $currency = strtoupper($request->input('currency', $baseCurrency));
        $supportedCurrencies = config('monetization.supported_currencies', [$baseCurrency]);
        if (!in_array($currency, $supportedCurrencies, true)) {
            $currency = $baseCurrency;
        }

        $fxQuote = app(FxService::class)->quote((float) $totalBase, $baseCurrency, $currency);
        $total = $fxQuote['converted'];

        // Redirect to MOMO (MTN/Airtel)
        return redirect()->route('momo.init', [
            'event_id' => $event->id,
            'ticket_type' => $request->ticket_type,
            'quantity' => $request->quantity,
            'total' => $total,
            'phone' => $request->phone,
            'currency' => $currency,
        ]);
    }
}
