@extends('layouts.admin')

@section('title', 'Platform Promo Codes')

@section('content')
<div class="bg-green-400/10 overflow-hidden border border-zinc-800">
    <div class="p-4 border-b border-zinc-800 bg-zinc-900/50 flex justify-between items-center">
        <h3 class="font-bold text-white uppercase text-xs tracking-widest">Active & Historic Promo Codes</h3>
        <div class="text-[10px] text-zinc-500 uppercase tracking-tighter">Total Codes: {{ $promos->total() }}</div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-orange-400 text-black/90 font-mono text-xs uppercase font-medium">
                <tr>
                    <th class="px-6 py-4">Code</th>
                    <th class="px-6 py-4">Organizer</th>
                    <th class="px-6 py-4">Discount</th>
                    <th class="px-6 py-4">Usage</th>
                    <th class="px-6 py-4">Expires</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y-3 divide-zinc-800">
                @forelse($promos as $promo)
                <tr class="hover:bg-zinc-800/50 transition-colors">
                    <td class="px-6 py-4">
                        <span class="bg-orange-500/10 text-orange-400 font-mono font-bold px-2 py-1 rounded border border-orange-500/20">
                            {{ $promo->code }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm">
                        <div class="text-zinc-300 font-medium">{{ $promo->organizer->business_name }}</div>
                        <div class="text-[10px] text-zinc-500 lowercase">{{ $promo->organizer->business_email }}</div>
                    </td>
                    <td class="px-6 py-4 font-mono text-sm text-white">
                        {{ $promo->discount_type === 'percentage' ? $promo->discount_amount.'%' : 'UGX '.number_format($promo->discount_amount) }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-xs text-zinc-400">{{ $promo->used_count ?? 0 }} / {{ $promo->usage_limit ?? '∞' }}</div>
                        <div class="w-24 h-1 bg-zinc-800 rounded-full mt-1 overflow-hidden">
                            @php
                                $percent = $promo->usage_limit ? (($promo->used_count ?? 0) / $promo->usage_limit) * 100 : 0;
                            @endphp
                            <div class="h-full bg-orange-500" style="width: {{ min(100, $percent) }}%"></div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-zinc-500 text-xs">
                        {{ $promo->expires_at ? $promo->expires_at->format('M d, Y') : 'Never' }}
                    </td>
                    <td class="px-6 py-4">
                        @if($promo->status && (!$promo->expires_at || $promo->expires_at->isFuture()))
                            <span class="px-2 py-0.5 rounded-full bg-green-500/10 text-green-400 text-[10px] border border-green-500/20">Active</span>
                        @else
                            <span class="px-2 py-0.5 rounded-full bg-red-500/10 text-red-100 text-[10px] border border-red-500/20">Disabled</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <form action="{{ route('admin.promos.delete', $promo->id) }}" method="POST" onsubmit="return confirm('Kill this promo code platform-wide?')">
                            @csrf
                            @method('DELETE')
                            <button class="text-red-400 hover:text-red-300 text-xs font-mono tracking-tighter uppercase transition-colors">Terminate</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-zinc-600 italic">No promo codes found on the platform</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4 border-t border-zinc-800">
        {{ $promos->links() }}
    </div>
</div>
@endsection
