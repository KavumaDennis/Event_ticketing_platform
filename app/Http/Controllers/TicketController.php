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
     * View all tickets in a purchase
     */
    public function viewPurchase(\App\Models\TicketPurchase $purchase)
    {
        // Ensure user owns the purchase
        if (auth()->check() && $purchase->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access to purchase.');
        }

        $tickets = $purchase->tickets;
        $event = $purchase->event;

        return view('ticket.index', compact('purchase', 'tickets', 'event'));
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

        // Notify Recipient if they exist in system
        $recipient = \App\Models\User::where('email', $request->recipient_email)->first();
        if ($recipient) {
            \App\Models\Notification::create([
                'user_id' => $recipient->id,
                'title' => 'Ticket Transfer Incoming',
                'message' => auth()->user()->first_name . " is transferring a ticket for {$ticket->event->event_name} to you.",
                'type' => 'info',
            ]);
        }

        return back()->with('success', 'Transfer initiated successfully! An email has been sent to the recipient.');
    }

    public function processTransfer($token)
    {
        $transfer = \App\Models\TicketTransfer::where('token', $token)
            ->where('status', 'pending')
            ->with(['ticket.event', 'sender'])
            ->firstOrFail();

        return view('tickets.transfer-confirm', compact('transfer'));
    }

    public function confirmTransfer($token)
    {
        $transfer = \App\Models\TicketTransfer::where('token', $token)
            ->where('status', 'pending')
            ->firstOrFail();

        $user = auth()->user();

        // Update Ticket Ownership
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
        if ($originalPurchase && $originalPurchase->quantity > 1) {
            $originalPurchase->decrement('quantity');
        }

        $transfer->ticket->update([
            'ticket_purchase_id' => $newPurchase->id,
        ]);

        $transfer->update(['status' => 'accepted']);

        // Notify Sender
        \App\Models\Notification::create([
            'user_id' => $transfer->sender_id,
            'title' => 'Transfer Accepted!',
            'message' => "{$user->first_name} has accepted your ticket transfer for {$transfer->ticket->event->event_name}.",
            'type' => 'success',
        ]);

        // Notify Recipient (Personal confirmation)
        \App\Models\Notification::create([
            'user_id' => $user->id,
            'title' => 'Ticket Claimed',
            'message' => "You have successfully claimed the ticket for {$transfer->ticket->event->event_name}. You can find it in your dashboard.",
            'type' => 'success',
        ]);

        return redirect()->route('user.dashboard.tickets')->with('success', 'Ticket added to your dashboard successfully!');
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
