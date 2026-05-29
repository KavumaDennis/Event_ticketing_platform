@if($events->isEmpty())
    <p class="text-white/40 text-sm">No events found.</p>
@else
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach($events as $event)
            <a href="{{ route('event.show', $event->id) }}"
               class="group block transition h-fit">
                <div class=" rounded-xl overflow-hidden mb-1 border border-white/10">
                    <img src="{{ $event->event_image ? asset('storage/'.$event->event_image) : asset('default.png') }}"
                         class="w-full h-full object-cover group-hover:scale-105 transition"
                         alt="">
                </div>
                <p class="text-green-400 font-medium text-sm line-clamp-2">{{ $event->event_name }}</p>
                <p class="text-orange-400 text-xs mt-1">{{ $event->location }} &middot; {{ $event->event_date?->format('M d, Y') }}</p>
            </a>
        @endforeach
    </div>
@endif
