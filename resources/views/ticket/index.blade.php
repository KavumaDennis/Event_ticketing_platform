<x-layout>
    <div class="min-h-screen bg-black/85 flex flex-col items-center py-12 px-4 bg-[url('/public/bg-img.png')] bg-cover bg-center bg-fixed bg-blend-multiply" x-data="{ joinOpen: {{ !empty($shouldPromptJoinGroup) ? 'true' : 'false' }} }">
        
        <div class="max-w-4xl w-full text-center mb-12">
            <h1 class="text-4xl font-black text-white mb-2 uppercase tracking-tighter">Your Tickets are Ready!</h1>
            <p class="text-zinc-400">Thank you for your purchase. You can find your tickets for <strong>{{ $event->event_name }}</strong> below.</p>
        </div>

        <div class="grid grid-cols-1 gap-8 max-w-lg w-full">
            @foreach($tickets as $ticket)
                <div class="bg-green-400/20 border border-green-400/50 overflow-hidden shadow-2xl transition hover:border-green-500/50">
                    {{-- Ticket Header --}}
                    <div class="p-6 border-b border-zinc-400 flex justify-between items-start">
                        <div>
                            <div class="text-[10px] text-zinc-500 uppercase tracking-[0.2em] font-bold mb-1">Pass Type</div>
                            <div class="text-green-400 font-black uppercase text-xl">{{ $ticket->ticket_type }}</div>
                        </div>
                        <div class="text-right">
                            <div class="text-[10px] text-zinc-500 uppercase tracking-[0.2em] font-bold mb-1">Ticket ID</div>
                            <div class="text-white font-mono text-sm tracking-tighter">{{ $ticket->ticket_code }}</div>
                        </div>
                    </div>

                    {{-- QR Code Area --}}
                    <div class="p-8 flex flex-col items-center bg-zinc-950/50">
                        <div class="bg-white p-3 rounded-2xl shadow-inner mb-4">
                            @if($ticket->qr_code_path)
                                <img src="{{ asset($ticket->qr_code_path) }}" class="w-40 h-40">
                            @else
                                <div class="w-40 h-40 flex items-center justify-center text-zinc-400">QR CODE</div>
                            @endif
                        </div>
                        <p class="text-zinc-500 text-[10px] uppercase font-bold tracking-widest mb-6">Scan at Entrance</p>
                        
                        <div class="w-full flex gap-3">
                            <a href="{{ route('ticket.show', $ticket->ticket_code) }}" class="flex-1 py-2 bg-zinc-800 border border-white/50 hover:bg-zinc-700 text-white text-[10px] uppercase font-bold rounded-lg text-center transition">
                                View Online
                            </a>
                            <a href="{{ route('ticket.download', $ticket->ticket_code) }}" class="flex-1 py-2 bg-orange-400 hover:bg-green-400/50 text-black text-[10px] uppercase font-bold rounded-lg text-center transition">
                                Download PDF
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-12 text-center">
            <a href="{{ route('home') }}" class="text-zinc-500 hover:text-white transition text-sm font-medium">
                <i class="fas fa-arrow-left mr-2"></i> Return to Homepage
            </a>
        </div>

        {{-- Join attendee group chat prompt --}}
        <div x-show="joinOpen" x-transition.opacity x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" @click="joinOpen = false"></div>
            <div class="relative w-full max-w-lg bg-zinc-950/90 border border-white/10 rounded-3xl overflow-hidden shadow-2xl">
                <div class="px-5 py-4 flex items-center justify-between border-b border-white/10">
                    <h3 class="text-white font-bold">Join group chat?</h3>
                    <button @click="joinOpen = false" class="size-9 rounded-full hover:bg-white/10 text-white/60 transition">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                <div class="px-5 py-5 text-white/70 text-sm leading-relaxed">
                    Would you like to join the attendee group chat for <span class="text-orange-400 font-bold">{{ $event->event_name }}</span>?
                </div>
                <div class="p-4 border-t border-white/10 bg-black/30 flex gap-3">
                    <button type="button" @click="joinOpen = false" class="flex-1 py-3 rounded-2xl bg-white/5 hover:bg-white/10 text-white/80 font-bold transition">
                        Not now
                    </button>
                    <form class="flex-1" method="POST" action="{{ route('messages.join-group', $event) }}">
                        @csrf
                        <button type="submit" class="w-full py-3 rounded-2xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold transition">
                            Join chat
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layout>
