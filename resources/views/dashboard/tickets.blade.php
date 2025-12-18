@extends('layouts.dashboard')

@section('title','Overview')

@section('content')
    <h1 class="text-2xl text-white mb-6">My Tickets</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @forelse($tickets as $ticket)
            <div class="bg-white/5 p-5 rounded-xl border border-white/10">
                <h2 class="text-lg text-white">{{ $ticket->event->event_name }}</h2>
                <p class="text-sm text-white/60">
                    {{ $ticket->event->event_date }}
                </p>

                <img src="{{ asset($ticket->qr_code_path) }}"
                     class="w-32 mt-4">

                <a href="{{ route('dashboard.ticket.view', $ticket) }}"
                   class="inline-block mt-4 px-4 py-2 bg-orange-500 rounded-xl text-black">
                    View / Download
                </a>
            </div>
        @empty
            <p class="text-white/50">You have no tickets yet.</p>
        @endforelse
    </div>
@endsection

