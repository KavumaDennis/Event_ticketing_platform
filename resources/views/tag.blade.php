<x-layout>
    <div class="p-5">
        <h1 class="text-2xl text-orange-400/80 mb-4">#{{ $tag }}</h1>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div>
                <h2 class="text-white/70 text-sm font-bold uppercase mb-3">Events</h2>
                @forelse($events as $event)
                    <a href="{{ route('event.show', $event->id) }}" class="block p-3 mb-3 bg-green-400/10 border border-green-400/10 rounded-2xl hover:bg-green-400/20 transition">
                        <div class="flex items-center gap-3">
                            <img src="{{ $event->event_image ? asset('storage/'.$event->event_image) : asset('default.png') }}" class="w-12 h-12 rounded-xl object-cover" alt="">
                            <div>
                                <p class="text-white/80 font-medium">{{ $event->event_name }}</p>
                                <p class="text-white/40 text-xs">{{ $event->location }}</p>
                            </div>
                        </div>
                    </a>
                @empty
                    <p class="text-white/40 text-sm">No events found for this tag.</p>
                @endforelse
            </div>

            <div>
                <h2 class="text-white/70 text-sm font-bold uppercase mb-3">Trends</h2>
                @forelse($trends as $trend)
                    <a href="{{ route('trends.show', $trend->id) }}" class="block p-3 mb-3 bg-orange-400/10 border border-orange-400/10 rounded-2xl hover:bg-orange-400/20 transition">
                        <div class="flex items-center gap-3">
                            <img src="{{ $trend->user->profile_pic ? asset('storage/'.$trend->user->profile_pic) : asset('default.png') }}" class="w-10 h-10 rounded-full object-cover" alt="">
                            <div>
                                <p class="text-white/80 font-medium">{{ $trend->title }}</p>
                                <p class="text-white/40 text-xs">{{ $trend->user->first_name }} {{ $trend->user->last_name }}</p>
                            </div>
                        </div>
                    </a>
                @empty
                    <p class="text-white/40 text-sm">No trends found for this tag.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-layout>
