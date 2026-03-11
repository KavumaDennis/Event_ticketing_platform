@foreach($events as $event)
<div class="event-card group hover:border-orange-400/20 transition-all duration-500 shadow-xl shadow-black/20" data-id="{{ $event->id }}">
    {{-- Event Image --}}
    <div class="relative overflow-hidden">
        <img src="{{ $event->event_image ? asset('storage/'.$event->event_image) : asset('default.png') }}" class="w-full h-70 object-cover opacity-80 group-hover:opacity-100" alt="{{ $event->event_name }}">

        @if($event->isHot() || $event->isBoostActive())
        <div class="absolute top-4 right-4 z-20">
            <span class="flex items-center gap-1.5 px-3 py-1 bg-orange-500 text-black text-[10px] font-black uppercase tracking-tighter rounded-full shadow-lg shadow-orange-500/40 animate-pulse">
                <i class="fa-solid fa-fire-flame-curved"></i> Hot
            </span>
        </div>
        @endif

        <div class="absolute top-4 left-4">
            <span class="px-3 py-1 bg-orange-400/80 backdrop-blur-md border border-green-400/30 rounded-full text-[10px] text-black/90 font-bold uppercase tracking-widest">
                {{ $event->category ?? 'Event' }}
            </span>
        </div>


    </div>

    {{-- Event Content --}}
    <div class="p-4 bg-green-400/10 border border-green-400/5 mt-1">
        <div class="flex justify-between items-start mb-3">
            <h3 class="font-medium font-mono text-base leading-tight text-orange-400 transition-colors line-clamp-1">{{ $event->event_name }}</h3>
            <span class="text-white text-xs font-bold font-mono">{{ $event->regular_price ? 'UGX '.number_format($event->regular_price) : 'Free' }}</span>
        </div>

        <div class="flex items-center justify-between gap-3 mb-3 py-2 border-y border-white/5">
            <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-orange-400 border border-green-400/10 p-0.5 flex-shrink-0">
                <img src="{{ $event->organizer->organizer_image ? asset('storage/'.$event->organizer->organizer_image) : asset('default.png') }}" class="w-full h-full rounded-full object-cover" alt="{{ $event->organizer->business_name }}">
            </div>
                <span class="text-xs text-white/80 font-mono font-medium">{{ $event->organizer->business_name }}</span>
            </div>

            <div class="flex gap-5 items-center justify-between">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-calendar-day text-orange-400 text-xs"></i>
                    <span class="text-xs text-white/90 font-medium font-mono">{{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}</span>
                </div>
                <div class="flex items-center gap-1.5 px-2 py-1 bg-zinc-950/40 backdrop-blur-md rounded-xl border border-white/5">
                    <i class="fa-solid fa-location-dot text-[10px] text-zinc-500"></i>
                    <span class="text-[10px] text-white/70 font-mono">{{ Str::limit($event->venue, 15) }}</span>
                </div>
            </div>

        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('event.show', $event->id) }}" class="flex-1 bg-black/90 text-orange-400 font-bold py-3 rounded-3xl text-xs text-center transition-all duration-300">
                View Details
            </a>
            <button onclick="toggleSaveEvent({{ $event->id }})" class="size-10 flex items-center justify-center  hover:bg-white/10 text-black rounded-full bg-orange-400/80 transition-all">
                <i class="fa-regular fa-bookmark"></i>
            </button>
        </div>
    </div>
</div>
@endforeach
