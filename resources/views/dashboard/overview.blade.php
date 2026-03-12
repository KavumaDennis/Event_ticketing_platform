@extends('layouts.dashboard')

@section('title','Overview')

@section('content')
<div class="flex flex-col lg:grid lg:grid-cols-12 gap-6">
    <div class="col-span-12 lg:col-span-8">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-white uppercase tracking-tighter">Dashboard</h1>
                <p class="text-zinc-500 text-sm">Welcome back, {{ $user->first_name }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('user.dashboard.profile') }}" class="p-1 px-3 rounded-3xl text-xs bg-zinc-800 hover:bg-zinc-700 text-zinc-300 transition-all border border-zinc-700/50">Edit Profile</a>
                <a href="{{ route('user.dashboard.security') }}" class="p-1 px-3 rounded-3xl text-xs bg-green-500/10 hover:bg-green-500/20 text-green-400 transition-all border border-green-500/20">Security</a>
            </div>
        </div>




        @if($user->organizer)
        <h2 class="text-sm font-bold text-zinc-500 uppercase tracking-widest mb-2">Organizer Overview</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-5">
            <a href="{{ route('organizer.analytics') }}" class="bg-purple-500/10 border border-purple-500/20 p-4 rounded-2xl hover:bg-purple-500/20 transition group">
                <div class="flex justify-between items-center mb-2">
                    <div class="size-10 rounded-xl bg-purple-500/20 flex items-center justify-center text-purple-400 group-hover:scale-110 transition-transform">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <i class="fas fa-arrow-right text-purple-400/50 -rotate-45 group-hover:rotate-0 transition-transform"></i>
                </div>
                <h3 class="font-bold text-white text-lg">Analytics</h3>
                <p class="text-xs text-purple-200/50">View sales & performance</p>
            </a>

            <a href="{{ route('organizer.settings') }}" class="bg-orange-500/10 border border-orange-500/20 p-4 rounded-2xl hover:bg-orange-500/20 transition group">
                <div class="flex justify-between items-center mb-2">
                    <div class="size-10 rounded-xl bg-orange-500/20 flex items-center justify-center text-orange-400 group-hover:scale-110 transition-transform">
                        <i class="fas fa-cogs"></i>
                    </div>
                    <i class="fas fa-arrow-right text-orange-400/50 -rotate-45 group-hover:rotate-0 transition-transform"></i>
                </div>
                <h3 class="font-bold text-white text-lg">Settings</h3>
                <p class="text-xs text-orange-200/50">Manage page & promos</p>
            </a>

            <a href="{{ route('organizer.create') }}" class="bg-blue-500/10 border border-blue-500/20 p-4 rounded-2xl hover:bg-blue-500/20 transition group">
                <div class="flex justify-between items-center mb-2">
                    <div class="size-10 rounded-xl bg-blue-500/20 flex items-center justify-center text-blue-400 group-hover:scale-110 transition-transform">
                        <i class="fas fa-plus"></i>
                    </div>
                    <i class="fas fa-arrow-right text-blue-400/50 -rotate-45 group-hover:rotate-0 transition-transform"></i>
                </div>
                <h3 class="font-bold text-white text-lg">Create E...</h3>
                <p class="text-xs text-blue-200/50">Post a new event</p>
            </a>
        </div>
        @else
        <h2 class="text-sm font-bold text-zinc-500 uppercase tracking-widest mb-2">Account Stats</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-5">
            <div class="bg-green-400/10 p-3 rounded-2xl">
                <div class="flex justify-between items-center mb-4">
                    <div class="size-9 rounded-[50%] bg-orange-400/10 flex items-center justify-center text-orange-400">
                        <i class="fas fa-ticket-alt"></i>
                    </div>
                    <span class="text-sm font-bold text-white/70">Tickets</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="text-2xl font-mono font-medium text-white/70">{{ $user->tickets()->count() }}
                    </div>
                    <p class="text-xs text-orange-400/70 mt-1">Total purchased</p>
                </div>
            </div>

            <div class="bg-green-400/10 p-3 rounded-2xl">
                <div class="flex justify-between items-center mb-4">
                    <div class="size-9 rounded-[50%] bg-green-400/10 flex items-center justify-center text-green-400">
                        <i class="fas fa-bullhorn"></i>
                    </div>
                    <span class="text-sm font-bold text-white/70">Trends</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="text-2xl font-mono font-medium text-white/70">{{ $user->trends()->count() }}</div>
                    <p class="text-xs text-orange-400/70 mt-1">Posts created</p>
                </div>
            </div>

            <div class="bg-green-400/10 p-3 rounded-2xl">
                <div class="flex justify-between items-center mb-4">
                    <div class="size-9 rounded-[50%] bg-blue-400/10 flex items-center justify-center text-blue-400">
                        <i class="fas fa-user-friends"></i>
                    </div>
                    <span class="text-sm font-bold text-white/70">Following</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="text-2xl font-mono font-medium text-white/70">{{ $user->followedOrganizers()->count() }}</div>
                    <p class="text-xs text-orange-400/70 mt-1">Organizers followed</p>
                </div>
            </div>
        </div>
        @endif

        <h2 class="text-sm font-bold text-zinc-500 uppercase tracking-widest mb-2">Top Categories</h2>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 mb-5">
            @foreach($topCategories as $cat)
            @php
            $colors = ['bg-orange-500/10 border-orange-500/20 hover:bg-orange-500/20 text-orange-400', 'bg-blue-500/10 border-blue-500/20 hover:bg-blue-500/20 text-blue-400', 'bg-green-500/10 border-green-500/20 hover:bg-green-500/20 text-green-400', 'bg-purple-500/10 border-purple-500/20 hover:bg-purple-500/20 text-purple-400', 'bg-red-500/10 border-red-500/20 hover:bg-red-500/20 text-red-400', 'bg-pink-500/10 border-pink-500/20 hover:bg-pink-500/20 text-pink-400'];
            $color = $colors[$loop->index % count($colors)];
            @endphp
            <a href="{{ route('categories.show', $cat->category) }}" class="{{ $color }} p-2.5 rounded-2xl flex flex-col items-center justify-center gap-2 transition-transform border ">
                <div class="size-10 rounded-full bg-white/10 flex items-center justify-center">
                    <span class="font-bold text-lg capitalize">{{ substr($cat->category, 0, 1) }}</span>
                </div>
                <span class="text-xs font-bold uppercase tracking-tighter text-center line-clamp-1">{{ $cat->category }}</span>
                <span class="text-[10px] opacity-70 font-mono">{{ $cat->event_count }} events</span>
            </a>
            @endforeach
        </div>

        @include('partials.experiences-following', ['experienceUsers' => $experienceUsers, 'seenExperienceIds' => $seenExperienceIds])

        <h2 class="text-sm font-bold text-zinc-500 uppercase tracking-widest mb-2">My Recent Tickets</h2>
        <div class="grid grid-cols-2 gap-5 mb-5">
            @forelse($recentTickets as $ticket)
            <div class="flex flex-col gap-1">
                <div class="flex items-center gap-4 bg-green-400/10 border border-green-400/5 p-2.5 rounded-2xl relative overflow-hidden ">
                    <div class="size-10 rounded-xl bg-orange-400/20 flex items-center justify-center text-orange-400">
                        <i class="fas fa-ticket-alt"></i>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-medium text-white/80 line-clamp-1 mb-1">{{ $ticket->event->event_name }}</h4>
                        <p class="text-xs text-zinc-500 font-mono">#{{ $ticket->ticket_code }}</p>
                    </div>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-[10px] uppercase font-bold text-orange-400/60 ml-2 font-mono">{{ $ticket->ticket_type }}</span>
                    <a href="{{ route('dashboard.ticket.view', $ticket) }}" class="px-3 py-1.5 bg-orange-400 text-black text-center rounded-lg flex items-center justify-center gap-2 hover:text-white duration-150 transition-colors text-[10px] font-bold uppercase">View Qr-Code</a>
                </div>
            </div>
            @empty
            <div class="col-span-2 p-10 bg-zinc-900/40 border border-dashed border-zinc-800 rounded-3xl text-center">
                <p class="text-zinc-600 text-sm">No tickets found yet.</p>
                <a href="{{ route('events') }}" class="text-orange-500 text-xs mt-2 inline-block hover:underline">Browse Events</a>
            </div>
            @endforelse
        </div>


        {{-- Recommendations removed --}}
    </div>

    {{-- Right sidebar --}}
    <aside class="col-span-12 lg:col-span-4">
        <div class="mb-4" x-data="{ currentStep: 0, total: {{ count($followedOrganizers) }} }">
            <div class="flex justify-between items-center mb-2 ml-1">
                <h4 class="text-xs p-1 font-mono font-medium bg-orange-400 rounded-2xl w-fit text-black/90">Organizers you follow</h4>
                @if(count($followedOrganizers) > 1)
                <div class="flex gap-2">
                    <button @click="currentStep = (currentStep - 1 + total) % total" class="size-6.5 flex items-center justify-center rounded-full bg-black/40 border border-green-400/20 text-orange-400/70 hover:bg-black/60 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-big-left-dash-icon lucide-arrow-big-left-dash">
                            <path d="M13 9a1 1 0 0 1-1-1V5.061a1 1 0 0 0-1.811-.75l-6.835 6.836a1.207 1.207 0 0 0 0 1.707l6.835 6.835a1 1 0 0 0 1.811-.75V16a1 1 0 0 1 1-1h2a1 1 0 0 0 1-1v-4a1 1 0 0 0-1-1z" />
                            <path d="M20 9v6" /></svg>
                    </button>
                    <button @click="currentStep = (currentStep + 1) % total" class="size-6.5 flex items-center justify-center rounded-full bg-black/40 border border-green-400/20 text-orange-400/70 hover:bg-black/60 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-big-right-dash-icon lucide-arrow-big-right-dash">
                            <path d="M11 9a1 1 0 0 0 1-1V5.061a1 1 0 0 1 1.811-.75l6.836 6.836a1.207 1.207 0 0 1 0 1.707l-6.836 6.835a1 1 0 0 1-1.811-.75V16a1 1 0 0 0-1-1H9a1 1 0 0 1-1-1v-4a1 1 0 0 1 1-1z" />
                            <path d="M4 9v6" /></svg>
                    </button>
                </div>
                @endif
            </div>

            <div class="h-[220px] flex items-center justify-center overflow-hidden rounded-2xl relative">
                @if(count($followedOrganizers) > 0)
                @foreach($followedOrganizers as $index => $org)
                <div x-show="currentStep === {{ $index }}" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="w-full absolute inset-0 flex items-center justify-center  p-2 border border-green-400/5 bg-green-400/10 rounded-2xl ">
                    <div class="flex flex-col items-center justify-between w-full h-full text-center">
                        <div class="col-span-1 w-full gap-5 lg:w-full lg:gap-0 h-fit grid grid-cols-3">
                            <div class="col-span-2 flex flex-col w-full justify-center gap-3 items-center bg-green-400/10 border-green-400/15 rounded-xl">
                                <div class="border border-green-400/15  w-fit rounded-[50%] p-0.5 bg-orange-400/60">
                                    <img src="{{ $org->organizer_image ? asset('storage/'.$org->organizer_image) : asset('default.png') }}" alt="{{ $org->business_name }}" class='size-18 rounded-[50%]' alt="" />
                                </div>
                                <div class="">
                                    <p class="text-white/40 text-xs font-mono font-light text-center">Organizer</p>
                                    <h1 class="text-orange-400/80 font-medium text-center ">{{ $org->business_name }}</h1>
                                </div>
                            </div>
                            <div class="ml-5">
                                <div class="flex flex-col py-1">
                                    <h1 class="text-white text-start font-bold">{{ $org->events->count() }}</h1>
                                    <p class="text-white/60 text-sm flex items-center gap-1">
                                        <span class="text-orange-400/60">
                                            <i class="fa-solid fa-tent"></i>
                                        </span>
                                        <span>Events</span>
                                    </p>
                                </div>
                                <div class="flex flex-col py-1">
                                    <h1 class="text-white text-start font-bold">{{ $org->followers->count() }}</h1>

                                    <p class="text-white/60 text-sm flex items-center gap-1">
                                        <span class="text-orange-400/60">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user-pen-icon lucide-user-pen">
                                                <path d="M11.5 15H7a4 4 0 0 0-4 4v2" />
                                                <path d="M21.378 16.626a1 1 0 0 0-3.004-3.004l-4.01 4.012a2 2 0 0 0-.506.854l-.837 2.87a.5.5 0 0 0 .62.62l2.87-.837a2 2 0 0 0 .854-.506z" />
                                                <circle cx="10" cy="7" r="4" /></svg>
                                        </span>
                                        <span>Followers</span>
                                    </p>
                                </div>
                                <div class="flex flex-col py-1">
                                    <h1 class="text-white text-start font-bold">30</h1>
                                    <p class="text-white/60 text-sm flex items-center gap-1">
                                        <span class="text-orange-400/60">
                                            <i class="fa-solid fa-heart"></i>
                                        </span>
                                        <span>Likes</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('organizer.details', $org->id) }}" class="w-full px-3 py-2.5 bg-orange-400 text-black text-center rounded-lg flex items-center justify-center gap-2 hover:text-white duration-150 transition-colors text-[10px] font-bold uppercase">
                            View Details
                        </a>
                    </div>
                </div>

                @endforeach
                @else
                <div class="flex flex-col items-center justify-center text-center p-5 opacity-40">
                    <i class="fas fa-user-friends text-4xl mb-3 text-zinc-600"></i>
                    <p class="text-sm text-zinc-500">Not following any organizers yet</p>
                </div>
                @endif
            </div>
        </div>


        <div x-data="trendsCarousel({{ count($randomTrends) }})">
            <h4 class="text-xs p-1 font-mono font-medium bg-orange-400 rounded-2xl w-fit text-black/90 mb-2">Trending Now</h4>

            <div class="h-60 overflow-hidden rounded-2xl relative group">
                @if(count($randomTrends) > 0)
                <div class="absolute inset-0 flex transition-transform duration-500 ease-out" :style="'transform: translateX(-' + (currentIndex * 100) + '%)'">
                    @foreach($randomTrends as $trend)
                    <div class="min-w-full h-full relative">
                        {{-- Background Media --}}
                        @if($trend->is_video)
                        <video src="{{ $trend->first_media_url }}" class="w-full h-full object-cover opacity-60" autoplay muted loop playsinline></video>
                        @else
                        <img src="{{ $trend->first_media_url }}" class="w-full h-full object-cover opacity-60" alt="{{ $trend->title }}">
                        @endif

                        {{-- Overlay Gradient --}}
                        <div class="absolute inset-0 bg-gradient-to-t from-black via-black/50 to-transparent"></div>

                        {{-- Content --}}
                        <div class="absolute bottom-0 left-0 w-full p-4">
                            <div class="flex items-center gap-2 mb-2">
                                <img src="{{ $trend->user->profile_pic ? asset('storage/'.$trend->user->profile_pic) : asset('default.png') }}" class="size-6 rounded-full border border-orange-400/30">
                                <span class="text-xs text-orange-400 font-bold truncate">{{ $trend->user->first_name }}</span>
                            </div>
                            <h3 class="text-white font-bold text-lg leading-tight mb-2 line-clamp-2">{{ $trend->title }}</h3>
                            <a href="{{ route('trends.show', $trend->id) }}" class="px-3 py-1.5 w-fit bg-orange-400 text-black text-center rounded-lg flex items-center justify-center gap-2 hover:text-white duration-150 transition-colors text-[10px] font-bold uppercase">
                                Watch Trend
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="absolute left-2 top-2 flex gap-3 items-center">
                    {{-- Controls --}}
                    <button @click="prev()" class="size-8 bg-black/60 border border-white/20 text-white rounded-full flex items-center justify-center hover:bg-orange-400 hover:text-black transition-colors backdrop-blur-lg opacity-0 group-hover:opacity-100">
                        <i class="fas fa-chevron-left text-xs"></i>
                    </button>
                    <button @click="next()" class="size-8 bg-black/60 border border-white/20 text-white rounded-full flex items-center justify-center hover:bg-orange-400 hover:text-black transition-colors backdrop-blur-lg opacity-0 group-hover:opacity-100">
                        <i class="fas fa-chevron-right text-xs"></i>
                    </button>
                </div>


                {{-- Indicators --}}
                <div class="absolute top-2 right-2 flex gap-1">
                    @foreach($randomTrends as $index => $trend)
                    <div class="h-1 rounded-full transition-all duration-300" :class="currentIndex === {{ $index }} ? 'w-4 bg-orange-400' : 'w-1.5 bg-white/30'"></div>
                    @endforeach
                </div>
                @else
                <div class="flex flex-col items-center justify-center h-full bg-green-400/10 text-center p-5">
                    <i class="fas fa-fire text-3xl mb-2 text-zinc-600"></i>
                    <p class="text-xs text-zinc-500 font-mono">No trends right now.</p>
                </div>
                @endif
            </div>
        </div>

        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('trendsCarousel', (total) => ({
                    currentIndex: 0
                    , total: total
                    , interval: null,

                    init() {
                        this.startAutoplay();
                    },

                    next() {
                        this.currentIndex = (this.currentIndex + 1) % this.total;
                        this.resetAutoplay();
                    },

                    prev() {
                        this.currentIndex = (this.currentIndex - 1 + this.total) % this.total;
                        this.resetAutoplay();
                    },

                    startAutoplay() {
                        if (this.total > 1) {
                            this.interval = setInterval(() => {
                                this.currentIndex = (this.currentIndex + 1) % this.total;
                            }, 5000);
                        }
                    },

                    resetAutoplay() {
                        clearInterval(this.interval);
                        this.startAutoplay();
                    }
                }));
            });

        </script>
    </aside>
</div>
@endsection

@section('scripts')
<script>
    document.querySelectorAll('.like-trend-btn').forEach(btn => {
        btn.addEventListener('click', async function() {
            const id = this.dataset.id;
            const res = await postJSON(`/trend/${id}/like`);
            if (res.likes_count !== undefined) this.textContent = res.likes_count + ' likes';
        });
    });
    document.querySelectorAll('.follow-org-btn').forEach(btn => {
        btn.addEventListener('click', async function() {
            const id = this.dataset.id;
            const res = await postJSON(`/organizer/${id}/follow`);
            if (res.status) location.reload();
        });
    });

</script>
@endsection
