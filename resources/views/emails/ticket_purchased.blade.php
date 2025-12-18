<!DOCTYPE html>
<html>
<head>
    <title>Ticket Purchased</title>
</head>
<body>
    <h1>Thank you for your purchase!</h1>
    <p>Hi {{ $purchase->user->first_name }},</p>

    <p>You have successfully purchased <strong>{{ $purchase->quantity }} {{ ucfirst($purchase->ticket_type) }} ticket(s)</strong> for the event <strong>{{ $purchase->event->event_name }}</strong>.</p>

    <p>Total Price: UGX {{ number_format($purchase->total_price) }}</p>

    <p>Event Date: {{ $purchase->event->event_date }}</p>
    <p>Event Location: {{ $purchase->event->location }}</p>

    <p>You can download your ticket <a href="{{ route('ticket.download', $purchase->id) }}">here</a>.</p>

    <p>Enjoy the event!</p>
</body>
</html>
