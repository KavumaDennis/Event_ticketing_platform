<x-layout>
    <div class="min-h-screen bg-black/85 flex items-center justify-center p-4 bg-[url('/public/bg-img.png')] bg-cover bg-center bg-fixed bg-blend-multiply">
        <div class="max-w-md w-full bg-zinc-900 border border-zinc-800 rounded-3xl overflow-hidden relative">
            {{-- Decorative Header --}}
            <div class="h-32 bg-gradient-to-br from-green-400/20 to-orange-400/20 relative">
                <div class="absolute inset-0 flex items-center justify-center">
                    <span class="text-6xl text-white/10 font-black tracking-widest">TICKET</span>
                </div>
            </div>

            <div class="px-8 pb-8 relative -mt-12">
                {{-- QR Code Card --}}
                <div class="bg-zinc-950 p-6 rounded-2xl border border-zinc-800 shadow-xl mb-6 flex flex-col items-center">
                    <div class="bg-white p-4 rounded-xl mb-4">
                         @if($ticket->qr_code_path)
                            <img src="{{ asset($ticket->qr_code_path) }}" class="w-48 h-48">
                        @else
                            <div class="w-48 h-48 bg-zinc-100 flex items-center justify-center text-zinc-400">
                                No QR
                            </div>
                        @endif
                    </div>
                    <div class="text-green-400 font-mono text-xl tracking-widest">{{ $ticket->ticket_code }}</div>
                    <div class="text-zinc-500 text-xs mt-1">Show this code at the entrance</div>
                </div>

                {{-- Event Details --}}
                <div class="text-center mb-8">
                    <h1 class="text-2xl font-bold text-white mb-2">{{ $event->event_name }}</h1>
                    <div class="flex items-center justify-center gap-2 text-zinc-400 text-sm mb-4">
                        <i class="fas fa-map-marker-alt text-orange-400"></i>
                        <span>{{ $event->location }}</span>
                        <span class="w-1 h-1 bg-zinc-700 rounded-full"></span>
                        <i class="far fa-calendar text-orange-400"></i>
                        <span>{{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}</span>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4 text-left bg-zinc-950/50 p-4 rounded-xl border border-zinc-800/50">
                        <div>
                            <div class="text-xs text-zinc-500 uppercase tracking-wider mb-1">Attendee</div>
                            <div class="text-white font-medium">{{ $ticket->purchase->user->first_name }}</div>
                            @php $transfer = $ticket->latestAcceptedTransfer; @endphp
                            @if($transfer)
                                <div class="text-[10px] text-orange-400/70 mt-1 italic">Sent by {{ $transfer->sender->first_name }}</div>
                            @endif
                        </div>
                        <div>
                            <div class="text-xs text-zinc-500 uppercase tracking-wider mb-1">Type</div>
                            <div class="text-green-400 font-medium uppercase">{{ $ticket->ticket_type }}</div>
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="space-y-3">
                    <a href="{{ route('ticket.download', $ticket->ticket_code) }}" class="block w-full py-4 bg-green-500 hover:bg-green-400 text-black font-bold text-center rounded-xl transition-colors">
                        <i class="fas fa-download mr-2"></i> Download Ticket (PDF)
                    </a>
                    
                    <a href="{{ route('home') }}" class="block w-full py-4 bg-zinc-800 hover:bg-zinc-700 text-zinc-300 font-medium text-center rounded-xl transition-colors">
                        Back to Home
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-layout>
