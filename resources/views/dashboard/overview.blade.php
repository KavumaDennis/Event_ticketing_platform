@extends('layouts.dashboard')

@section('title','Overview')

@section('content')
<div class="grid grid-cols-12 gap-6">
    <div class="col-span-8">
        <h2 class="text-xl text-orange-400/70 mb-2">Welcome, {{ $user->first_name ?? $user->name }}</h2>
        <h2 class="text-xl text-white/70 mb-2">Overview</h2>
        <div class="grid grid-cols-3 gap-5">
            <div class="bg-green-400/10 p-3 rounded-2xl flex flex-col gap-5">
                <div class="flex justify-between items-center">
                    <span class="bg-orange-400/70 p-2 rounded-[50%] h-fit w-fit flex items-center justify-center text-white/80">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-flag-icon lucide-flag">
                            <path d="M4 22V4a1 1 0 0 1 .4-.8A6 6 0 0 1 8 2c3 0 5 2 7.333 2q2 0 3.067-.8A1 1 0 0 1 20 4v10a1 1 0 0 1-.4.8A6 6 0 0 1 16 16c-3 0-5-2-8-2a6 6 0 0 0-4 1.528" /></svg>
                    </span>
                    <span class="text-white/70 text-sm font-medium">Events attended</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-white/70 font-mono font-medium ">20 events</span>
                    <a href="" class="text-xs text-black/90 font-medium font-mono bg-orange-400/70 rounded-3xl px-2 py-1">Details</a>
                </div>
            </div>
            <div class="bg-green-400/10 p-3 rounded-2xl flex flex-col gap-5">
                <div class="flex justify-between items-center">
                    <span class="bg-orange-400/70 p-2 rounded-[50%] h-fit w-fit flex items-center justify-center text-white/80">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trending-up-icon lucide-trending-up">
                            <path d="M16 7h6v6" />
                            <path d="m22 7-8.5 8.5-5-5L2 17" /></svg>
                    </span>
                    <span class="text-white/70 text-sm font-medium">Trends Created</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-white/70 font-mono font-medium ">20 trends</span>
                    <a href="" class="text-xs text-black/90 font-medium font-mono bg-orange-400/70 rounded-3xl px-2 py-1">Details</a>
                </div>
            </div>
            <div class="bg-green-400/10 p-3 rounded-2xl flex flex-col gap-5">
                <div class="flex justify-between items-center">
                    <span class="bg-orange-400/70 p-2 rounded-[50%] h-fit w-fit flex items-center justify-center text-white/80">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user-pen-icon lucide-user-pen">
                            <path d="M11.5 15H7a4 4 0 0 0-4 4v2" />
                            <path d="M21.378 16.626a1 1 0 0 0-3.004-3.004l-4.01 4.012a2 2 0 0 0-.506.854l-.837 2.87a.5.5 0 0 0 .62.62l2.87-.837a2 2 0 0 0 .854-.506z" />
                            <circle cx="10" cy="7" r="4" /></svg>
                    </span>
                    <span class="text-white/70 text-sm font-medium">Organizer Profile</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-white/70 font-mono font-medium ">Akavaako</span>
                    <a href="" class="text-xs text-black/90 font-medium font-mono bg-orange-400/70 rounded-3xl px-2 py-1">Details</a>
                </div>
            </div>
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
                                <div class="text-xs text-white/60">{{ $org->followers_count ?? $org->followers->count() }} followers</div>
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
                        <div class="font-medium text-sm">{{ $ev->event_name }}</div>
                        <div class="text-white/60 text-xs">{{ $ev->organizer?->business_name ?? 'Unknown' }}</div>
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
