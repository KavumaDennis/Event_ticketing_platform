<x-layout>
    <div class="p-5">
        <div class="flex flex-col md:flex-row md:items-end gap-4 mb-6">
            <div class="flex-1">
                <h1 class="text-2xl text-white/80">Discover</h1>
                <p class="text-white/40 text-sm">Search events, organizers, trends, and people.</p>
            </div>
            <form method="GET" action="{{ route('discover') }}" class="flex flex-col md:flex-row gap-2 w-full md:w-auto">
                <input type="text" name="q" value="{{ $query }}" placeholder="Search anything..."
                       class="p-3 rounded-2xl bg-white/5 border border-white/10 text-white text-sm w-full md:w-72">
                <input type="text" name="location" value="{{ $location }}" placeholder="Location"
                       class="p-3 rounded-2xl bg-white/5 border border-white/10 text-white text-sm w-full md:w-48">
                <button type="submit" class="px-4 py-3 rounded-2xl bg-orange-400 text-black text-xs font-bold uppercase">Search</button>
            </form>
        </div>

        @if($query)
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-10">
                <div>
                    <h2 class="text-white/70 text-sm font-bold uppercase mb-3">Events</h2>
                    @forelse($events as $event)
                        <a href="{{ route('event.show', $event->id) }}" class="block p-3 mb-3 bg-green-400/10 border border-green-400/10 rounded-2xl hover:bg-green-400/20 transition">
                            <div class="flex items-center gap-3">
                                <img src="{{ $event->event_image ? asset('storage/'.$event->event_image) : asset('default.png') }}" class="w-12 h-12 rounded-xl object-cover" alt="">
                                <div>
                                    <p class="text-white/80 font-medium">{{ $event->event_name }}</p>
                                    <p class="text-white/40 text-xs">{{ $event->location }} • {{ $event->event_date?->format('M d, Y') }}</p>
                                </div>
                            </div>
                        </a>
                    @empty
                        <p class="text-white/40 text-sm">No events found.</p>
                    @endforelse
                </div>

                <div>
                    <h2 class="text-white/70 text-sm font-bold uppercase mb-3">Organizers</h2>
                    @forelse($organizers as $org)
                        <a href="{{ route('organizer.details', $org->id) }}" class="block p-3 mb-3 bg-orange-400/10 border border-orange-400/10 rounded-2xl hover:bg-orange-400/20 transition">
                            <div class="flex items-center gap-3">
                                <img src="{{ $org->organizer_image ? asset('storage/'.$org->organizer_image) : asset('default.png') }}" class="w-12 h-12 rounded-xl object-cover" alt="">
                                <div>
                                    <p class="text-white/80 font-medium">{{ $org->business_name }}</p>
                                    <p class="text-white/40 text-xs">{{ $org->events_count }} events</p>
                                </div>
                            </div>
                        </a>
                    @empty
                        <p class="text-white/40 text-sm">No organizers found.</p>
                    @endforelse
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-10">
                <div>
                    <h2 class="text-white/70 text-sm font-bold uppercase mb-3">Trends</h2>
                    @forelse($trends as $trend)
                        <a href="{{ route('trends.show', $trend->id) }}" class="block p-3 mb-3 bg-white/5 border border-white/10 rounded-2xl hover:bg-white/10 transition">
                            <div class="flex items-center gap-3">
                                <img src="{{ $trend->user->profile_pic ? asset('storage/'.$trend->user->profile_pic) : asset('default.png') }}" class="w-10 h-10 rounded-full object-cover" alt="">
                                <div>
                                    <p class="text-white/80 font-medium">{{ $trend->title }}</p>
                                    <p class="text-white/40 text-xs">{{ $trend->user->first_name }} {{ $trend->user->last_name }}</p>
                                </div>
                            </div>
                        </a>
                    @empty
                        <p class="text-white/40 text-sm">No trends found.</p>
                    @endforelse
                </div>

                <div>
                    <h2 class="text-white/70 text-sm font-bold uppercase mb-3">People</h2>
                    @forelse($users as $u)
                        <a href="{{ route('user.profile', $u->id) }}" class="block p-3 mb-3 bg-white/5 border border-white/10 rounded-2xl hover:bg-white/10 transition">
                            <div class="flex items-center gap-3">
                                <img src="{{ $u->profile_photo_url }}" class="w-10 h-10 rounded-full object-cover" alt="">
                                <div>
                                    <p class="text-white/80 font-medium">{{ $u->first_name }} {{ $u->last_name }}</p>
                                    <p class="text-white/40 text-xs">{{ $u->username ?? $u->email }}</p>
                                </div>
                            </div>
                        </a>
                    @empty
                        <p class="text-white/40 text-sm">No users found.</p>
                    @endforelse
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="p-4 bg-green-400/10 border border-green-400/10 rounded-3xl">
                <h2 class="text-white/80 font-bold text-sm uppercase mb-3">Near Me: {{ $location }}</h2>
                <div class="h-56 rounded-2xl overflow-hidden border border-white/10 mb-4">
                    <iframe width="100%" height="100%" frameborder="0" style="border:0"
                            src="https://maps.google.com/maps?q={{ urlencode($location) }}&t=&z=13&ie=UTF8&iwloc=&output=embed" allowfullscreen></iframe>
                </div>
                @forelse($nearbyEvents as $event)
                    <a href="{{ route('event.show', $event->id) }}" class="block p-3 mb-3 bg-black/40 border border-white/10 rounded-2xl hover:bg-black/60 transition">
                        <div class="flex items-center gap-3">
                            <img src="{{ $event->event_image ? asset('storage/'.$event->event_image) : asset('default.png') }}" class="w-10 h-10 rounded-xl object-cover" alt="">
                            <div>
                                <p class="text-white/80 font-medium">{{ $event->event_name }}</p>
                                <p class="text-white/40 text-xs">{{ $event->venue }}</p>
                            </div>
                        </div>
                    </a>
                @empty
                    <p class="text-white/40 text-sm">No nearby events found.</p>
                @endforelse
            </div>

            <div class="p-4 bg-orange-400/10 border border-orange-400/10 rounded-3xl">
                <h2 class="text-white/80 font-bold text-sm uppercase mb-3">Recommended For You</h2>
                @if($recommendedEvents->isEmpty())
                    <p class="text-white/40 text-sm">Like, save, or buy tickets to get personalized recommendations.</p>
                @else
                    @foreach($recommendedEvents as $event)
                        <a href="{{ route('event.show', $event->id) }}" class="block p-3 mb-3 bg-black/40 border border-white/10 rounded-2xl hover:bg-black/60 transition">
                            <div class="flex items-center gap-3">
                                <img src="{{ $event->event_image ? asset('storage/'.$event->event_image) : asset('default.png') }}" class="w-10 h-10 rounded-xl object-cover" alt="">
                                <div>
                                    <p class="text-white/80 font-medium">{{ $event->event_name }}</p>
                                    <p class="text-white/40 text-xs">{{ $event->category }} • {{ $event->event_date?->format('M d') }}</p>
                                </div>
                            </div>
                        </a>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</x-layout>
