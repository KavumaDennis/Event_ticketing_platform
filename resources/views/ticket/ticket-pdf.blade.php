<html>
    <body style="font-family: sans-serif; background: #0d0f16; color: #fff; padding: 30px;">

        <div style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16,185,129,0.2); padding: 20px; border-radius: 20px; max-width: 400px; margin: auto;">

            <h2 style="color: #ddd; text-align:center;">Event Ticket</h2>

            <p><strong>Event:</strong> {{ $ticket->event->event_name }}</p>
            <p><strong>Ticket Type:</strong> {{ ucfirst($ticket->ticket_type) }}</p>
            <p><strong>Ticket Code:</strong> {{ $ticket->ticket_code }}</p>

            <div style="text-align:center; margin-top:20px;">
                <img src="{{ public_path('storage/qrcodes/' . $ticket->ticket_code . '.png') }}"
                     style="width:180px; height:180px; border-radius: 20px; border: 1px solid orange;">
            </div>
        </div>

    </body>
</html>
