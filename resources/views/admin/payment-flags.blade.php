@extends('layouts.admin')

@section('title', 'Payment Review Queue')

@section('content')
<div class="bg-green-400/10 border border-zinc-800">
    <h3 class="text-lg font-bold text-white p-4">Flags & Chargebacks</h3>

    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-orange-400 text-black/90 font-mono text-xs uppercase font-medium">
                    <th class="px-6 py-4">Type</th>
                    <th class="px-6 py-4">Purchase</th>
                    <th class="px-6 py-4">Amount</th>
                    <th class="px-6 py-4">Reason</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Action</th>
                </tr>
            </thead>
            <tbody class="text-sm">
                @forelse($flags as $flag)
                    <tr class="border-b border-zinc-800/50 hover:bg-zinc-800/40 transition-colors">
                        <td class="py-3 px-2 text-white/80 uppercase">{{ $flag->type }}</td>
                        <td class="py-3 px-2 text-white/70">
                            #{{ $flag->purchase?->id }} · {{ $flag->purchase?->event?->event_name }}
                        </td>
                        <td class="py-3 px-2 text-green-400 font-medium">
                            {{ $flag->purchase?->currency ?? 'UGX' }} {{ number_format($flag->purchase?->total ?? 0, 2) }}
                        </td>
                        <td class="py-3 px-2 text-white/50 text-xs">{{ $flag->reason ?? '—' }}</td>
                        <td class="py-3 px-2">
                            <span class="px-2 py-1 rounded-full text-[10px] font-bold uppercase 
                                {{ $flag->status === 'open' ? 'bg-orange-400/20 text-orange-400' : '' }}
                                {{ $flag->status === 'reviewed' ? 'bg-blue-500/20 text-blue-400' : '' }}
                                {{ $flag->status === 'cleared' ? 'bg-green-500/20 text-green-400' : '' }}">
                                {{ $flag->status }}
                            </span>
                        </td>
                        <td class="py-3 px-2">
                            <form action="{{ route('admin.payment-flags.update', $flag->id) }}" method="POST" class="flex items-center gap-2">
                                @csrf
                                <select name="status" class="bg-black/50 border border-zinc-800 rounded-lg px-2 py-1 text-[10px] text-white/70">
                                    <option value="reviewed">Reviewed</option>
                                    <option value="cleared">Cleared</option>
                                </select>
                                <input type="text" name="admin_notes" placeholder="Notes" class="bg-black/50 border border-zinc-800 rounded-lg px-2 py-1 text-[10px] text-white/70">
                                <button class="px-2 py-1 bg-orange-400 text-black text-[10px] font-bold rounded-lg">Update</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-zinc-500">No flags found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($flags instanceof \Illuminate\Pagination\AbstractPaginator)
        <div class="mt-4">
            {{ $flags->links() }}
        </div>
    @endif
</div>
@endsection
