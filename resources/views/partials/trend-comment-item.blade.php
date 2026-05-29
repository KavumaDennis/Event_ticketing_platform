@php
    $isReply = $isReply ?? false;
@endphp
<div id="comment-{{ $comment->id }}"
    class="{{ $isReply ? 'ml-6 bg-transparent border-t-2 p-0 pt-2 border-white/30 rounded-none' : 'p-2 bg-white/5' }} flex items-start gap-3 rounded-lg transition">

    <x-user-avatar :user="$comment->user" size="w-8 h-8 border border-green-400/60 rounded-full object-cover" />

    <div class="flex-1 min-w-0">
        <div class="flex justify-between items-center gap-2">
            <span class="text-white/80 font-medium text-sm truncate">
                {{ $comment->user?->first_name ?? 'Unknown' }}
                {{ $comment->user?->last_name ?? '' }}
            </span>
            <span class="bg-orange-400/10 border border-orange-400/20 p-0.5 px-2 rounded-full text-white/50 text-[10px] font-mono whitespace-nowrap">{{ $comment->created_at->diffForHumans() }}</span>
        </div>

        <p class="text-white/70 text-sm font-mono break-all overflow-hidden w-full mt-1">
            {!! \App\Support\ContentFormatter::linkify($comment->comment) !!}
        </p>

        <div class="flex gap-3 mt-1 items-center flex-wrap text-xs text-white/50">

            @auth
                <button type="button" class="like-comment-btn flex items-center gap-1" data-comment="{{ $comment->id }}">
                    <i
                        class="fa-heart {{ $comment->likes->contains(fn($l) => (int) $l->user_id === (int) auth()->id()) ? 'fa-solid' : 'fa-regular' }}"></i>
                    <span class="like-count">{{ $comment->likes->count() }}</span>
                </button>
                @unless ($isReply)
                    <button type="button"
                        class="trend-reply-comment-btn flex items-center text-white/40 hover:text-orange-400 transition text-[10px] font-mono uppercase"
                        data-parent="{{ $comment->id }}"
                        data-label="Replying to {{ $comment->user?->first_name ?? 'comment' }}">
                        Reply
                    </button>
                @endunless
                @if ($comment->user_id === auth()->id())
                    <button type="button" class="delete-comment-btn text-red-400 hover:text-red-300 transition"
                        data-comment="{{ $comment->id }}">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                @endif
            @else
                <span class="flex items-center gap-1">
                    <i class="fa-regular fa-heart"></i>
                    <span class="like-count">{{ $comment->likes->count() }}</span>
                </span>
            @endauth

        </div>

        @unless ($isReply)
            <div id="trend-comment-replies-{{ $comment->id }}" class="mt-3 space-y-2">
                @foreach ($comment->replies as $reply)
                    @include('partials.trend-comment-item', ['comment' => $reply, 'isReply' => true])
                @endforeach
            </div>
        @endunless
    </div>
</div>
