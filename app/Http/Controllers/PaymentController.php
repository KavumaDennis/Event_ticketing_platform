<?php

namespace App\Http\Controllers;

use App\Models\Event; // ✅ CORRECT
use App\Models\PromoCode;
use App\Services\EventCheckoutPricing;
use App\Services\FxService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Show payment page for selected event, ticket type, and quantity
     */
    public function paymentPage(Request $request, Event $event, FxService $fx)
    {
        $ticketType = strtolower((string) $request->ticket_type);
        $quantity = (int) $request->quantity;

        // Validate inputs
        if (!$ticketType || !$quantity || ! in_array($ticketType, ['regular', 'vip', 'vvip'], true)) {
            return redirect()->back()->with('error', 'Please select ticket type and quantity.');
        }

        $promoInput = (string) $request->query('promo_code', '');
        $quote = EventCheckoutPricing::compute($event, $ticketType, $quantity, $promoInput);
        // Invalid client-supplied promo: treat as warning on page instead of silent ignore
        $promoError = PromoCode::normalizedCode($promoInput) && ! $quote['promo']
            ? 'That promo code is not valid or has expired.'
            : null;

        $baseTotal = $quote['base_total'];
        $serviceFee = $quote['service_fee'];
        $totalBase = $quote['total_base'];
        $grossBaseTotal = $quote['gross_base_total'];
        $discountAmount = $quote['discount_amount'];

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
            'promoInput',
            'promoError',
            'baseTotal',
            'grossBaseTotal',
            'discountAmount',
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
            'promo_code' => 'nullable|string|max:80',
        ]);

        $event = Event::findOrFail($request->event_id);

        return redirect()->route('payment.page', [
            'event' => $event,
            'ticket_type' => $request->ticket_type,
            'quantity' => $request->quantity,
            'currency' => strtoupper((string) $request->currency),
            'promo_code' => $request->promo_code,
        ]);
    }
}
