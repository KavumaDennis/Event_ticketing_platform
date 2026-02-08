<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Ticket - {{ $event->event_name }}</title>
    <style>
        @page { margin: 0; }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            background-color: #09090b; /* zinc-950 */
            color: #ffffff;
            margin: 0;
            padding: 0;
            width: 100%;
           
        }
        .container {
            width: fit-content;
            height: fit-content;
            padding: 20px;
            box-sizing: border-box;
        }
        .ticket-card {
            border: 1px solid #27272a; /* zinc-800 */
            border-radius: 10px;
            background-color: #18181b; /* zinc-900 */
            padding: 30px;
            position: relative;
        }
        .header {
            border-bottom: 2px solid #fb923c; /* orange-400 */
            padding-bottom: 20px;
            margin-bottom: 20px;
            display: table;
            width: 100%;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #4ade80; /* green-400 */
            display: table-cell;
            vertical-align: middle;
        }
        .order-id {
            text-align: right;
            color: #a1a1aa; /* zinc-400 */
            font-size: 12px;
            display: table-cell;
            vertical-align: middle;
        }
        .event-title {
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #ffffff;
        }
        .event-details {
            margin-bottom: 30px;
            color: #d4d4d8; /* zinc-300 */
            font-size: 14px;
        }
        .info-grid {
            width: 100%;
            margin-bottom: 30px;
        }
        .info-cell {
            padding-bottom: 15px;
            vertical-align: top;
        }
        .label {
            font-size: 10px;
            text-transform: uppercase;
            color: #fb923c; /* orange-400 */
            letter-spacing: 1px;
            margin-bottom: 5px;
        }
        .value {
            font-size: 16px;
            font-weight: bold;
            color: #ffffff;
        }
        .qr-section {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px dashed #3f3f46; /* zinc-700 */
        }
        .qr-img {
            width: 200px;
            height: 200px;
            background-color: white;
            padding: 10px;
            border-radius: 10px;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #52525b; /* zinc-600 */
        }
        .status-badge {
            background-color: rgba(74, 222, 128, 0.1);
            color: #4ade80;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 12px;
            display: inline-block;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="ticket-card">
            <div class="header">
                <div class="logo">TICKET APP</div>
                <div class="order-id">
                    REF: {{ $ticket->purchase->reference_id ?? 'N/A' }}<br>
                    DATE: {{ $ticket->created_at->format('M d, Y') }}
                </div>
            </div>

            <div class="event-title">{{ $event->event_name }}</div>
            <div class="event-details">
                {{ $event->location }} &bull; {{ \Carbon\Carbon::parse($event->event_date)->format('l, F j, Y @ g:i A') }}
            </div>

            <table class="info-grid">
                <tr>
                    <td class="info-cell" width="50%">
                        <div class="label">Attendee</div>
                        <div class="value">{{ $ticket->purchase->user->first_name }} {{ $ticket->purchase->user->last_name }}</div>
                        @php $transfer = $ticket->latestAcceptedTransfer; @endphp
                        @if($transfer)
                            <div style="font-size: 8px; color: #fb923c; margin-top: 5px; font-style: italic;">Sent by {{ $transfer->sender->first_name }}</div>
                        @endif
                    </td>
                    <td class="info-cell" width="50%">
                        <div class="label">Ticket Type</div>
                        <div class="value">{{ ucfirst($ticket->ticket_type) }}</div>
                    </td>
                </tr>
                <tr>
                    <td class="info-cell">
                        <div class="label">Ticket Code</div>
                        <div class="value" style="font-family: monospace;">{{ $ticket->ticket_code }}</div>
                    </td>
                    <td class="info-cell">
                        <div class="label">Price</div>
                        <div class="value">{{ $ticket->purchase->currency }} {{ number_format($ticket->purchase->total / $ticket->purchase->quantity) }}</div>
                    </td>
                </tr>
            </table>

            <div class="qr-section">
                @if($ticket->qr_code_path)
                    <img src="{{ public_path($ticket->qr_code_path) }}" class="qr-img">
                @else
                    <div style="padding: 50px; color: #52525b;">QR Code Not Available</div>
                @endif
                <br>
                <div class="status-badge">VALID TICKET</div>
            </div>

            <div class="footer">
                Present this ticket at the entrance. <br>
                Powered by E-Ticketing Platform
            </div>
        </div>
    </div>
</body>
</html>
