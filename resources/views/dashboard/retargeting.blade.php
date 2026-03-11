@extends('layouts.dashboard')

@section('title', 'Retargeting Leads - ' . $event->event_name)

@section('content')
<div class="max-w-6xl mx-auto">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <nav class="flex mb-4" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3 text-xs font-mono uppercase tracking-widest">
                    <li class="inline-flex items-center">
                        <a href="{{ route('user.dashboard.events') }}" class="text-zinc-500 hover:text-orange-400 transition-colors">Events</a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fa-solid fa-chevron-right text-zinc-700 text-[8px] mx-2"></i>
                            <span class="text-zinc-400">Retargeting</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <h1 class="text-3xl font-black text-white tracking-tighter uppercase">Retargeting Leads</h1>
            <p class="text-zinc-500 text-sm mt-1">Users who liked <span class="text-orange-400 font-bold">{{ $event->event_name }}</span> but haven't purchased tickets yet.</p>
        </div>
        
        <div class="flex items-center gap-3">
            <div class="px-4 py-2 bg-zinc-900/50 border border-white/5 rounded-2xl text-center">
                <p class="text-[10px] text-zinc-500 uppercase tracking-widest font-bold">Total Leads</p>
                <p class="text-xl font-black text-orange-400">{{ $leads->total() }}</p>
            </div>
        </div>
    </div>

    {{-- Leads Table --}}
    <div class="bg-zinc-950 border border-white/5 rounded-[2.5rem] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-white/5 bg-white/5">
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-zinc-500">User</th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-zinc-500">Contact</th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-zinc-500">Status</th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-zinc-500 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($leads as $lead)
                    <tr class="group hover:bg-white/[0.02] transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="size-10 rounded-full bg-zinc-800 border border-white/5 flex items-center justify-center text-zinc-500 font-bold">
                                    @if($lead->profile_pic)
                                        <img src="{{ Storage::url($lead->profile_pic) }}" class="size-full rounded-full object-cover">
                                    @else
                                        {{ substr($lead->first_name, 0, 1) }}
                                    @endif
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-white">{{ $lead->first_name }} {{ $lead->last_name }}</p>
                                    <p class="text-xs text-zinc-500 font-mono">{{ '@' . $lead->username }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-xs text-zinc-400">{{ $lead->email }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-0.5 bg-orange-400/10 text-orange-400 text-[10px] font-bold uppercase rounded-full border border-orange-400/20">Liked Event</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button 
                                onclick="sendOffer(this, {{ $lead->id }})"
                                class="inline-flex items-center gap-2 px-4 py-2 bg-zinc-800 text-zinc-300 rounded-xl text-[10px] font-black uppercase hover:bg-orange-400 hover:text-black transition-all disabled:opacity-50 disabled:cursor-not-allowed group/btn"
                            >
                                <i class="fa-solid fa-envelope group-[.loading]/btn:hidden"></i>
                                <i class="fa-solid fa-spinner fa-spin hidden group-[.loading]/btn:block"></i>
                                <span class="group-[.loading]/btn:hidden">Send Offer</span>
                                <span class="hidden group-[.loading]/btn:block">Sending...</span>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-20 text-center">
                            <div class="opacity-20 mb-4">
                                <i class="fa-solid fa-users-slash text-5xl"></i>
                            </div>
                            <p class="text-zinc-500 font-mono text-sm uppercase tracking-widest">No retargeting leads found yet</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($leads->hasPages())
        <div class="p-6 border-t border-white/5 bg-white/5">
            {{ $leads->links() }}
        </div>
        @endif
    </div>

    {{-- Tip Alert --}}
    <div class="mt-8 p-6 bg-orange-400/5 border border-orange-400/20 rounded-3xl flex gap-4 items-start">
        <div class="size-10 bg-orange-400/20 rounded-2xl flex items-center justify-center text-orange-400 shrink-0">
            <i class="fa-solid fa-lightbulb"></i>
        </div>
        <div>
            <h4 class="text-white font-bold text-sm">Organizer Pro-Tip</h4>
            <p class="text-zinc-500 text-xs mt-1 leading-relaxed">Users who like your events are 4x more likely to convert if sent a personalized discount or early-bird reminder. Use the email action to reach out directly!</p>
        </div>
    </div>
</div>

@push('scripts')
<script>
    async function sendOffer(btn, userId) {
        if (!confirm('Are you sure you want to send this offer email directly to the user?')) return;

        btn.disabled = true;
        btn.classList.add('loading');

        try {
            const response = await fetch("{{ route('organizer.sendOffer', $event->id) }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ user_id: userId })
            });

            const data = await response.json();

            if (data.success) {
                // Change button style to success
                btn.classList.remove('loading');
                btn.classList.remove('bg-zinc-800', 'text-zinc-300', 'hover:bg-orange-400', 'hover:text-black');
                btn.classList.add('bg-green-500/20', 'text-green-500', 'border', 'border-green-500/20');
                btn.innerHTML = '<i class="fa-solid fa-check"></i> Sent';
                btn.onclick = null; // Disable further clicks
            } else {
                alert('Error: ' + (data.message || 'Failed to send offer.'));
                btn.disabled = false;
                btn.classList.remove('loading');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('A network error occurred. Please try again.');
            btn.disabled = false;
            btn.classList.remove('loading');
        }
    }
</script>
@endpush
@endsection
