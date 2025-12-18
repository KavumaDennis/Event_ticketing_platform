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
        if (auth()->check() && $ticket->user_id !== auth()->id()) {
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
        if (auth()->check() && $ticket->user_id !== auth()->id()) {
            abort(403, 'Unauthorized download attempt.');
        }

        $event = $ticket->event ?? null;

        $pdf = Pdf::loadView('tickets.pdf', compact('ticket', 'event'))
                ->setPaper('A5', 'portrait');

        return $pdf->download("ticket-{$ticket->ticket_code}.pdf");
    }
}
