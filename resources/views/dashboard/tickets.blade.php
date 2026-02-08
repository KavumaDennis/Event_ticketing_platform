@extends('layouts.dashboard')

@section('title', 'My Tickets')

@section('content')
<div class="px-6 pb-20">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div>
            <h1 class="text-2xl text-white/70">My Tickets</h1>
            <p class="text-orange-400/70 font-mono text-sm">Manage your event access and gifts</p>
        </div>
        <div class="bg-green-400/10 p-1 rounded-2xl flex border border-green-400/5 w-full sm:w-auto overflow-x-auto">
            <button onclick="switchTab('upcoming')" id="tab-upcoming" class="tab-btn flex-1 sm:flex-none px-6 py-2 rounded-xl text-xs sm:text-sm font-bold transition-all bg-orange-500 text-black whitespace-nowrap">Upcoming</button>
            <button onclick="switchTab('past')" id="tab-past" class="tab-btn flex-1 sm:flex-none px-6 py-2 rounded-xl text-xs sm:text-sm font-bold transition-all text-zinc-500 hover:text-white whitespace-nowrap">Past Events</button>
            <button onclick="switchTab('cancelled')" id="tab-cancelled" class="tab-btn flex-1 sm:flex-none px-6 py-2 rounded-xl text-xs sm:text-sm font-bold transition-all text-zinc-500 hover:text-white whitespace-nowrap">Cancelled</button>
        </div>
    </div>

    {{-- Upcoming Tickets --}}
    <div id="content-upcoming" class="tab-content transition-all duration-500">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            @forelse($upcoming as $ticket)
            <div class="overflow-hidden group hover:border-orange-500/30 transition-all duration-500 relative">
                <div class="flex flex-col sm:flex-row sm:h-40 gap-3">
                    {{-- Event Graphic --}}
                    <div class="w-full sm:w-1/3 h-32 sm:h-full relative overflow-hidden">
                        <img src="{{ $ticket->event->event_image ? asset('storage/'.$ticket->event->event_image) : asset('default.jpg') }}" class="h-full w-full object-cover rounded-3xl">
                    </div>

                    {{-- Ticket Info --}}
                    <div class="flex-1 p-3 flex flex-col justify-between bg-green-400/10  rounded-3xl">
                        <div>
                            <div class="flex justify-between items-start">
                                <span class="px-3 py-1 bg-orange-500/10 text-orange-400/80 text-[10px] font-black uppercase tracking-widest rounded-full border border-orange-500/20">
                                    {{ $ticket->ticket_type }}
                                </span>
                                <span class="text-zinc-600 font-mono text-xs">#{{ $ticket->ticket_code }}</span>
                            </div>
                            <h3 class="text-md font-medium font-mono text-white/70 mt-3 line-clamp-1 group-hover:text-orange-400 transition-colors">{{ $ticket->event->event_name }}</h3>
                            <p class="text-zinc-500 text-sm mt-1"><i class="far fa-calendar-alt mr-1"></i> {{ \Carbon\Carbon::parse($ticket->event->event_date)->format('D, M d, Y') }}</p>
                        </div>

                        <div class="flex gap-3 mt-4 sm:mt-0">
                            <a href="{{ route('dashboard.ticket.view', $ticket) }}" class="flex-1 text-center font-bold bg-green-400/10 border border-green-400/20 text-xs rounded-2xl p-2 text-orange-400/50 font-mono">
                                View qr-code
                            </a>
                            <button onclick="openTransferModal({{ $ticket->id }}, '{{ $ticket->event->event_name }}')" class="flex-1 text-xs p-2 font-mono font-medium bg-orange-400/70 rounded-2xl text-black/90">
                                Gift ticket
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-2 p-8 bg-green-400/5 border border-dashed border-green-400/20 rounded-3xl text-center">
                <i class="fas fa-ticket-alt text-4xl text-zinc-800 mb-4 block"></i>
                <p class="text-zinc-600 font-bold">No upcoming tickets found.</p>
                <a href="{{ route('home') }}" class="text-orange-500 text-sm mt-4 inline-block hover:underline">Explore Events</a>
            </div>
            @endforelse
        </div>
    </div>

    {{-- Past Tickets --}}
    <div id="content-past" class="tab-content hidden transition-all duration-500">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            @forelse($past as $ticket)
            <div class="bg-green-400/10 rounded-2xl p-6 hover:opacity-100 transition-opacity">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="text-lg font-bold text-white/70">{{ $ticket->event->event_name }}</h3>
                        <p class="text-zinc-500 text-xs mt-1">{{ \Carbon\Carbon::parse($ticket->event->event_date)->format('M d, Y') }}</p>
                    </div>
                    <a href="{{ route('user.dashboard.support') }}" class="text-[10px] uppercase font-bold text-zinc-500 hover:text-white">Report Issue</a>
                </div>
                <div class="mt-6 flex gap-3">
                    <button class="flex-1 py-2 bg-zinc-800 text-zinc-400 rounded-xl text-xs font-bold cursor-not-allowed font-mono">EVENT ENDED</button>
                    {{-- Add review logic here --}}
                    <a href="{{ route('user.dashboard.support') }}" class="px-4 py-2 bg-orange-500/70 text-black/90 rounded-xl text-xs font-bold transition-all font-mono">RATE EVENT</a>
                </div>
            </div>
            @empty
            <p class="text-zinc-600 text-center col-span-2 py-10">No past events yet.</p>
            @endforelse
        </div>
    </div>

    {{-- Cancelled --}}
    <div id="content-cancelled" class="tab-content hidden transition-all duration-500">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            @forelse($cancelled as $ticket)
            <div class="bg-zinc-900/30 border border-red-500/10 rounded-[2.5rem] p-6">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-bold text-zinc-400">{{ $ticket->event->event_name }}</h3>
                    <span class="px-3 py-1 bg-red-500/10 text-red-500 text-[10px] font-black uppercase rounded-full">FAILED</span>
                </div>
                <p class="text-zinc-600 text-xs mt-2">Transaction was not completed successfully.</p>
            </div>
            @empty
            <p class="text-zinc-600 text-center col-span-2 py-10">No failed transactions.</p>
            @endforelse
        </div>
    </div>
</div>

{{-- Transfer Modal --}}
<div id="transferModal" class="fixed inset-0 bg-black/30 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-black/85 bg-[url(/public/bg-img.png)] bg-cover bg-center bg-fixed  bg-blend-multiply border border-green-400/30 backdrop-blur-[1px] w-full max-w-lg p-4 shadow-xl relative">
        <div class="absolute -right-20 -top-20 size-60 bg-green-500/5 rounded-full blur-3xl"></div>

        <h2 class="text-3xl font-black text-white/70 font-mono tracking-tighter mb-2">GIFT TICKET</h2>
        <p class="text-orange-400/70 text-sm mb-8" id="transferEventName"></p>

        <form id="transferForm" method="POST" action="">
            @csrf
            <div class="mb-6">
                <label class="block text-zinc-500 text-xs font-bold uppercase mb-2 ml-1">Recipient Email</label>
                <input type="email" name="recipient_email" required class="w-full p-3 rounded-xl bg-[#b0a6df]/10 outline outline-[#b0a6df]/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70" placeholder="friend@example.com">
            </div>

            <div class="flex gap-4">
                <button type="button" onclick="closeTransferModal()" class="flex-1 py-2 bg-orange-400/70 font-mono text-black/90 font-medium rounded-3xl">Cancel</button>
                <button type="submit" class="flex-1 py-2 bg-green-400/70 text-zinc-900 font-medium font-mono rounded-3xl hover:bg-green-400">Send Gift</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function switchTab(tab) {
        // Update Buttons
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('bg-orange-500', 'text-black');
            btn.classList.add('text-zinc-500', 'hover:text-white');
        });
        document.getElementById('tab-' + tab).classList.remove('text-zinc-500', 'hover:text-white');
        document.getElementById('tab-' + tab).classList.add('bg-orange-500', 'text-black');

        // Update Content
        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.add('hidden');
        });
        document.getElementById('content-' + tab).classList.remove('hidden');
    }

    function openTransferModal(ticketId, eventName) {
        const modal = document.getElementById('transferModal');
        const form = document.getElementById('transferForm');
        const eventText = document.getElementById('transferEventName');

        form.action = `/ticket/${ticketId}/transfer`;
        eventText.textContent = "You are gifting a ticket for: " + eventName;
        modal.classList.remove('hidden');
    }

    function closeTransferModal() {
        document.getElementById('transferModal').classList.add('hidden');
    }

</script>
@endpush
@endsection
