<!DOCTYPE html>
<html>
<head>
    <title>Ticket Gift</title>
</head>
<body style="font-family: sans-serif; background-color: #000; color: #fff; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #09090b; border: 1px solid #27272a; padding: 40px; border-radius: 20px; text-align: center;">
        <div style="margin-bottom: 30px;">
            <span style="font-size: 24px; font-weight: bold; color: #4ade80;">TICKET GIFT!</span>
        </div>

        <p style="font-size: 18px; margin-bottom: 20px;">
            Hello! <strong>{{ $transfer->sender->first_name }}</strong> has sent you a ticket for:
        </p>

        <div style="background-color: #18181b; padding: 30px; border-radius: 15px; border: 1px solid #27272a; margin-bottom: 30px;">
            <h2 style="font-size: 20px; font-weight: bold; margin-bottom: 10px;">{{ $transfer->ticket->event->event_name }}</h2>
            <p style="color: #a1a1aa; font-size: 14px;">
                {{ \Carbon\Carbon::parse($transfer->ticket->event->event_date)->format('l, F j, Y') }}<br>
                {{ $transfer->ticket->event->location }}
            </p>
        </div>

        <p style="color: #a1a1aa; margin-bottom: 30px;">
            To claim this ticket and add it to your dashboard, click the button below:
        </p>

        <a href="{{ route('ticket.transfer.accept', $transfer->token) }}" 
           style="display: inline-block; padding: 15px 30px; background-color: #22c55e; color: #000; font-weight: bold; text-decoration: none; border-radius: 10px;">
            Accept Ticket
        </a>

        <p style="margin-top: 40px; color: #71717a; font-size: 12px;">
            If you don't have an account, you will be prompted to create one to accept this gift.
        </p>
    </div>
</body>
</html>
