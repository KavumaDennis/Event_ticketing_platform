@extends('layouts.dashboard')

@section('content')
<div class="px-6 pb-20">
    <div class="mb-12">
        <h1 class="text-4xl font-black text-white uppercase tracking-tighter">Your Wishlist</h1>
        <p class="text-zinc-500 text-sm">Save events you're interested in for later</p>
    </div>

    @if($saved->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($saved as $item)
        <div class="bg-zinc-900 border border-zinc-800 rounded-[2.5rem] overflow-hidden group hover:border-orange-500/30 transition-all duration-500">
            <div class="relative h-48 overflow-hidden">
                <img src="{{ $item->event->event_image ? asset('storage/'.$item->event->event_image) : asset('default.jpg') }}" 
                     class="w-full h-full object-cover grayscale group-hover:grayscale-0 group-hover:scale-110 transition-all duration-700">
                <div class="absolute inset-x-0 bottom-0 p-4 bg-gradient-to-t from-zinc-900 to-transparent">
                    <span class="px-3 py-1 bg-black/50 backdrop-blur-md text-white text-[10px] font-black uppercase tracking-widest rounded-full border border-white/10">
                        {{ \Carbon\Carbon::parse($item->event->event_date)->format('M d, Y') }}
                    </span>
                </div>
                {{-- Heart/Unsave Button could go here --}}
            </div>
            
            <div class="p-6">
                <h3 class="text-xl font-bold text-white mb-2 line-clamp-1 group-hover:text-orange-400 transition-colors">{{ $item->event->event_name }}</h3>
                <div class="flex items-center gap-2 text-zinc-500 text-xs mb-6">
                    <i class="fas fa-map-marker-alt text-orange-400"></i>
                    <span>{{ $item->event->venue ?? 'Main Arena, Kampala' }}</span>
                </div>

                <div class="flex gap-3">
                    <a href="{{ route('event.show', $item->event->id) }}" 
                       class="flex-1 py-3 bg-zinc-800 hover:bg-zinc-700 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest text-center transition-all">
                        Details
                    </a>
                    <a href="{{ route('event.show', $item->event->id) }}" 
                       class="flex-1 py-3 bg-orange-500 hover:bg-orange-400 text-black rounded-2xl text-[10px] font-black uppercase tracking-widest text-center transition-all shadow-lg shadow-orange-500/20">
                        Buy Tickets
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="py-24 bg-zinc-900/40 border border-dashed border-zinc-800 rounded-[3rem] text-center max-w-2xl mx-auto">
        <div class="size-20 bg-zinc-900 border border-zinc-800 rounded-3xl flex items-center justify-center mx-auto mb-6 text-zinc-700">
            <i class="far fa-heart text-3xl"></i>
        </div>
        <h3 class="text-white font-bold text-lg mb-2">Your wishlist is empty</h3>
        <p class="text-zinc-500 text-sm mb-8">Save events you don't want to miss and they'll appear here.</p>
        <a href="{{ route('home') }}" class="inline-block px-8 py-4 bg-orange-500 text-black font-black uppercase tracking-widest rounded-2xl hover:bg-orange-400 transition-all">Discover Events</a>
    </div>
    @endif
</div>
@endsection
