@extends('layouts.admin')

@section('title', 'Dashboard Overview')

@section('content')

{{-- TOP STATS CARDS --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-green-400/10 p-3 flex flex-col justify-between rounded-2xl">
        <div class="flex items-center justify-between mb-4">
            <span class="bg-orange-400/70 p-2 rounded-[50%] h-fit w-fit flex items-center justify-center text-white/80">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-dollar-sign">
                    <line x1="12" x2="12" y1="2" y2="22" />
                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" /></svg>
            </span>
            <h3 class="text-white/70 text-sm font-medium">Total Revenue</h3>
        </div>
        <div class="flex items-center justify-between">
            <div class="font-medium text-white/80 mb-1">
                UGX {{ number_format($totalRevenue) }}
            </div>
            <div class="text-xs text-green-400 flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trending-up">
                    <polyline points="22 7 13.5 15.5 8.5 10.5 2 17" />
                    <polyline points="16 7 22 7 22 13" /></svg>
                <span>+12% vs last month</span>
            </div>
        </div>
    </div>

    <div class="bg-green-400/10 p-3 flex flex-col justify-between rounded-2xl">
        <div class="flex items-center justify-between mb-4">
            <span class="bg-orange-400/70 p-2 rounded-[50%] h-fit w-fit flex items-center justify-center text-white/80">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                    <circle cx="9" cy="7" r="4" />
                    <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
                    <path d="M16 3.13a4 4 0 0 1 0 7.75" /></svg>
            </span>
            <h3 class="text-white/70 text-sm font-medium">Active Users</h3>
        </div>
        <div class="flex items-center justify-between">
            <div class="text-xl font-bold text-white mb-1">
                {{ number_format($totalUsers) }}
            </div>
            <div class="text-xs text-orange-400/70 font-medium font-mono">
                Registered accounts
            </div>
        </div>
    </div>

    <div class="bg-green-400/10 p-3 flex flex-col justify-between rounded-2xl">
        <div class="flex items-center justify-between mb-4">
            <span class="bg-orange-400/70 p-2 rounded-[50%] h-fit w-fit flex items-center justify-center text-white/80">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-briefcase">
                    <rect width="20" height="14" x="2" y="7" rx="2" ry="2" />
                    <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16" /></svg>
            </span>
            <h3 class="text-white/70 text-sm font-medium">Organizers</h3>
        </div>
        <div class="flex items-center justify-between">
            <div class="text-xl font-bold text-white mb-1">
                {{ number_format($totalOrganizers) }}
            </div>
            <div class="text-xs text-orange-400/70 font-medium font-mono">
                Business profiles
            </div>
        </div>
    </div>

    <div class="bg-green-400/10 p-3 flex flex-col justify-between rounded-2xl">
        <div class="flex items-center justify-between mb-4">
            <span class="p-2 bg-orange-500/10 text-orange-400 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar">
                    <path d="M8 2v4" />
                    <path d="M16 2v4" />
                    <rect width="18" height="18" x="3" y="4" rx="2" />
                    <path d="M3 10h18" /></svg>
            </span>
            <h3 class="text-white/70 text-sm font-medium">Events Hosted</h3>
        </div>
        <div class="flex items-center justify-between">
            <div class="text-xl font-bold text-white mb-1">
                {{ number_format($totalEvents) }}
            </div>
            <div class="text-xs text-orange-400/70 font-medium font-mono">
                Total events created
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

    {{-- RECENT ORDERS --}}
    <div class="lg:col-span-2 bg-green-400/10 rounded-2xl p-6">
        <h3 class="text-lg font-bold text-white mb-6">Recent Transactions</h3>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-orange-400/70 text-black/90 font-mono text-xs uppercase font-medium">
                        <th class="px-6 py-4">User</th>
                        <th class="px-6 py-4">Event</th>
                        <th class="px-6 py-4">Amount</th>
                        <th class="px-6 py-4">Date</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @forelse($recentOrders as $purchase)
                    <tr class="border-b border-zinc-800/50 last:border-0 hover:bg-zinc-800/50 transition-colors">
                        <td class="py-3 px-2 flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-zinc-800 flex items-center justify-center text-xs font-bold text-zinc-400">
                                {{ substr($purchase->user->first_name, 0, 1) }}
                            </div>
                            <span class="text-zinc-200">{{ $purchase->user->first_name }}</span>
                        </td>
                        <td class="py-3 px-2 text-zinc-400">{{ Str::limit($purchase->event->event_name, 20) }}</td>
                        <td class="py-3 px-2 text-green-400 font-medium">+ UGX {{ number_format($purchase->total) }}</td>
                        <td class="py-3 px-2 text-zinc-500">{{ $purchase->created_at->diffForHumans() }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-4 text-center text-zinc-500">No transactions yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- TOP ORGANIZERS --}}
    <div class="p-4 border-green-400/20 bg-green-400/10 rounded-3xl">
        <h3 class="font-semibold text-orange-400/70 text-sm mb-3">Top Organizers</h3>
        <div class="space-y-4">
            @forelse($topOrganizers as $org)
            <div class="flex items-center gap-5 p-2 border border-green-400/20 bg-green-400/10 rounded-2xl">
                <div class="border border-green-400/15  w-fit rounded-[50%]">
                    <img src="{{ $org->organizer_image ? asset('storage/'.$org->organizer_image) : asset('default.jpg') }}" alt="{{ $org->business_name }}" class='size-13 rounded-[50%]' alt="" />
                </div>
                <div class="flex items-center justify-between w-4/5">
                    <div>
                        <p class="font-medium text-sm text-white/80">{{ $org->business_name }}</p>
                        <div class="text-xs text-white/60">{{ $org->followers_count ?? $org->followers->count() }} followers</div>
                    </div>
                    <a href="{{ route('organizer.details', $org->id) }}" class="text-xs text-black/90 font-medium font-mono bg-orange-400/70 rounded-3xl px-2 py-1">Details</a>
                </div>
            </div>
            @empty
            <div class="text-center text-zinc-500 py-4">No organizers yet.</div>
            @endforelse

        </div>
    </div>

</div>

@endsection
