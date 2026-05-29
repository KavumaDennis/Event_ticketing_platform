@php
    $isReply = $isReply ?? false;
@endphp
<div id="event-comment-{{ $comment->id }}"
    class="{{ $isReply ? 'ml-6 pl-3 border-l border-white/10 ' : '' }}flex items-start gap-3 p-2 rounded-lg bg-black/50">
    <x-user-avatar :user="$comment->user" size="w-8 h-8 rounded-full object-cover" />
    <div class="flex-1 min-w-0">
        <div class="flex items-center justify-between gap-2">
            <div class="text-white/80 text-[10px] font-bold truncate">
                {{ $comment->user?->first_name ?? 'Unknown' }}
                {{ $comment->user?->last_name ?? '' }}
            </div>
            <span class="text-white/30 text-[10px] whitespace-nowrap">{{ $comment->created_at->diffForHumans() }}</span>
        </div>
        <div class="text-white/60 text-xs break-words">
            {!! \App\Support\ContentFormatter::linkify($comment->comment) !!}
        </div>
        <div class="flex items-center flex-wrap gap-3 mt-2 text-[10px]">
            @auth
                <button
                    class="event-like-comment-btn flex items-center gap-1 text-orange-400/80 hover:text-orange-400"
                    data-comment="{{ $comment->id }}" type="button">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-heart-icon lucide-heart">
                        <path
                            d="M2 9.5a5.5 5.5 0 0 1 9.591-3.676.56.56 0 0 0 .818 0A5.49 5.49 0 0 1 22 9.5c0 2.29-1.5 4-3 5.5l-5.492 5.313a2 2 0 0 1-3 .019L5 15c-1.5-1.5-3-3.2-3-5.5" />
                    </svg>
                    <span class="like-count">{{ $comment->likes->count() }}</span>
                </button>
                @unless ($isReply)
                    <button type="button" class="event-reply-comment-btn text-white/45 hover:text-orange-400 transition"
                        data-parent="{{ $comment->id }}" data-label="Replying to {{ $comment->user?->first_name ?? 'comment' }}">
                        Reply
                    </button>
                @endunless
                @if (auth()->id() === $comment->user_id)
                    <button class="event-delete-comment-btn text-red-500/80 hover:text-red-300" type="button"
                        data-comment="{{ $comment->id }}">
                        Delete
                    </button>
                @endif
            @else
                <span class="flex items-center gap-1 text-white/35">
                    <span class="like-count">{{ $comment->likes->count() }}</span>
                    likes
                </span>
            @endauth
        </div>

        @unless ($isReply)
            <div id="event-comment-replies-{{ $comment->id }}" class="mt-2 space-y-2">
                @foreach ($comment->replies as $reply)
                    @include('partials.event-comment-item', ['comment' => $reply, 'isReply' => true])
                @endforeach
            </div>
        @endunless
    </div>
</div>
