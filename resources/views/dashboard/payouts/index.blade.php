@extends('layouts.dashboard')

@section('title', 'Payouts & Earnings')

@section('content')
<div class="max-w-7xl mx-auto p-6">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Balance Card -->
        <div class="lg:col-span-1">
            <div class="bg-orange-400 p-8 rounded-[2rem] text-black shadow-xl">
                <h2 class="text-xs font-mono uppercase tracking-widest opacity-60 mb-2">Available Balance</h2>
                <p class="text-4xl font-black tracking-tighter mb-8">UGX {{ number_format($balance) }}</p>
                
                <form action="{{ route('organizer.payouts.request') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-[10px] uppercase font-bold mb-1 opacity-60">Withdraw Amount</label>
                        <input type="number" name="amount" min="5000" max="{{ $balance }}" 
                               class="w-full bg-black/10 border-none rounded-xl p-3 text-sm font-bold focus:ring-2 focus:ring-black/20" 
                               placeholder="Min. UGX 5,000" required>
                    </div>
                    <div>
                        <label class="block text-[10px] uppercase font-bold mb-1 opacity-60">Method</label>
                        <select name="payment_method" class="w-full bg-black/10 border-none rounded-xl p-3 text-sm font-bold focus:ring-2 focus:ring-black/20">
                            <option value="momo">Mobile Money</option>
                            <option value="bank">Bank Transfer</option>
                        </select>
                    </div>
                    <button type="submit" class="w-full py-4 bg-black text-orange-400 font-bold rounded-xl hover:bg-zinc-900 transition shadow-lg" 
                            {{ $balance < 5000 ? 'disabled' : '' }}>
                        Request Payout
                    </button>
                    @if($balance < 5000)
                        <p class="text-[10px] text-center opacity-60">Minimum withdraw: UGX 5,000</p>
                    @endif
                </form>
            </div>
        </div>

        <!-- History Table -->
        <div class="lg:col-span-2">
            <div class="bg-green-400/10 border border-green-400/10 rounded-[2rem] p-8">
                <h3 class="text-white font-bold text-xl mb-6">Payout History</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-[10px] uppercase tracking-widest text-zinc-500 border-b border-white/5">
                                <th class="pb-4">Date</th>
                                <th class="pb-4">Amount</th>
                                <th class="pb-4">Method</th>
                                <th class="pb-4">Status</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            @forelse($payoutRequests as $request)
                            <tr class="border-b border-white/5 hover:bg-white/5 transition-colors">
                                <td class="py-4 text-white/60">{{ $request->created_at->format('M d, Y') }}</td>
                                <td class="py-4 text-white font-mono">UGX {{ number_format($request->amount) }}</td>
                                <td class="py-4 text-white/60 uppercase">{{ $request->payment_method }}</td>
                                <td class="py-4">
                                    <span class="px-2 py-1 rounded-full text-[10px] font-bold uppercase 
                                        {{ $request->status === 'completed' ? 'bg-green-500/20 text-green-400' : '' }}
                                        {{ $request->status === 'pending' ? 'bg-orange-400/20 text-orange-400' : '' }}
                                        {{ $request->status === 'rejected' ? 'bg-red-500/20 text-red-400' : '' }}">
                                        {{ $request->status }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="py-10 text-center text-zinc-600 italic">No payout history yet.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
