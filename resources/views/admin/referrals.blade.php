@extends('layouts.admin')

@section('title', 'Referral & Affiliate Monitoring')

@section('content')
<div class="space-y-6">
    {{-- Referral Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-green-400/10 p-6 rounded-2xl border border-white/5">
            <div class="text-zinc-500 text-sm mb-1 uppercase tracking-wider">Total Referrals</div>
            <div class="text-2xl font-bold text-orange-400">{{ $referrals->total() }}</div>
            <div class="text-xs text-zinc-600 mt-2">Successful platform registrations</div>
        </div>
        <div class="bg-green-400/10 p-6 rounded-2xl border border-white/5">
            <div class="text-zinc-500 text-sm mb-1 uppercase tracking-wider">Commissions Paid</div>
            <div class="text-2xl font-bold text-green-400">UGX {{ number_format($totalEarned) }}</div>
            <div class="text-xs text-zinc-600 mt-2">Total affiliate earnings</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Total Referrals List --}}
        <div class="lg:col-span-2 bg-green-400/10 rounded-2xl overflow-hidden border border-white/5">
            <div class="p-4 border-b border-zinc-800 bg-zinc-900/50">
                <h3 class="font-bold text-white uppercase text-xs tracking-widest">Recent Referral Activity</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left font-sans">
                    <thead class="text-zinc-500 text-[10px] uppercase font-bold tracking-tighter border-b border-zinc-800">
                        <tr>
                            <th class="px-6 py-3">Referrer</th>
                            <th class="px-6 py-3">Referred User</th>
                            <th class="px-6 py-3">Commission</th>
                            <th class="px-6 py-3">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-800">
                        @forelse($referrals as $ref)
                        <tr class="hover:bg-zinc-800/30 transition-colors">
                            <td class="px-6 py-4">
                                <div class="text-white text-sm font-medium">{{ $ref->referrer->username }}</div>
                                <div class="text-[10px] text-zinc-500">{{ $ref->referrer->email }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-zinc-300 text-sm">{{ $ref->referredUser->username }}</div>
                            </td>
                            <td class="px-6 py-4 text-green-400 font-mono text-sm uppercaseTracking">
                                UGX {{ number_format($ref->commission_earned) }}
                            </td>
                            <td class="px-6 py-4 text-zinc-500 text-xs">
                                {{ $ref->created_at->diffForHumans() }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-zinc-600 italic">No referral data yet</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-zinc-800">
                {{ $referrals->links() }}
            </div>
        </div>

        {{-- Top Referrers Sidebar --}}
        <div class="space-y-6">
            <div class="bg-green-400/10 rounded-2xl border border-white/5 p-5">
                <h3 class="font-bold text-orange-400/80 uppercase text-xs tracking-widest mb-4">Platform Top Referrers</h3>
                <div class="space-y-4">
                    @foreach($topReferrers as $top)
                    <div class="flex items-center justify-between group">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-zinc-800 flex items-center justify-center text-xs font-bold text-zinc-500">
                                #{{ $loop->iteration }}
                            </div>
                            <div>
                                <div class="text-sm font-medium text-white group-hover:text-orange-400 transition-colors">{{ $top->username }}</div>
                                <div class="text-[10px] text-zinc-500">{{ $top->referrals_count }} Referrals</div>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-xs font-mono text-green-400">UGX {{ number_format($top->total_commissions) }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
