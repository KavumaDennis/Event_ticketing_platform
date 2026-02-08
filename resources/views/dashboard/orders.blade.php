@extends('layouts.dashboard')

@section('title', 'My Orders')

@section('content')
<div class="pl-6 pb-20">
    <div class="mb-5">
        <h1 class="text-2xl text-white/70">Orders & Payments</h1>
        <p class="text-orange-400/70 font-mono text-sm">Track your transaction history and download receipts</p>
    </div>

    <div class="space-y-4">
        @forelse ($orders as $order)
        <div class="bg-green-400/10 border border-green-400/10 rounded-3xl p-3 hover:border-zinc-700 transition-all group">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                <div class="flex items-center gap-6">
                    <div class="size-16 bg-zinc-800 rounded-2xl flex items-center justify-center text-zinc-500 group-hover:bg-orange-500/10 group-hover:text-orange-400 transition-colors">
                        <i class="fas fa-receipt text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-medium text-white/80 group-hover:text-orange-400 transition-colors">
                            {{ $order->event->event_name }}
                        </h2>
                        <div class="flex gap-4 mt-1 text-xs font-mono text-zinc-500 font-medium">
                            <span><i class="far fa-calendar-alt mr-1"></i> {{ $order->created_at->format('M d, Y') }}</span>
                            <span><i class="fas fa-fingerprint mr-1"></i> REF: {{ $order->reference_id }}</span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-4 lg:gap-8 h-full">
                    <div class="text-right flex flex-col justify-between h-full">
                        <p class="text-[10px] uppercase font-black text-zinc-600 tracking-widest mb-1">Status</p>
                        <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest
                                    {{ $order->status === 'paid' ? 'bg-green-500/10 text-green-500' : '' }}
                                    {{ $order->status === 'pending' ? 'bg-yellow-500/10 text-yellow-500' : '' }}
                                    {{ $order->status === 'failed' ? 'bg-red-500/10 text-red-500' : '' }}">
                            {{ $order->status }}
                        </span>
                    </div>

                    <div class="text-right flex flex-col justify-between h-full">
                        <p class="text-[10px] uppercase font-black text-zinc-600 tracking-widest mb-1">Tickets</p>
                        <p class="text-white/80 font-medium">{{ $order->quantity }} x {{ $order->ticket_type ?? 'Standard' }}</p>
                    </div>

                    <div class="text-right flex flex-col justify-between h-full lg:min-w-[120px]">
                        <p class="text-[10px] uppercase font-black text-zinc-600 tracking-widest mb-1">Total</p>
                        <p class="text-orange-400 font-medium text-lg uppercase tracking-tighter">UGX {{ number_format($order->total) }}</p>
                    </div>

                    <div class="flex gap-2">
                        @if($order->status === 'paid')
                        <a href="{{ route('user.dashboard.tickets') }}" class="px-2 py-2 flex items-center bg-green-400/10 border border-green-400/10 hover:bg-green-400/20 text-white rounded-xl text-[10px] font-bold uppercase tracking-widest transition-all">
                            View Tickets
                        </a>
                        {{-- <button onclick="alert('Receipt generation is being finalized!')" class="size-10 bg-green-500/10 text-green-500 rounded-xl flex items-center justify-center hover:bg-green-500/20 transition-all border border-green-500/20">
                            <i class="fas fa-download"></i>
                        </button> --}}
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="p-8 bg-green-400/5 border border-dashed border-green-400/20 rounded-3xl text-center">
            <i class="fas fa-history text-4xl text-zinc-800 mb-4 block"></i>
            <p class="text-zinc-600 font-bold">No order history found.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection

