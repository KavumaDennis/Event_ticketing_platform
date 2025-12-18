<?php

namespace App\Http\Controllers;

use App\Models\Event; // ✅ CORRECT
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Show payment page for selected event, ticket type, and quantity
     */
    public function paymentPage(Request $request, Event $event)
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

        $total = ($prices[$ticketType] ?? 0) * (int) $quantity;

        return view('payment.index', compact(
            'event',
            'ticketType',
            'quantity',
            'total'
        ));
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
        ]);

        $event = Event::findOrFail($request->event_id);

        // PRICE LOOKUP
        $prices = [
            'regular' => $event->regular_price,
            'vip' => $event->vip_price,
            'vvip' => $event->vvip_price,
        ];

        $total = ($prices[$request->ticket_type] ?? 0) * $request->quantity;

        // Redirect to MOMO (MTN/Airtel)
        return redirect()->route('momo.init', [
            'event_id' => $event->id,
            'ticket_type' => $request->ticket_type,
            'quantity' => $request->quantity,
            'total' => $total,
            'phone' => $request->phone,
        ]);
    }
}
