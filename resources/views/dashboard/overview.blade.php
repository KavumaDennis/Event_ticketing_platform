@extends('layouts.dashboard')

@section('title','Overview')

@section('content')
<div class="grid grid-cols-12 gap-6">
    <div class="col-span-8">
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

        <h2 class="text-sm font-bold text-zinc-500 uppercase tracking-widest mb-4">Account Stats</h2>
        <div class="grid grid-cols-3 gap-5 mb-10">
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

        {{-- Recommendations --}}
        <h2 class="text-sm font-bold text-zinc-500 uppercase tracking-widest mb-4">Recommended for you</h2>
        <div class="grid grid-cols-2 gap-4">
            @foreach($recommendations as $event)
            <div class="bg-green-400/10 border border-green-400/10 rounded-3xl overflow-hidden group hover:border-orange-400/20 transition-all">
                <div class="flex p-3 gap-4">
                    <img src="{{ $event->event_image ? asset('storage/'.$event->event_image) : asset('default.jpg') }}" class="size-20 rounded-2xl object-cover grayscale group-hover:grayscale-0 transition-all duration-500">
                    <div class="flex-1">
                        <h4 class="font-medium text-sm text-white/80 line-clamp-1">{{ $event->event_name }}</h4>
                        <p class="text-xs text-zinc-500 font-mono mt-1"><i class="far fa-calendar-alt mr-1"></i> {{ \Carbon\Carbon::parse($event->event_date)->format('M d') }}</p>
                        <a href="{{ route('event.show', $event->id) }}" class="text-[10px] uppercase font-bold text-orange-400 mt-2 block hover:translate-x-1 transition-transform">Get Tickets <i class="fas fa-arrow-right ml-1"></i></a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Right sidebar --}}
    <aside class="col-span-4">
        <div class="p-4 border-green-400/20 bg-green-400/10 rounded-3xl mb-4">
            <h4 class="font-semibold text-orange-400/70 text-sm mb-3">Organizers you follow</h4>
            <div class=" h-[300px] overflow-hidden overflow-y-scroll rounded-2xl">
                @if($followedOrganizers)
                <div class="flex flex-col gap-3 justify-center">
                    @foreach($followedOrganizers as $org)
                    <div class="flex items-center gap-5 p-2 border border-green-400/20 bg-green-400/10 rounded-2xl">
                        <div class="border border-green-400/15  w-fit rounded-[50%]">
                            <img src="{{ $org->organizer_image ? asset('storage/'.$org->organizer_image) : asset('default.jpg') }}" alt="{{ $org->business_name }}" class='size-13 rounded-[50%]' alt="" />
                        </div>
                        <div class="flex items-center justify-between w-4/5">
                            <div>
                                <p class="font-medium text-sm text-white/80">{{ $org->business_name }}</p>
                                <div class="text-xs text-white/60 font-mono">{{ $org->followers_count ?? $org->followers->count() }} followers</div>
                            </div>
                            <a href="{{ route('organizer.details', $org->id) }}" class="text-xs text-black/90 font-medium font-mono bg-orange-400/70 rounded-3xl px-2 py-1">Details</a>
                        </div>
                    </div>
                    @endforeach

                </div>

                @else
                <p class="pt-3 border border-t-white/70 text-sm text-white/70">
                    Not following any organizers
                </p>
                @endif
            </div>
        </div>


        <div class="p-4 border-green-400/20 bg-green-400/10  rounded-3xl ">
            <h4 class="font-semibold text-orange-400/70 text-sm mb-3">Saved events</h4>
            <div class="h-[300px] overflow-hidden overflow-y-scroll flex flex-col gap-3 rounded-2xl">
                @foreach($saved as $s)
                @php $ev = $s->event; @endphp
                <div class="flex border border-green-400/20 bg-green-400/10 rounded-2xl p-2 relative">
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
