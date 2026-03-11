<!DOCTYPE html>
<html>
<head>
    <title>Your Ticket for {{ $event->event_name }}</title>
    <style>
        body { font-family: sans-serif; line-height: 1.6; color: #333; }
        .details { background: #f9f9f9; padding: 15px; border-radius: 8px; margin: 20px 0; border: 1px solid #eee; }
        .ticket { border: 1px solid #ddd; padding: 20px; margin-bottom: 20px; border-radius: 8px; text-align: center; }
        .qr-code { margin-top: 15px; }
        .btn { background: #2563eb; color: #fff; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block; margin-top: 10px; }
    </style>
</head>
<body>
    <h1>Thank you for your purchase!</h1>
    <p>Hi {{ $purchase->user ? $purchase->user->first_name : 'Valued Attendee' }},</p>

    <p>You have successfully purchased <strong>{{ $purchase->quantity }} {{ ucfirst($purchase->ticket_type) }} ticket(s)</strong> for the event <strong>{{ $event->event_name }}</strong>.</p>

    <div class="details">
        <h3>Event Details</h3>
        <p><strong>Date & Time:</strong> {{ $event->event_date }} at {{ $event->event_time }}</p>
        <p><strong>Location:</strong> {{ $event->location }}</p>
        <p><strong>Order Reference:</strong> {{ $purchase->reference_id }}</p>
    </div>

    <h3>Your Tickets</h3>
    @foreach($tickets as $ticket)
        <div class="ticket">
            <p><strong>Ticket ID:</strong> {{ $ticket->ticket_code }}</p>
            <p><strong>Type:</strong> {{ ucfirst($ticket->ticket_type) }}</p>
            <div class="qr-code">
                <img src="{{ $message->embed(public_path($ticket->qr_code_path)) }}" alt="QR Code" width="200">
            </div>
        </div>
    @endforeach

    <p>You can also view or download your tickets online:</p>
    <a href="{{ route('ticket.view', $purchase->id) }}" class="btn">View Online Tickets</a>

    <p>Enjoy the event!</p>
</body>
</html>
