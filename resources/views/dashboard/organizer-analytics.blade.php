@extends('layouts.dashboard')

@section('title', 'Organizer Analytics')

@section('content')
<div class="max-w-7xl mx-auto">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-5">
        <div>
            <h1 class="text-2xl font-bold text-white mb-1">Analytics Dashboard</h1>
            <p class="text-white/60 text-sm">Performance insights for your events.</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('organizer.settings') }}" class="px-4 py-2 bg-white/5 border border-white/10 text-white rounded-lg uppercase text-[10px] font-medium hover:bg-white/10 transition">
                Settings
            </a>
            <a href="{{ route('organizer.create') }}" class="px-4 py-2 bg-orange-400 text-black rounded-lg uppercase text-[10px] font-bold hover:bg-orange-500 transition">
                Create Event
            </a>
        </div>
    </div>

    {{-- Key Metrics --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-5">
        {{-- Revenue --}}
        <div class="p-6 bg-green-400/10 border border-green-400/20 rounded-2xl relative overflow-hidden group">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-green-400/10 rounded-full group-hover:scale-110 transition-transform"></div>
            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-2">
                    <div class="p-2 px-3 bg-green-400/20 rounded-lg text-green-400">
                        <i class="fas fa-coins"></i>
                    </div>
                    <span class="text-white/60 text-sm font-medium uppercase tracking-wider">Revenue (This Month)</span>
                </div>
                <div class="text-3xl font-bold text-white font-mono">
                    {{ number_format($monthlyRevenue) }} <span class="text-sm font-normal text-white/40">UGX</span>
                </div>
            </div>
        </div>

        {{-- Tickets Sold --}}
        <div class="p-6 bg-orange-400/10 border border-orange-400/20 rounded-2xl relative overflow-hidden group">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-orange-400/10 rounded-full group-hover:scale-110 transition-transform"></div>
            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-2">
                    <div class="p-2 px-3 bg-orange-400/20 rounded-lg text-orange-400">
                        <i class="fas fa-ticket-alt"></i>
                    </div>
                    <span class="text-white/60 text-sm font-medium uppercase tracking-wider">Tickets Sold</span>
                </div>
                <div class="text-3xl font-bold text-white font-mono">
                    {{ number_format($totalTicketsSold) }}
                </div>
            </div>
        </div>

        {{-- Views --}}
        <div class="p-6 bg-blue-400/10 border border-blue-400/20 rounded-2xl relative overflow-hidden group">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-blue-400/10 rounded-full group-hover:scale-110 transition-transform"></div>
            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-2">
                    <div class="p-2 px-3 bg-blue-400/20 rounded-lg text-blue-400">
                        <i class="fas fa-eye"></i>
                    </div>
                    <span class="text-white/60 text-sm font-medium uppercase tracking-wider">Total Views</span>
                </div>
                <div class="text-3xl font-bold text-white font-mono">
                    {{ number_format($profileViews) }}
                </div>
            </div>
        </div>
    </div>

    {{-- Advanced Insights (New) --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-5">
        <div class="p-4 bg-purple-400/5 border border-purple-400/10 rounded-2xl">
            <p class="text-[10px] text-purple-400 font-bold uppercase tracking-wider mb-1">Affiliate Sales</p>
            <p class="text-xl font-bold text-white">UGX {{ number_format($referralRevenue) }}</p>
            <p class="text-[10px] text-white/40 mt-1">Generated via user referrals</p>
        </div>
        <div class="p-4 bg-red-400/5 border border-red-400/10 rounded-2xl">
            <p class="text-[10px] text-red-400 font-bold uppercase tracking-wider mb-1">Waitlist Total</p>
            <p class="text-xl font-bold text-white">{{ number_format($waitlistTotal) }}</p>
            <p class="text-[10px] text-white/40 mt-1">People waiting for tickets</p>
        </div>
        <div class="p-4 bg-yellow-400/5 border border-yellow-400/10 rounded-2xl">
            <p class="text-[10px] text-yellow-400 font-bold uppercase tracking-wider mb-1">Conversion Rate</p>
            <p class="text-xl font-bold text-white">
                {{ $profileViews > 0 ? number_format(($totalTicketsSold / $profileViews) * 100, 1) : 0 }}%
            </p>
            <p class="text-[10px] text-white/40 mt-1">Views to ticket sales</p>
        </div>
        <div class="p-4 bg-green-400/5 border border-green-400/10 rounded-2xl">
            <p class="text-[10px] text-green-400 font-bold uppercase tracking-wider mb-1">Avg. Sale Value</p>
            <p class="text-xl font-bold text-white">
                UGX {{ $totalTicketsSold > 0 ? number_format($monthlyRevenue / $totalTicketsSold) : 0 }}
            </p>
            <p class="text-[10px] text-white/40 mt-1">Per ticket sold</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-5">
        {{-- Chart Section --}}
        <div class="lg:col-span-2 p-3 bg-white/5 border border-white/10 rounded-2xl">
            <h3 class="text-lg font-bold text-white mb-6">Revenue Trend (Last 7 Days)</h3>
            <div class="relative h-[350px] w-full">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        {{-- Ticket Type Breakdown (New) --}}
        <div class="p-3 bg-white/5 border border-white/10 flex flex-col justify-between rounded-2xl">
            <h3 class="text-lg font-bold text-white mb-6">Ticket Distribution</h3>
            <div class="relative h-[250px] w-full flex justify-center items-center mb-4">
                <canvas id="typeChart"></canvas>
            </div>
            <div class="space-y-2 mt-4">
                @foreach($ticketTypeData as $type)
                <div class="flex justify-between items-center text-xs">
                    <span class="text-white/60 capitalize">{{ $type->ticket_type }}</span>
                    <span class="text-white font-bold">{{ number_format($type->total_sold) }} Sold</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Event Performance Table (New) --}}
    <div class="p-3 bg-white/5 border border-white/10 rounded-2xl mb-5">
        <h3 class="text-lg font-bold text-white mb-3">Detailed Event Performance</h3>
        <div class="overflow-x-auto max-h-[500px] overflow-y-auto custom-scrollbar">
            <table class="w-full text-left border-collapse">
                <thead class="sticky top-0 bg-[#1a1a1a] z-10">
                    <tr class="text-[10px] text-white/40 uppercase tracking-widest border-b border-white/5">
                        <th class="py-3 px-4">Event Name</th>
                        <th class="py-3 px-4">Revenue</th>
                        <th class="py-3 px-4">Sales</th>
                        <th class="py-3 px-4">Views</th>
                        <th class="py-3 px-4">Waitlist</th>
                        <th class="py-3 px-4 text-right">Conv. Rate</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($eventPerformance as $event)
                    <tr class="text-sm">
                        <td class="py-4 pr-4 font-bold text-white">{{ $event->event_name }}</td>
                        <td class="py-4 px-4 text-green-400 font-mono">UGX {{ number_format($event->total_revenue ?? 0) }}</td>
                        <td class="py-4 px-4 text-white/70">{{ number_format($event->tickets_sold ?? 0) }}</td>
                        <td class="py-4 px-4 text-blue-400">{{ number_format($event->views_count) }}</td>
                        <td class="py-4 px-4 text-red-400">{{ number_format($event->waitlist_count) }}</td>
                        <td class="py-4 pl-4 text-right">
                            <span class="px-2 py-1 bg-white/5 rounded-lg text-xs {{ $event->conversion_rate > 10 ? 'text-green-400' : 'text-white/60' }}">
                                {{ number_format($event->conversion_rate, 1) }}%
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-white/30 italic">No event data available yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Recent Sales --}}
    <div class="p-3 bg-white/5 border border-white/10 rounded-2xl">
        <h3 class="text-lg font-bold text-white mb-3">Recent Transactions</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($recentSales as $sale)
            <div class="flex items-center gap-4 p-4 bg-black/20 rounded-2xl border border-white/5">
                <img src="{{ $sale->user->profile_pic ? asset('storage/'.$sale->user->profile_pic) : asset('default.png') }}" class="w-12 h-12 rounded-full object-cover border border-white/10">
                <div class="flex-1 min-w-0">
                    <h4 class="text-sm font-bold text-white truncate">{{ $sale->user->first_name }} {{ $sale->user->last_name }}</h4>
                    <p class="text-xs text-white/40 truncate">{{ $sale->event->event_name }}</p>
                    <p class="text-[10px] text-orange-400 font-bold uppercase tracking-tighter">{{ $sale->ticket_type }} • {{ $sale->quantity }} tkt(s)</p>
                </div>
                <div class="text-right">
                    <span class="block text-sm font-bold text-green-400 font-mono">UGX {{ number_format($sale->total) }}</span>
                    <span class="text-[10px] text-white/30">{{ $sale->created_at->diffForHumans() }}</span>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-8 text-white/30 text-sm">No recent sales found.</div>
            @endforelse
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Revenue Trend Chart
    const ctx = document.getElementById('revenueChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($labels) !!},
            datasets: [{
                label: 'Revenue (UGX)',
                data: {!! json_encode($chartData) !!},
                borderColor: '#fb923c',
                backgroundColor: 'rgba(251, 146, 60, 0.1)',
                borderWidth: 3,
                tension: 0.4,
                pointBackgroundColor: '#fb923c',
                pointRadius: 4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(255, 255, 255, 0.05)' },
                    ticks: { color: 'rgba(255, 255, 255, 0.5)', font: { size: 10 } }
                },
                x: {
                    grid: { display: false },
                    ticks: { color: 'rgba(255, 255, 255, 0.5)', font: { size: 10 } }
                }
            }
        }
    });

    // Ticket Type Distribution Chart
    const typeCtx = document.getElementById('typeChart').getContext('2d');
    new Chart(typeCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($ticketTypeData->pluck('ticket_type')) !!},
            datasets: [{
                data: {!! json_encode($ticketTypeData->pluck('total_sold')) !!},
                backgroundColor: [
                    '#fb923c', // orange
                    '#a855f7', // purple
                    '#3b82f6', // blue
                    '#10b981'  // green
                ],
                borderWidth: 0,
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { color: 'rgba(255, 255, 255, 0.6)', font: { size: 10 }, usePointStyle: true, padding: 15 }
                }
            },
            cutout: '70%'
        }
    });
</script>
@endsection
