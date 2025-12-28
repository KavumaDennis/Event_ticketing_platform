@extends('layouts.dashboard')

@section('content')
<div class="px-6 pb-20">
    <div class="mb-12">
        <h1 class="text-4xl font-black text-white uppercase tracking-tighter">Notifications</h1>
        <p class="text-zinc-500 text-sm">Stay updated with your latest activities</p>
    </div>

    <div class="max-w-3xl space-y-4">
        @forelse($notifications as $notify)
        <div class="bg-zinc-900 border border-zinc-800 rounded-3xl p-6 flex gap-6 items-start hover:border-zinc-700 transition-all {{ $notify->read_at ? 'opacity-60' : 'border-l-4 border-l-orange-500' }}">
            <div class="size-12 rounded-2xl flex items-center justify-center shrink-0
                        {{ $notify->type === 'success' ? 'bg-green-500/10 text-green-500' : '' }}
                        {{ $notify->type === 'info' ? 'bg-blue-500/10 text-blue-500' : '' }}
                        {{ $notify->type === 'warning' ? 'bg-orange-500/10 text-orange-400' : '' }}">
                <i class="fas {{ $notify->type === 'success' ? 'fa-check-circle' : ($notify->type === 'warning' ? 'fa-exclamation-triangle' : 'fa-info-circle') }}"></i>
            </div>
            <div class="flex-1">
                <div class="flex justify-between items-start mb-1">
                    <h3 class="text-white font-bold">{{ $notify->title }}</h3>
                    <span class="text-[10px] text-zinc-600 font-bold uppercase">{{ $notify->created_at->diffForHumans() }}</span>
                </div>
                <p class="text-zinc-400 text-sm leading-relaxed">{{ $notify->message }}</p>
            </div>
        </div>
        @empty
        <div class="py-20 bg-zinc-900/40 border border-dashed border-zinc-800 rounded-[3rem] text-center">
            <i class="far fa-bell-slash text-4xl text-zinc-800 mb-4 block"></i>
            <p class="text-zinc-600 font-bold">No notifications yet.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
