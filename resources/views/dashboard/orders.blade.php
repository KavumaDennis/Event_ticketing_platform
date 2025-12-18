@extends('layouts.dashboard')

@section('title','Overview')

@section('content')
<div class="px-6">
    <h1 class="text-2xl font-semibold text-white mb-6">
        My Orders
    </h1>

    <div class="grid grid-cols-2 gap-5">
        @forelse ($orders as $order)
        <div class="bg-green-400/10 border border-green-400/10 rounded-2xl p-3">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-lg text-white">
                        {{ $order->event->event_name }}
                    </h2>
                    <p class="text-sm text-white/50">
                        Ref: {{ $order->reference_id }}
                    </p>
                </div>

                <span class="px-2 py-0.5 rounded-lg text-sm font-mono
                            {{ $order->status === 'paid' ? 'bg-green-500/20 text-green-400' : '' }}
                            {{ $order->status === 'pending' ? 'bg-yellow-500/20 text-yellow-400' : '' }}
                            {{ $order->status === 'failed' ? 'bg-red-500/20 text-red-400' : '' }}">
                    {{ strtoupper($order->status) }}
                </span>
            </div>

            <div class="flex justify-between items-center mt-7">
                <div class="flex gap-5 items-center text-sm text-white/60">
                    <span class="flex gap-5 items-center">
                        Tickets: {{ $order->quantity }}
                    </span>
                    @if($order->status === 'paid')
                    <div class="">
                        <a href="{{ route('user.dashboard.tickets') }}" class="text-xs text-black/90 font-medium font-mono bg-orange-400/70 text-center rounded-3xl p-2 w-full">
                            View Tickets
                        </a>
                    </div>
                    @endif
                </div>
                <span class="text-orange-400/80 text-sm font-semibold">
                    Total: UGX {{ number_format($order->amount) }}
                </span>


            </div>


        </div>
        @empty
        <p class="text-white/50">You have no orders yet.</p>
        @endforelse
    </div>
</div>
@endsection
