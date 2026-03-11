<x-layout>
    <div class="min-h-screen bg-black/85 flex flex-col items-center py-12 px-4 bg-[url('/public/bg-img.png')] bg-cover bg-center bg-fixed bg-blend-multiply">
        
        <div class="max-w-4xl w-full text-center mb-12">
            <h1 class="text-4xl font-black text-white mb-2 uppercase tracking-tighter">Your Tickets are Ready!</h1>
            <p class="text-zinc-400">Thank you for your purchase. You can find your tickets for <strong>{{ $event->event_name }}</strong> below.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-5xl w-full">
            @foreach($tickets as $ticket)
                <div class="bg-zinc-900 border border-zinc-800 rounded-3xl overflow-hidden shadow-2xl transition hover:border-green-500/50">
                    {{-- Ticket Header --}}
                    <div class="p-6 border-b border-zinc-800 flex justify-between items-start">
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
                            <a href="{{ route('ticket.show', $ticket->ticket_code) }}" class="flex-1 py-3 bg-zinc-800 hover:bg-zinc-700 text-white text-xs font-bold rounded-xl text-center transition">
                                View Online
                            </a>
                            <a href="{{ route('ticket.download', $ticket->ticket_code) }}" class="flex-1 py-3 bg-green-500 hover:bg-green-400 text-black text-xs font-bold rounded-xl text-center transition">
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
    </div>
</x-layout>
