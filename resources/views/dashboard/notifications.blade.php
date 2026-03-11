@extends('layouts.dashboard')

@section('content')
<div class="px-6 pb-20">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-12">
        <div>
            <h1 class="text-4xl font-black text-white uppercase tracking-tighter">Notifications</h1>
            <p class="text-zinc-500 text-sm">Stay updated with your latest activities</p>
        </div>
        
        @if($notifications->count() > 0)
        <form action="{{ route('notifications.readAll') }}" method="POST">
            @csrf
            <button type="submit" class="px-3 py-1.5  rounded-lg flex items-center gap-2 bg-orange-400 hover:text-black transition-all text-[10px] font-bold uppercase">
                Mark all as read
            </button>
        </form>
        @endif
    </div>

    <div class="max-w-3xl space-y-4">
        @forelse($notifications as $notify)
        @php
            $url = $notify->action_url;
            if (!$url && $notify->related_id) {
                if ($notify->related_type === 'App\Models\Event') {
                    $url = route('event.show', $notify->related_id);
                } elseif ($notify->related_type === 'App\Models\Trend') {
                    $url = route('trends.show', $notify->related_id);
                }
            }
        @endphp
        <div 
            id="notification-{{ $notify->id }}"
            class="bg-green-400/20 border border-green-400/20  p-4 flex gap-6 items-start hover:border-zinc-700 transition-all cursor-pointer relative group {{ $notify->read_at ? 'opacity-50' : 'border-l-4 border-l-orange-500 shadow-lg shadow-orange-500/5' }}"
            onclick="markAsRead(this, {{ $notify->id }}, '{{ $url }}')"
        >
            <div class="size-12 rounded-2xl flex items-center justify-center shrink-0
                        {{ $notify->type === 'success' ? 'bg-green-500/10 text-green-500' : '' }}
                        {{ $notify->type === 'info' ? 'bg-blue-500/10 text-blue-500' : '' }}
                        {{ $notify->type === 'warning' ? 'bg-orange-500/10 text-orange-400' : '' }}">
                <i class="fas {{ $notify->type === 'success' ? 'fa-check-circle' : ($notify->type === 'warning' ? 'fa-exclamation-triangle' : 'fa-info-circle') }}"></i>
            </div>
            <div class="flex-1">
                <div class="flex justify-between items-start mb-1">
                    <h3 class="text-white font-bold">{{ $notify->title }}</h3>
                    <span class="text-[10px] text-zinc-600 font-bold uppercase">{{ $notify->created_at->diffForHumans() }}</span>
                </div>
                <p class="text-zinc-400 text-sm leading-relaxed">{{ $notify->message }}</p>
            </div>
            
            @if(!$notify->read_at)
            <div class="absolute top-6 right-6 opacity-0 group-hover:opacity-100 transition-opacity">
                <div class="size-2 bg-orange-500 rounded-full"></div>
            </div>
            @endif
        </div>
        @empty
        <div class="py-20 bg-zinc-900/40 border border-dashed border-zinc-800 rounded-[3rem] text-center">
            <i class="far fa-bell-slash text-4xl text-zinc-800 mb-4 block"></i>
            <p class="text-zinc-600 font-bold">No notifications yet.</p>
        </div>
        @endforelse

        <div class="mt-8">
            {{ $notifications->links() }}
        </div>
    </div>
</div>

@push('scripts')
<script>
    async function markAsRead(el, id, url) {
        // If already read, just redirect
        if (el.classList.contains('opacity-50')) {
             if (url) window.location.href = url;
             return;
        }

        try {
            const response = await fetch(`/dashboard/notifications/${id}/read`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            const data = await response.json();

            if (data.success) {
                if (url) {
                    window.location.href = url;
                    return;
                }

                el.classList.add('opacity-50');
                el.classList.remove('border-l-4', 'border-l-orange-500', 'shadow-lg', 'shadow-orange-500/5');
                el.removeAttribute('onclick');
                // Re-add onclick for pure redirection if clicked again (optional, but current logic handles it via class check)
                el.setAttribute('onclick', `markAsRead(this, ${id}, '${url}')`); // Ensure URL persists
                
                // Hide the dot
                const dot = el.querySelector('.absolute.top-6.right-6');
                if (dot) dot.remove();

                // Update badge if exists in parent
                const badge = document.querySelector('.lucide-bell + span');
                if (badge) {
                    let count = parseInt(badge.innerText);
                    if (count > 1) {
                        badge.innerText = count - 1;
                    } else {
                        badge.remove();
                    }
                }
            }
        } catch (error) {
            console.error('Error marking as read:', error);
            if (url) window.location.href = url;
        }
    }
</script>
@endpush
@endsection
