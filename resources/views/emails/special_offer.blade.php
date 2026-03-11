<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; border-radius: 10px; }
        .header { text-align: center; margin-bottom: 30px; }
        .btn { display: inline-block; padding: 12px 24px; background-color: #fb923c; color: #000; text-decoration: none; border-radius: 8px; font-weight: bold; }
        .footer { margin-top: 40px; font-size: 12px; color: #777; border-top: 1px solid #eee; padding-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="color: #fb923c;">A Special Offer for You!</h1>
        </div>

        <p>Hello {{ $user->first_name }},</p>

        <p>We noticed you liked our upcoming event: <strong>{{ $event->event_name }}</strong>. We're thrilled you're interested!</p>

        <p>To help you make it to the event, we'd like to offer you a special heads-up or perhaps a discount (if applicable). Don't miss out on the experience!</p>

        <div style="text-align: center; margin: 40px 0;">
            <a href="{{ route('event.show', $event->id) }}" class="btn">Get Your Tickets Now</a>
        </div>

        <p>If you have any questions, feel free to reply to this email.</p>

        <p>Best regards,<br>
        <strong>{{ $organizer->business_name }}</strong></p>

        <div class="footer">
            <p>You received this email because you liked an event on our platform.</p>
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
