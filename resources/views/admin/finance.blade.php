@extends('layouts.admin')

@section('title', 'Finance & Orders')

@section('content')


    <div class="bg-green-400/10 border border-zinc-800  overflow-hidden">
        
        <div class="p-3 border-b border-zinc-800 flex justify-between items-center">
            <div>
                <h2 class="text-xl font-bold text-white uppercase tracking-tighter">Transaction History</h2>
                <p class="text-zinc-500 text-xs font-mono mt-1">Monitor and manage user feedback for events</p>
            </div>
            <div class="flex items-center gap-5">
                <h3 class="font-bold text-white">Transaction History</h3>
                <div
                class="bg-orange-400/10 px-4 py-1.5 rounded-lg border border-orange-400/20 text-orange-400 text-[10px] font-bold uppercase tracking-widest">
                Total UGX {{ number_format($totalRevenue, 2) }}
            </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-orange-400 text-black/90 font-mono text-xs uppercase font-medium">
                    <tr>
                        <th class="px-6 py-4">Transaction ID</th>
                        <th class="px-6 py-4">Customer</th>
                        <th class="px-6 py-4">Event</th>
                        <th class="px-6 py-4">Amount</th>
                        <th class="px-6 py-4">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y-3 divide-zinc-800">
                    @foreach ($purchases as $p)
                        <tr class="hover:bg-zinc-800/50 transition-colors">
                            <td class="px-6 py-4 font-mono text-xs text-zinc-500">
                                #TRX-{{ $p->id }}
                            </td>
                            <td class="px-6 py-4 flex items-center gap-3">
                                <div
                                    class="w-8 h-8 rounded-full bg-zinc-800 flex items-center justify-center text-xs font-bold text-zinc-400">
                                    {{ substr($p->user->first_name, 0, 1) }}
                                </div>
                                <span class="text-zinc-300 text-sm font-bold">{{ $p->user->first_name }} {{ $p->user->last_name }}</span>
                            </td>
                            <td class="px-6 py-4 text-zinc-400 text-sm font-bold">{{ $p->event->event_name }}</td>
                            <td class="px-6 py-4 text-green-400 font-mono font-medium">
                                {{ $p->currency ?? 'UGX' }} {{ number_format($p->total, 2) }}
                                @if (($p->base_currency ?? 'UGX') !== ($p->currency ?? 'UGX') && $p->total_base)
                                    <div class="text-[10px] text-white/40">Base: {{ $p->base_currency }}
                                        {{ number_format($p->total_base, 2) }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-zinc-500 text-sm font-mono">{{ $p->created_at->format('M d, Y H:i') }}</td>
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
