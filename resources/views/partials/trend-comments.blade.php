@foreach($comments as $comment)
<div class="flex items-start gap-3" id="comment-{{ $comment->id }}">
    <img src="{{ $comment->user->profile_photo_url ?? asset('default.jpg') }}" class="w-8 h-8 rounded-[50%] object-cover">
    <div class="flex flex-col w-full">
        <div class="flex justify-between items-center">
            <p class="text-white/80 text-sm font-medium">{{ $comment->user->first_name }}:</p>
            @if(auth()->id() === $comment->user_id)
            <button data-id="{{ $comment->id }}" class="delete-comment text-xs text-red-400">Delete</button>
            @endif
        </div>
        <p class="text-white/60 text-sm">{{ $comment->comment }}</p>
        <div class="flex items-center gap-2 text-xs text-white/40">
            <button data-id="{{ $comment->id }}" class="like-comment">
                ❤️ <span class="like-count">{{ $comment->likes()->count() }}</span>
            </button>
            <span>{{ $comment->created_at->diffForHumans() }}</span>
        </div>
    </div>
</div>
@endforeach
