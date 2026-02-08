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
                    <div class="text-2xl font-mono font-medium text-white/70">{{ $user->tickets()->count() }}</div>
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

        <h2 class="text-sm font-bold text-zinc-500 uppercase tracking-widest mb-2">Top Categories</h2>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 mb-5">
            @foreach($topCategories as $cat)
            @php
                $colors = ['bg-orange-500/10 text-orange-400', 'bg-blue-500/10 text-blue-400', 'bg-green-500/10 text-green-400', 'bg-purple-500/10 text-purple-400', 'bg-red-500/10 text-red-400', 'bg-pink-500/10 text-pink-400'];
                $color = $colors[$loop->index % count($colors)];
            @endphp
            <a href="{{ route('categories.show', $cat->category) }}" class="{{ $color }} p-2.5 rounded-2xl flex flex-col items-center justify-center gap-2 hover:scale-105 transition-transform border border-white/5">
                <div class="size-10 rounded-full bg-white/10 flex items-center justify-center">
                    <span class="font-bold text-lg capitalize">{{ substr($cat->category, 0, 1) }}</span>
                </div>
                <span class="text-xs font-bold uppercase tracking-tighter text-center line-clamp-1">{{ $cat->category }}</span>
                <span class="text-[10px] opacity-70 font-mono">{{ $cat->event_count }} events</span>
            </a>
            @endforeach
        </div>

        <h2 class="text-sm font-bold text-zinc-500 uppercase tracking-widest mb-2">My Recent Tickets</h2>
        <div class="grid grid-cols-2 gap-5 mb-5">
            @forelse($recentTickets as $ticket)
            <div class="bg-green-400/10 border border-green-400/5 p-2.5 rounded-2xl relative overflow-hidden group">
                <div class="flex items-center gap-4">
                    <div class="size-10 rounded-xl bg-orange-400/20 flex items-center justify-center text-orange-400">
                        <i class="fas fa-ticket-alt"></i>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-medium text-white/80 line-clamp-1">{{ $ticket->event->event_name }}</h4>
                        <p class="text-xs text-zinc-500 font-mono">#{{ $ticket->ticket_code }}</p>
                    </div>
                </div>
                <div class="mt-4 flex justify-between items-center">
                    <span class="text-[10px] uppercase font-bold text-orange-400/60 self-end">{{ $ticket->ticket_type }}</span>
                    <a href="{{ route('dashboard.ticket.view', $ticket) }}" class="bg-green-400/10 border border-green-400/20 text-xs rounded-2xl p-0.5 px-1 text-orange-400/50 font-mono w-fit">View Qr <i class="fas fa-chevron-right text-xs"></i></a>
                </div>
            </div>
            @empty
            <div class="col-span-2 p-10 bg-zinc-900/40 border border-dashed border-zinc-800 rounded-3xl text-center">
                <p class="text-zinc-600 text-sm">No tickets found yet.</p>
                <a href="{{ route('events') }}" class="text-orange-500 text-xs mt-2 inline-block hover:underline">Browse Events</a>
            </div>
            @endforelse
        </div>


        {{-- Recommendations --}}
        {{-- <h2 class="text-sm font-bold text-zinc-500 uppercase tracking-widest mb-4">Recommended for you</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($recommendations as $event)
            <div class="bg-green-400/10 border border-green-400/10 rounded-2xl overflow-hidden group hover:border-orange-400/20 transition-all">
                <div class="flex p-2.5 gap-4 h-fit">
                    <img src="{{ $event->event_image ? asset('storage/'.$event->event_image) : asset('default.jpg') }}" class="size-15 rounded-xl object-cover grayscale group-hover:grayscale-0 transition-all duration-500">
                    <div class="flex-1">
                        <h4 class="font-medium text-sm text-white/80 line-clamp-1">{{ $event->event_name }}</h4>
                        <p class="text-xs text-zinc-500 font-mono mt-1"><i class="far fa-calendar-alt mr-1"></i> {{ \Carbon\Carbon::parse($event->event_date)->format('M d') }}</p>
                        <a href="{{ route('event.show', $event->id) }}" class="text-[10px] uppercase font-bold text-orange-400 mt-2 block hover:translate-x-1 transition-transform">Get Tickets <i class="fas fa-arrow-right ml-1"></i></a>
                    </div>
                </div>
            </div>
            @endforeach
        </div> --}}
    </div>

    {{-- Right sidebar --}}
    <aside class="col-span-12 lg:col-span-4">
        <div class="p-3 border border-green-400/5 bg-green-400/10 rounded-3xl mb-4" x-data="{ currentStep: 0, total: {{ count($followedOrganizers) }} }">
            <div class="flex justify-between items-center mb-2 ml-1">
                <h4 class="font-semibold text-orange-400/70 text-sm">Organizers you follow</h4>
                @if(count($followedOrganizers) > 1)
                <div class="flex gap-2">
                    <button @click="currentStep = (currentStep - 1 + total) % total" class="size-6 flex items-center justify-center rounded-full bg-black/40 text-orange-400/70 hover:bg-black/60 transition">
                        <i class="fas fa-chevron-left text-[10px]"></i>
                    </button>
                    <button @click="currentStep = (currentStep + 1) % total" class="size-6 flex items-center justify-center rounded-full bg-black/40 text-orange-400/70 hover:bg-black/60 transition">
                        <i class="fas fa-chevron-right text-[10px]"></i>
                    </button>
                </div>
                @endif
            </div>

            <div class="h-[200px] flex items-center justify-center overflow-hidden rounded-2xl relative">
                @if(count($followedOrganizers) > 0)
                @foreach($followedOrganizers as $index => $org)
                <div x-show="currentStep === {{ $index }}" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="w-full absolute inset-0 flex items-center justify-center  p-2 border border-green-400/5 bg-green-400/10 rounded-2xl ">
                    <div class="flex flex-col items-center justify-between gap-2 w-full h-full text-center">
                        <div class="p-1 bg-orange-400/20 border border-orange-400/30 rounded-full shadow-lg shadow-orange-400/5">
                            <img src="{{ $org->organizer_image ? asset('storage/'.$org->organizer_image) : asset('default.jpg') }}" alt="{{ $org->business_name }}" class="size-15 rounded-full object-cover" />
                        </div>
                        <div class="flex flex-col items-center">
                            <p class="font-semibold text-white/90">{{ $org->business_name }}</p>
                            <div class="text-xs text-orange-400/60 font-mono bg-orange-400/5 px-3 py-0.5 rounded-full border border-orange-400/10">
                                {{ $org->followers_count ?? $org->followers->count() }} followers
                            </div>
                        </div>
                        <a href="{{ route('organizer.details', $org->id) }}" class="w-full text-sm text-black/90 font-semibold font-mono bg-orange-400/70 hover:bg-orange-400 transition-colors rounded-3xl py-2 shadow-lg shadow-orange-400/10">
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


        <div class="p-3 pr-1 border border-green-400/5 bg-green-400/10  rounded-2xl ">
            <h4 class="font-semibold text-orange-400/70 text-sm mb-2">Saved events</h4>
            <div class="h-[300px] overflow-hidden overflow-y-scroll flex flex-col gap-3 rounded-2xl pr-2">
                @foreach($saved as $s)
                @php $ev = $s->event; @endphp
                <div class="flex bg-green-400/10 rounded-2xl p-2 relative">
                    <div class="pb-5">
                        <div class="font-medium text-white/80 text-sm">{{ $ev->event_name }}</div>
                        <div class="text-white/60 text-xs font-mono">{{ $ev->organizer?->business_name ?? 'Unknown' }}</div>
                    </div>
                    <div class="flex items-center p-0.5 w-fit bg-orange-400/70 gap-1 rounded-3xl absolute bottom-2 right-2">
                        <a href="{{ route('event.show', $ev->id) }}" class='flex gap-1 items-center'>
                            <span class='p-2 rounded-[50%] bg-black/95 text-orange-400/80'>
                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-ticket-icon lucide-ticket">
                                    <path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z" />
                                    <path d="M13 5v2" />
                                    <path d="M13 17v2" />
                                    <path d="M13 11v2" />
                                </svg>
                            </span>
                            <span class='text-xs pr-2 font-semibold text-black/90'>Get Tickets</span>
                        </a>
                    </div>
                </div>

                @endforeach
            </div>
        </div>
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
