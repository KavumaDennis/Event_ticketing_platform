@extends('layouts.dashboard')

@section('title','Events Feed')

@section('content')
<div class="max-w-7xl mx-auto">
    {{-- Flex Container for Side-by-Side Layout --}}
    <div class="flex flex-col lg:flex-row gap-8">

        {{-- LEFT COLUMN: Events Feed --}}
        <div class="w-full lg:flex-1 min-w-0">
            <div x-data="{ activeTab: 'discovery' }">

                {{-- SUCCESS MESSAGE --}}
                @if (session('success'))
                <div class="p-3 mb-6 bg-green-500/20 border border-green-500/50 text-green-400 rounded-2xl text-sm font-mono text-center animate-pulse">
                    {{ session('success') }}
                </div>
                @endif

                @include('partials.experiences-following', ['experienceUsers' => $experienceUsers, 'seenExperienceIds' => $seenExperienceIds])

                {{-- FEED TABS --}}
                <div class="flex items-center gap-4 mb-8 sticky top-0 z-10 bg-orange-400/80 backdrop-blur-xl p-0.5 rounded-3xl">
                    <button @click="activeTab = 'discovery'" :class="activeTab === 'discovery' ? 'bg-black/90 text-orange-400 font-bold' : 'text-black'" class="flex-1 py-3 rounded-3xl text-xs uppercase font-mono tracking-widest transition-all duration-300">
                        Discover Events
                    </button>
                    <button @click="activeTab = 'my-events'" :class="activeTab === 'my-events' ? 'bg-black/90 text-orange-400 font-bold' : 'text-black'" class="flex-1 py-3 rounded-3xl text-xs uppercase font-mono tracking-widest transition-all duration-300">
                        Your Events
                    </button>
                    <a href="{{ route('events.create') }}" class="size-10 bg-black/90 border border-green-400/20 rounded-full flex items-center justify-center text-orange-400 hover:bg-green-800/80 hover:text-black transition-all">
                        <i class="fa-solid fa-plus"></i>
                    </a>
                </div>

                {{-- DISCOVERY FEED (Infinite Scroll) --}}
                <div x-show="activeTab === 'discovery'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" id="discovery-feed">
                    <div id="event-feed-container" class="space-y-8">
                        @include('partials.event-dashboard-card', ['events' => $events])
                    </div>

                    {{-- Loading Indicator --}}
                    <div id="loading-trigger-events" class="py-10 flex flex-col items-center justify-center gap-3">
                        <div id="loader-events" class="size-6 border-2 border-orange-400/20 border-t-orange-400 rounded-full animate-spin"></div>
                        <p id="no-more-msg-events" class="hidden text-zinc-600 text-xs font-mono uppercase tracking-widest text-center">That's all for now. Check back later for more events!</p>
                    </div>
                </div>

                {{-- YOUR EVENTS (Grid with Management) --}}
                <div x-show="activeTab === 'my-events'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                    @if($organizer)
                    <div class="grid grid-cols-1 gap-4">
                        @forelse($organizedEvents as $ev)
                        <div class="bg-green-400/10 border border-green-400/10 p-3  group hover:border-orange-400/20 transition-all flex gap-4 items-center">
                            <div class="relative size-24 rounded-2xl overflow-hidden shrink-0">
                                <img src="{{ $ev->event_image ? asset('storage/'.$ev->event_image) : asset('default.png') }}" class="w-full h-full object-cover transition-all" alt="{{ $ev->event_name }}">
                            </div>
                            <div class="flex-1">
                                <h4 class="text-white/80 font-bold text-sm mb-1 line-clamp-1">{{ $ev->event_name }}</h4>
                                <p class="text-[10px] text-zinc-500 font-mono mb-3 uppercase tracking-tighter">{{ \Carbon\Carbon::parse($ev->event_date)->format('M d, Y') }}</p>
                                <div class="flex items-center gap-2">
                                    <button onclick="openEditModal({{ json_encode($ev) }})" class="size-7 rounded-md flex items-center justify-center bg-orange-400 text-black transition-all cursor-pointer">
                                        <i class="fa-solid fa-pen-to-square text-[10px]"></i>
                                    </button>
                                    <form action="{{ route('events.destroy', $ev->id) }}" method="POST" onsubmit="return confirm('Delete this event?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="size-7 rounded-md flex items-center justify-center bg-red-500 text-white transition-all cursor-pointer">
                                            <i class="fa-solid fa-trash text-[10px]"></i>
                                        </button>
                                    </form>
                                    <div class="flex items-center gap-3 ml-auto">
                                        <a href="{{ route('organizer.retargeting', $ev->id) }}" class="px-3 py-1.5 bg-white/5 border border-white/20 text-orange-400 rounded-lg flex items-center gap-2 hover:bg-orange-400 hover:text-black transition-all text-[10px] font-bold uppercase" title="Retarget Users">
                                            <i class="fa-solid fa-bullseye"></i> Leads
                                        </a>
                                        <a href="{{ route('event.show', $ev->id) }}" class="size-7.5 bg-white/5 border border-white/20 rounded-lg flex items-center justify-center text-orange-400 transition-all shrink-0">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye-icon lucide-eye">
                                                <path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0" />
                                                <circle cx="12" cy="12" r="3" /></svg>
                                        </a>
                                        <a href="{{ route('boost.select', $ev->id) }}" class="px-3 py-1.5 {{ $ev->is_boosted ? 'bg-orange-400 text-black border border-orange-400' : 'bg-white/5 border border-white/20 text-zinc-400' }} rounded-lg flex items-center gap-2 hover:bg-orange-500 hover:text-black transition-all text-[10px] font-bold uppercase" title="{{ $ev->is_boosted ? 'Active Boost' : 'Activate Boost' }}">
                                            <i class="fa-solid fa-rocket"></i> {{ $ev->is_boosted ? 'Boosted' : 'Boost' }}
                                        </a>

                                    </div>
                                </div>
                            </div>

                        </div>
                        @empty
                        <div class="py-20 text-center opacity-40">
                            <i class="fa-solid fa-calendar-plus text-4xl mb-4 text-zinc-700"></i>
                            <p class="text-sm font-mono uppercase tracking-widest text-zinc-500">You haven't created any events yet</p>
                        </div>
                        @endforelse
                    </div>
                    @else
                    <div class="py-20 text-center bg-zinc-900/40 border border-dashed border-white/5 rounded-[3rem]">
                        <i class="fa-solid fa-building text-4xl mb-4 text-zinc-800"></i>
                        <h3 class="text-white font-bold mb-2">Not an Organizer</h3>
                        <p class="text-xs text-zinc-500 font-mono mb-6">Create an organizer profile to start hosting events</p>
                        <a href="{{ route('organizer.create') }}" class="px-8 py-3 bg-orange-400 text-black font-bold rounded-2xl text-xs uppercase tracking-widest hover:bg-orange-500 transition-all">Get Started</a>
                    </div>
                    @endif
                </div>

            </div> {{-- End x-data --}}
        </div> {{-- End Left Column --}}

        {{-- RIGHT COLUMN: Sidebar --}}
        <div class="w-full lg:w-96 shrink-0 hidden lg:block">
            <div class="sticky top-0">
                <div class="flex flex-col gap-3">
                    <!-- Random Organizers -->
                    <div class="">
                        {{-- Search Bar for Events --}}
                        <div class="mb-6 relative group">
                            <input type="text" id="events-search" placeholder="Search events, venues..." class="w-full bg-green-400/10 border border-green-400/10 rounded-2xl py-3 px-11 text-sm text-white placeholder-zinc-500 focus:outline-none focus:border-orange-400/30 transition-all">
                            <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-zinc-500 group-focus-within:text-orange-400/70 transition-colors"></i>
                            <div id="events-search-loader" class="hidden absolute right-4 top-1/2 -translate-y-1/2">
                                <div class="size-4 border-2 border-orange-400/20 border-t-orange-400 rounded-full animate-spin"></div>
                            </div>
                        </div>

                        <h2 class="text-xs p-1 font-mono font-medium bg-orange-400 rounded-2xl w-fit text-black/90 mb-3">Organizers to Follow</h2>
                        <div class="flex flex-col gap-2">
                            @forelse($randomOrganizers as $organizer)
                            <div class="flex items-center justify-between gap-3 h-fit p-3 bg-green-400/10 border border-green-400/5 rounded-2xl hover:border-orange-400/30 transition">
                                <div class="flex items-center gap-3 flex-1 min-w-0">
                                    <div class="w-10 h-10 rounded-full bg-orange-400 border border-green-400/10 p-0.5 flex-shrink-0">
                                        <img src="{{ $organizer->organizer_image ? asset('storage/'.$organizer->organizer_image) : asset('default.png') }}" onerror="this.onerror=null; this.src='{{ asset('default.png') }}';" class='w-full h-full rounded-full object-cover' alt="{{ $organizer->business_name }}" />
                                    </div>
                                    <div class="flex flex-col min-w-0">
                                        <a href="{{ route('organizer.details', $organizer->id) }}" class='text-orange-400/90 font-medium text-sm hover:text-orange-400 transition truncate'>{{ $organizer->business_name }}</a>
                                        <p class='text-white/50 font-mono text-xs'>{{ $organizer->events_count ?? $organizer->events()->count() }} events</p>
                                    </div>
                                </div>
                                @auth
                                <button class="organizer-follow-btn px-3 py-1.5 bg-white/5 border border-white/20 text-orange-400 text-center rounded-lg flex items-center justify-center gap-2 hover:text-white duration-150 transition-colors text-[10px] font-bold uppercase flex-shrink-0" data-organizer="{{ $organizer->id }}">
                                    Follow
                                </button>
                                @else
                                <a href="{{ route('show.login') }}" class="px-3 py-1.5 bg-white/5 border border-white/20 text-orange-400 text-center rounded-lg flex items-center justify-center gap-2 hover:text-white duration-150 transition-colors text-[10px] font-bold uppercase flex-shrink-0">
                                    Follow
                                </a>
                                @endauth
                            </div>
                            @empty
                            <div class="text-center py-4">
                                <p class="text-white/60 text-sm">No organizers found</p>
                            </div>
                            @endforelse
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div> {{-- End Flex Container --}}
</div> {{-- End max-w-7xl --}}

{{-- EDIT EVENT MODAL (Refined UI) --}}
<div id="editModal" class="hidden fixed inset-0 bg-black/30 backdrop-blur-sm z-50  flex items-center justify-center p-4">
    <div class="bg-black/85 bg-[url(/public/bg-img.png)] bg-cover bg-center bg-fixed  bg-blend-multiply border border-green-400/30 backdrop-blur-[1px] w-full max-w-2xl max-h-[90vh] overflow-y-auto p-8 shadow-2xl relative custom-scrollbar">
        <button onclick="closeEditModal()" class="absolute top-6 right-6 text-zinc-500 hover:text-white transition-colors">
            <i class="fa-solid fa-xmark text-xl"></i>
        </button>

        <h2 class="text-2xl font-black text-white tracking-tighter uppercase mb-8">Edit Event Details</h2>

        <form id="editForm" method="POST" enctype="multipart/form-data" class="grid grid-cols-2 gap-6">
            @csrf
            @method('PUT')

            <div class="col-span-2 md:col-span-1">
                <label class="block text-[10px] uppercase tracking-widest text-zinc-500 font-bold mb-2 ml-1">Event Name</label>
                <input type="text" id="edit_name" name="event_name" class="w-full p-3 rounded-xl bg-white/5 outline outline-white/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70" required>
            </div>

            <div class="col-span-2 md:col-span-1">
                <label class="block text-[10px] uppercase tracking-widest text-zinc-500 font-bold mb-2 ml-1">Category</label>
                <input type="text" id="edit_category" name="category" class="w-full p-3 rounded-xl bg-white/5 outline outline-white/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70">
            </div>

            <div class="col-span-2">
                <label class="block text-[10px] uppercase tracking-widest text-zinc-500 font-bold mb-2 ml-1">Venue & Location</label>
                <div class="grid grid-cols-2 gap-4">
                    <input type="text" id="edit_venue" name="venue" placeholder="Venue Name" class="w-full p-3 rounded-xl bg-white/5 outline outline-white/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70">
                    <input type="text" id="edit_location" name="location" placeholder="City/Town" class="w-full p-3 rounded-xl bg-white/5 outline outline-white/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70">
                </div>
            </div>

            <div class="col-span-2 grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-[10px] uppercase tracking-widest text-zinc-500 font-bold mb-2 ml-1">Date</label>
                    <input type="date" id="edit_event_date" name="event_date" class="w-full p-3 rounded-xl bg-white/5 outline outline-white/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70">
                </div>
                <div>
                    <label class="block text-[10px] uppercase tracking-widest text-zinc-500 font-bold mb-2 ml-1">Start</label>
                    <input type="time" id="edit_start_time" name="start_time" class="w-full p-3 rounded-xl bg-white/5 outline outline-white/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70">
                </div>
                <div>
                    <label class="block text-[10px] uppercase tracking-widest text-zinc-500 font-bold mb-2 ml-1">End</label>
                    <input type="time" id="edit_end_time" name="end_time" class="w-full p-3 rounded-xl bg-white/5 outline outline-white/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70">
                </div>
            </div>

            <div class="col-span-2">
                <label class="block text-[10px] uppercase tracking-widest text-zinc-500 font-bold mb-2 ml-1">About This Event</label>
                <textarea id="edit_description" name="description" rows="4" class="w-full p-3 rounded-lg bg-white/5 outline outline-white/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70"></textarea>
            </div>

            <div class="col-span-2">
                <label class="block text-[10px] uppercase tracking-widest text-zinc-500 font-bold mb-2 ml-1">Pricing (UGX)</label>
                <div class="grid grid-cols-3 gap-4">
                    <div class="space-y-2">
                        <input type="number" id="edit_regular_price" name="regular_price" placeholder="Reg. Price" class="p-3 rounded-xl bg-white/5 outline outline-white/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70">
                        <input type="number" id="edit_regular_quantity" name="regular_quantity" placeholder="Qty" class="p-3 rounded-xl bg-white/5 outline outline-white/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70">
                    </div>
                    <div class="space-y-2">
                        <input type="number" id="edit_vip_price" name="vip_price" placeholder="VIP Price" class="p-3 rounded-xl bg-white/5 outline outline-white/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70">
                        <input type="number" id="edit_vip_quantity" name="vip_quantity" placeholder="Qty" class="p-3 rounded-xl bg-white/5 outline outline-white/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70">
                    </div>
                    <div class="space-y-2">
                        <input type="number" id="edit_vvip_price" name="vvip_price" placeholder="VVIP Price" class="p-3 rounded-xl bg-white/5 outline outline-white/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70">
                        <input type="number" id="edit_vvip_quantity" name="vvip_quantity" placeholder="Qty" class="p-3 rounded-xl bg-white/5 outline outline-white/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70">
                    </div>
                </div>
            </div>

            <button class="col-span-2 w-full p-3 bg-black/80 border border-green-400/10 rounded-3xl font-mono text-white/70 text-center text-sm font-medium hover:bg-green-400/10 transition-all mt-4">
                Save Changes
            </button>
        </form>
    </div>
</div>

@push('before-alpine')
<script>
    // Alpine.js Trend Slider Component (Kept for future use if trends return)
    function trendSlider(trends) {
        return {
            trends: trends || []
            , currentIndex: 0
            , get currentTrend() {
                if (!this.trends.length) {
                    return {
                        id: 0
                        , title: 'No trends available'
                        , body: ''
                        , user_name: ''
                        , image: null
                    };
                }
                return this.trends[this.currentIndex];
            }
            , next() {
                if (!this.trends.length) return;
                this.currentIndex = (this.currentIndex + 1) % this.trends.length;
            }
            , prev() {
                if (!this.trends.length) return;
                this.currentIndex = (this.currentIndex - 1 + this.trends.length) % this.trends.length;
            }
        };
    }

</script>
@endpush
@push('scripts')
<script>
    // --- INFINITE SCROLL LOGIC ---
    let nextPageUrlEvents = "{{ $events->nextPageUrl() }}";
    const eventFeedContainer = document.getElementById('event-feed-container');
    const loadingTriggerEvents = document.getElementById('loading-trigger-events');
    const loaderEvents = document.getElementById('loader-events');
    const noMoreMsgEvents = document.getElementById('no-more-msg-events');
    let isLoadingEvents = false;

    const observerEvents = new IntersectionObserver((entries) => {
        if (entries[0].isIntersecting && nextPageUrlEvents && !isLoadingEvents) {
            loadMoreEvents();
        }
    }, {
        root: document.querySelector('main')
        , rootMargin: '100px'
        , threshold: 0.1
    });

    observerEvents.observe(loadingTriggerEvents);

    async function loadMoreEvents() {
        if (!nextPageUrlEvents) return;

        isLoadingEvents = true;
        loaderEvents.classList.remove('hidden');

        try {
            const url = new URL(nextPageUrlEvents);
            const searchParams = new URLSearchParams(window.location.search);
            if (searchParams.has('search')) {
                url.searchParams.set('search', searchParams.get('search'));
            }

            const response = await fetch(url.toString(), {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            const data = await response.json();

            if (data.html) {
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = data.html;
                while (tempDiv.firstChild) {
                    eventFeedContainer.appendChild(tempDiv.firstChild);
                }

                nextPageUrlEvents = data.next_page;

                if (!nextPageUrlEvents) {
                    loaderEvents.classList.add('hidden');
                    noMoreMsgEvents.classList.remove('hidden');
                }
            }
        } catch (error) {
            console.error('Error loading events:', error);
        } finally {
            isLoadingEvents = false;
        }
    }

    // --- SEARCH LOGIC ---
    // (Basic search if needed, but UI is removed as per request)

    // --- SAVE EVENT LOGIC ---
    async function toggleSaveEvent(eventId) {
        try {
            const response = await fetch(`/user/dashboard/event/${eventId}/save`, {
                method: 'POST'
                , headers: {
                    'Content-Type': 'application/json'
                    , 'X-CSRF-TOKEN': window.csrfToken
                }
            });
            const data = await response.json();
            if (data.status) {
                alert(data.status === 'saved' ? 'Event saved!' : 'Event removed from saved');
            }
        } catch (error) {
            console.error('Error saving event:', error);
        }
    }

    // --- MODAL LOGIC ---
    window.openEditModal = function(event) {
        document.getElementById("edit_name").value = event.event_name || "";
        document.getElementById("edit_category").value = event.category || "";
        document.getElementById("edit_location").value = event.location || "";
        document.getElementById("edit_venue").value = event.venue || "";
        document.getElementById("edit_event_date").value = event.event_date || "";
        document.getElementById("edit_start_time").value = event.start_time || "";
        document.getElementById("edit_end_time").value = event.end_time || "";
        document.getElementById("edit_description").value = event.description || "";
        document.getElementById("edit_regular_price").value = event.regular_price || "";
        document.getElementById("edit_regular_quantity").value = event.regular_quantity || "";
        document.getElementById("edit_vip_price").value = event.vip_price || "";
        document.getElementById("edit_vip_quantity").value = event.vip_quantity || "";
        document.getElementById("edit_vvip_price").value = event.vvip_price || "";
        document.getElementById("edit_vvip_quantity").value = event.vvip_quantity || "";

        document.getElementById("editForm").action = "/events/" + event.id;
        document.getElementById("editModal").classList.remove("hidden");
    }

    window.closeEditModal = function() {
        document.getElementById("editModal").classList.add("hidden");
    }
    // Organizer Follow Functionality
    $(document).on('click', '.organizer-follow-btn', function(e) {
        e.preventDefault();
        const btn = $(this);
        const organizerId = btn.data('organizer');

        fetch(`/organizer/${organizerId}/follow`, {
                method: 'POST'
                , headers: {
                    'X-CSRF-TOKEN': window.csrfToken
                    , 'Content-Type': 'application/json'
                }
            })
            .then(res => {
                if (res.status === 401) {
                    alert('Please log in to follow organizers.');
                    return null;
                }
                return res.json();
            })
            .then(data => {
                if (!data) return;
                if (data.status === 'followed') {
                    btn.text('Following').removeClass('bg-orange-400/80').addClass('bg-green-400/80');
                } else {
                    btn.text('Follow').removeClass('bg-green-400/80').addClass('bg-orange-400/80');
                }
            })
            .catch(err => console.error('Error:', err));
    });

</script>
@endpush
@endsection
