@extends('layouts.admin')

@section('title', 'Manage Reviews')

@section('content')
<div class="bg-green-400/10 border border-zinc-800 rounded-2xl overflow-hidden mt-2">
    <div class="p-6 border-b border-zinc-800 flex justify-between items-center">
        <div>
            <h2 class="text-xl font-bold text-white uppercase tracking-tighter">User Reviews</h2>
            <p class="text-zinc-500 text-xs font-mono mt-1">Monitor and manage user feedback for events</p>
        </div>
        <div class="bg-orange-400/10 px-4 py-2 rounded-xl border border-orange-400/20 text-orange-400 text-xs font-bold uppercase tracking-widest">
            Total {{ $reviews->total() }}
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-orange-400/70 text-black/90 font-mono text-[10px] uppercase font-black">
                <tr>
                    <th class="px-6 py-4 tracking-widest">Reviewer</th>
                    <th class="px-6 py-4 tracking-widest">Event</th>
                    <th class="px-6 py-4 tracking-widest">Rating</th>
                    <th class="px-6 py-4 tracking-widest">Comment</th>
                    <th class="px-6 py-4 tracking-widest">Date</th>
                    <th class="px-6 py-4 tracking-widest text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-800/50">
                @forelse($reviews as $review)
                <tr class="hover:bg-zinc-800/30 transition-colors group">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="size-8 rounded-full bg-zinc-800 flex items-center justify-center text-[10px] font-bold text-zinc-400 border border-white/5">
                                {{ substr($review->user->first_name, 0, 1) }}
                            </div>
                            <div>
                                <div class="font-medium text-white text-sm">{{ $review->user->first_name }} {{ $review->user->last_name }}</div>
                                <div class="text-[10px] text-zinc-500 font-mono">{{ $review->user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-zinc-300 text-sm font-medium">{{ $review->event->event_name ?? 'N/A' }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex gap-0.5 text-[10px]">
                            @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star {{ $i <= $review->rating ? 'text-orange-400' : 'text-zinc-700' }}"></i>
                            @endfor
                        </div>
                    </td>
                    <td class="px-6 py-4 max-w-xs">
                        <p class="text-zinc-400 text-xs italic line-clamp-2 group-hover:line-clamp-none transition-all duration-300">
                            "{{ $review->body }}"
                        </p>
                    </td>
                    <td class="px-6 py-4 text-zinc-500 text-[11px] font-mono">
                        {{ $review->created_at->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-4 text-right">
                        <form action="{{ route('admin.reviews.delete', $review) }}" method="POST" onsubmit="return confirm('Permanently delete this review?')" class="inline">
                            @csrf
                            @method('DELETE')
                            <button class="bg-red-500/10 text-red-500 border border-red-500/20 hover:bg-red-500 hover:text-white size-8 rounded-lg flex items-center justify-center transition-all">
                                <i class="fas fa-trash-alt text-xs"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-20 text-center">
                        <div class="flex flex-col items-center gap-2 text-zinc-600">
                            <i class="fas fa-comment-slash text-4xl mb-2"></i>
                            <p class="font-bold uppercase tracking-widest text-xs">No reviews found</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="p-6 border-t border-zinc-800 bg-zinc-900/20">
        {{ $reviews->links() }}
    </div>
</div>
@endsection
