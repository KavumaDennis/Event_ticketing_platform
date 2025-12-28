@extends('layouts.dashboard')

@section('title','Event Ticket')

@section('content')
<div class="flex items-center justify-center p-4">
    <div class=" w-fit bg-green-400/10 rounded-3xl overflow-hidden relative">
        {{-- Decorative Header --}}
        <div class="h-14 border-b border-white/20 pb-2 relative">
            <div class="absolute inset-0 flex items-center justify-center">
                <span class="text-4xl text-white/10 font-black tracking-widest">TICKET</span>
            </div>
        </div>

        <div class="p-8 grid grid-cols-3 items-center gap-12 relative">
            {{-- QR Code Card --}}
            <div class="bg-zinc-950/70 col-span-1 p-6 h-fit rounded-2xl border border-zinc-800 shadow-xl flex flex-col items-center">
                <div class="bg-white p-4 rounded-xl mb-4">
                    @if($ticket->qr_code_path)
                    <img src="{{ asset($ticket->qr_code_path) }}" class="w-30 h-30">
                    @else
                    <div class="w-30 h-30 bg-zinc-100 flex items-center justify-center text-zinc-400">
                        No QR
                    </div>
                    @endif
                </div>
                <div class="text-green-400 font-mono text-xl tracking-widest">{{ $ticket->ticket_code }}</div>
                <div class="text-zinc-500 text-xs mt-1">Show this code at the entrance</div>
            </div>

            <div class="col-span-2 flex flex-col">
                {{-- Event Details --}}
                <div class="text-center flex flex-col mb-8">
                    <h1 class="text-2xl font-medium text-white/70 font-mono mb-2">{{ $ticket->event->event_name }}</h1>
                    <div class="flex items-center justify-center gap-2 text-zinc-400 text-sm mb-4">
                        <i class="fas fa-map-marker-alt text-orange-400"></i>
                        <span>{{ $ticket->event->location }}</span>
                        <span class="w-1 h-1 bg-zinc-700 rounded-full"></span>
                        <i class="far fa-calendar text-orange-400"></i>
                        <span>{{ \Carbon\Carbon::parse($ticket->event->event_date)->format('M d, Y') }}</span>
                    </div>

                    <div class="grid grid-cols-2 gap-4 text-left bg-zinc-950/50 p-4 rounded-xl border border-zinc-800/50">
                        <div>
                            <div class="text-xs text-zinc-500 uppercase font-mono tracking-wider mb-1">Attendee</div>
                            <div class="text-white font-medium">{{ $ticket->purchase->user->first_name }}</div>
                        </div>
                        <div>
                            <div class="text-xs text-zinc-500 uppercase font-mono tracking-wider mb-1">Type</div>
                            <div class="text-green-400 font-medium uppercase">{{ $ticket->ticket_type }}</div>
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="space-y-3">
                    <a href="{{ route('ticket.download', $ticket->ticket_code) }}" class="block w-full py-3 bg-green-400 text-black/90 font-medium font-mono text-center rounded-3xl transition-colors">
                        <i class="fas fa-download mr-2"></i> Download Ticket (PDF)
                    </a>

                    <a href="{{ route('user.dashboard.tickets') }}" class="block w-full py-3 bg-orange-400/70 text-black/90 font-medium font-mono text-center rounded-3xl transition-colors">
                        Back to My Tickets
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
