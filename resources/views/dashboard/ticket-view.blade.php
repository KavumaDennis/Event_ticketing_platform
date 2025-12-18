@extends('layouts.dashboard')

@section('title','Overview')

@section('content')
    <div class="max-w-xl mx-auto p-6">
        <div class="bg-white/5 border border-white/10 rounded-2xl p-6 text-center">
            <h1 class="text-2xl text-white mb-2">
                {{ $ticket->event->event_name }}
            </h1>

            <p class="text-white/60 mb-4">
                {{ ucfirst($ticket->ticket_type) }} Ticket
            </p>

            <img src="{{ asset($ticket->qr_code_path) }}"
                 alt="Ticket QR Code"
                 class="mx-auto w-56 h-56 bg-white p-3 rounded-xl">

            <p class="mt-4 text-white/50 font-mono">
                Code: {{ $ticket->ticket_code }}
            </p>

            <div class="mt-6 flex gap-3 justify-center">
                <a href="{{ asset($ticket->qr_code_path) }}"
                   download
                   class="px-4 py-2 bg-green-500 rounded-xl text-black font-medium">
                    Download QR
                </a>

                <a href="{{ route('user.dashboard.tickets') }}"
                   class="px-4 py-2 bg-white/10 rounded-xl text-white">
                    Back to Tickets
                </a>
            </div>
        </div>
    </div>
@endsection
