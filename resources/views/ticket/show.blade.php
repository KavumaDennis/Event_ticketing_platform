<x-layout>

<div class="grid grid-cols-2 gap-5 p-10">

    {{-- LEFT SIDE (Ticket Design Visual) --}}
    <div class="bg-green-400/10 border border-green-400/10 w-full p-6 shadow-lg rounded-3xl">

        <h1 class="text-2xl text-white/70 mb-6">Your Ticket</h1>

        <div class="space-y-4 text-white">

            <div class="flex items-center">
                <span class="pr-3 relative text-sm text-orange-400/70 font-medium after:content-[''] after:bg-orange-400/80 after:absolute after:w-[3px] after:h-[12px] after:rounded-lg after:right-0">
                    Event
                </span>
                <span class="pl-3 text-white/60 font-mono">{{ $ticket->event->event_name }}</span>
            </div>

            <div class="flex items-center">
                <span class="pr-3 relative text-sm text-orange-400/70 font-medium after:content-[''] after:bg-orange-400/80 after:absolute after:w-[3px] after:h-[12px] after:h-[12px] after:rounded-lg after:right-0">
                    Ticket Type
                </span>
                <span class="pl-3 text-white/60 font-mono">{{ ucfirst($ticket->ticket_type) }}</span>
            </div>

            <div class="flex items-center">
                <span class="pr-3 relative text-sm text-orange-400/70 font-medium after:content-[''] after:bg-orange-400/80 after:absolute after:w-[3px] after:h-[12px] after:rounded-lg after:right-0">
                    Ticket Code
                </span>
                <span class="pl-3 text-white/60 font-mono">{{ $ticket->ticket_code }}</span>
            </div>

            <div class="pt-4">
                <img src="{{ asset('storage/qrcodes/' . $ticket->ticket_code . '.png') }}"
                     class="w-40 h-40 rounded-xl border border-orange-400/40 shadow-lg">
            </div>

        </div>

        <a href="{{ route('ticket.download', $ticket->ticket_code) }}"
           class="block mt-6 w-full text-center py-3 bg-orange-400/80 font-mono hover:bg-orange-600 transition rounded-3xl text-black/90 shadow-lg">
           Download Ticket
        </a>

    </div>

    {{-- RIGHT SIDE: IMAGE / ADS / PROMO --}}
    <div class="col-span-1 relative overflow-hidden h-full">
        <img src="{{ asset('event-banners/' . $ticket->event->banner) }}"
             class="opacity-80 absolute inset-0 w-full h-full object-cover">
    </div>

</div>

</x-layout>
