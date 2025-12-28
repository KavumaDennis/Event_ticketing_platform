@extends('layouts.admin')

@section('title', 'Finance & Orders')

@section('content')

{{-- REVENUE CARD --}}
<div class="bg-green-400/10 rounded-2xl p-6 mb-8 flex items-center justify-between">
    <div>
        <h3 class="text-zinc-400 font-medium mb-1">Total Lifetime Revenue</h3>
        <p class="text-3xl font-medium font-mono text-white/80">UGX {{ number_format($totalRevenue) }}</p>
    </div>
    <div class="p-4 bg-green-500/10 rounded-full text-green-400">
        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-wallet"><path d="M20 12V8H6a2 2 0 0 1-2-2c0-1.1.9-2 2-2h12v4"/><path d="M4 6v12c0 1.1.9 2 2 2h14v-4"/><path d="M18 12a2 2 0 0 0-2 2c0 1.1.9 2 2 2h4v-4h-4z"/></svg>
    </div>
</div>

<div class="bg-green-400/10  rounded-2xl overflow-hidden">
    <div class="p-4">
        <h3 class="font-bold text-white">Transaction History</h3>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-orange-400/70 text-black/90 font-mono text-xs uppercase font-medium">
                <tr>
                    <th class="px-6 py-4">Transaction ID</th>
                    <th class="px-6 py-4">Customer</th>
                    <th class="px-6 py-4">Event</th>
                    <th class="px-6 py-4">Amount</th>
                    <th class="px-6 py-4">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-800">
                @foreach($purchases as $p)
                <tr class="hover:bg-zinc-800/50 transition-colors">
                    <td class="px-6 py-4 font-mono text-xs text-zinc-500">
                        #TRX-{{ $p->id }}
                    </td>
                    <td class="px-6 py-4 flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-zinc-800 flex items-center justify-center text-xs font-bold text-zinc-400">
                            {{ substr($p->user->first_name, 0, 1) }}
                        </div>
                        <span class="text-zinc-300">{{ $p->user->first_name }} {{ $p->user->last_name }}</span>
                    </td>
                    <td class="px-6 py-4 text-zinc-400">{{ $p->event->event_name }}</td>
                    <td class="px-6 py-4 text-green-400 font-medium">UGX {{ number_format($p->total) }}</td>
                    <td class="px-6 py-4 text-zinc-500 text-sm">{{ $p->created_at->format('M d, Y H:i') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="p-4 border-t border-zinc-800">
        {{ $purchases->links() }}
    </div>
</div>
@endsection
