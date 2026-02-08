<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class TicketController extends Controller
{
    /**
     * Display a ticket in the browser
     */
    public function show($code)
    {
        $ticket = Ticket::where('ticket_code', $code)->firstOrFail();

        // Optional: ensure logged-in user owns the ticket
        if (auth()->check() && $ticket->purchase->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access to ticket.');
        }

        $event = $ticket->event ?? null;

        return view('tickets.show', compact('ticket', 'event'));
    }

    /**
     * Download ticket as PDF
     */
    public function download($code)
    {
        $ticket = Ticket::where('ticket_code', $code)->firstOrFail();

        // Optional: ensure logged-in user owns the ticket
        if (auth()->check() && $ticket->purchase->user_id !== auth()->id()) {
            abort(403, 'Unauthorized download attempt.');
        }

        $event = $ticket->event ?? null;

        $pdf = Pdf::loadView('tickets.pdf', compact('ticket', 'event'))
                ->setPaper('A5', 'portrait');

        return $pdf->download("ticket-{$ticket->ticket_code}.pdf");
    }
    public function transfer(Request $request, Ticket $ticket)
    {
        $request->validate([
            'recipient_email' => 'required|email',
        ]);

        if ($ticket->purchase->user_id !== auth()->id()) {
            abort(403);
        }

        // Cancel any existing pending transfers for this ticket
        $ticket->transfers()->where('status', 'pending')->update(['status' => 'cancelled']);

        $transfer = \App\Models\TicketTransfer::create([
            'ticket_id' => $ticket->id,
            'sender_id' => auth()->id(),
            'recipient_email' => $request->recipient_email,
            'status' => 'pending',
            'token' => \Illuminate\Support\Str::random(40),
        ]);

        // Send Email (Mailable implementation needed)
        \Illuminate\Support\Facades\Mail::to($request->recipient_email)->send(new \App\Mail\TicketTransferMail($transfer));

        return back()->with('success', 'Transfer initiated successfully! An email has been sent to the recipient.');
    }

    public function processTransfer($token)
    {
        $transfer = \App\Models\TicketTransfer::where('token', $token)->where('status', 'pending')->firstOrFail();

        // Ensure user is logged in
        if (!auth()->check()) {
            return redirect()->route('login')->with('info', 'Please login to accept the ticket transfer.');
        }

        $user = auth()->user();

        // If user email doesn't match recipient email (optional check, sometimes people use different emails)
        // if ($user->email !== $transfer->recipient_email) { ... }

        // Update Ticket Ownership
        // Since tickets are linked to purchases, we either move the ticket or update the purchase.
        // Usually, a transferred ticket should become a new purchase record or change the purchase owner.
        // However, a purchase can have multiple tickets. If we transfer one, we should probably detach it.
        
        // Simpler implementation: Just update the purchase user_id IF there's only one ticket,
        // OR create a new "dummy" purchase record for the recipient to represent this ticket.

        $newPurchase = \App\Models\TicketPurchase::create([
            'user_id' => $user->id,
            'event_id' => $transfer->ticket->event_id,
            'ticket_type' => $transfer->ticket->ticket_type,
            'quantity' => 1,
            'total' => 0, // Gifted
            'status' => 'paid',
            'paid_at' => now(),
            'reference_id' => 'TRANSFER-' . $transfer->token,
        ]);

        // Decrement original purchase quantity if it's more than 1
        $originalPurchase = $transfer->ticket->purchase;
        if ($originalPurchase && $originalPurchase->quantity > 0) {
            $originalPurchase->decrement('quantity');
        }

        $transfer->ticket->update([
            'ticket_purchase_id' => $newPurchase->id,
        ]);

        $transfer->update(['status' => 'accepted']);

        return redirect()->route('user.dashboard.tickets')->with('success', 'Ticket accepted successfully! You can now view it in your dashboard.');
    }

    public function cancelTransfer(\App\Models\TicketTransfer $transfer)
    {
        if ($transfer->sender_id !== auth()->id()) {
            abort(403);
        }

        if ($transfer->status === 'pending') {
            $transfer->update(['status' => 'cancelled']);
            return back()->with('success', 'Transfer cancelled.');
        }

        return back()->with('error', 'Only pending transfers can be cancelled.');
    }
}
