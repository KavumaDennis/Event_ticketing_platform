<x-layout>

    <section class="grid grid-cols-14 gap-5 p-5">
        <section class="col-span-10 grid grid-cols-2 gap-5 w-full h-fit">

            <div class="h-[520px] flex flex-col gap-3 justify-between">
                <div class="p-0.5 w-fit rounded-3xl bg-orange-400 hover:text-orange-300 transition">
                    <a href="{{ route('trends') }}" class="flex items-center gap-1 ">
                        <div class="size-8 rounded-[50%] bg-black/90 border border-green-400/10 flex items-center justify-center">
                            <i class="fa-solid fa-arrow-left text-orange-400/80"></i>
                        </div>
                        <p class="text-black/90 text-xs font-medium font-mono pr-1">Back to Trends</p>
                    </a>
                </div>
                <div class="flex gap-2 items-center">
                    <div class="bg-green-400/10 border border-green-400/20 text-xs rounded-2xl p-1 text-orange-400/50 font-mono w-fit">
                        {{ $trend->event->event_name }}
                    </div>
                    <a href="{{ route('event.show', $trend->event->id) }}" class="text-xs p-1 font-mono font-medium bg-orange-400 rounded-2xl w-fit text-black/90">
                        Event Details
                    </a>
                </div>


            <div class="bg-green-400/10 p-3 py-2 flex-1 flex flex-col justify-between rounded-2xl w-fit">
                <h1 class="text-2xl font-medium text-orange-400/70 mb-4">{{ $trend->title }}</h1>
                <div class="flex gap-5 items-center text-sm font-mono font-bold text-white/40">
                    <div class="flex items-center gap-3 ">
                        <i class="fa-solid fa-user text-orange-400/80"></i>
                        <span>By {{ $trend->user?->first_name ?? 'Unknown' }} {{ $trend->user?->last_name ?? '' }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-calendar text-orange-400/80"></i>
                        <span>{{ $trend->created_at->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>
                {{-- Swiper CSS --}}
                <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
                
                <div class="relative w-full h-[300px] overflow-hidden rounded-3xl group/media bg-black">
                    <div class="swiper mySwiper w-full h-full">
                        <div class="swiper-wrapper">
                            @if($trend->media->count() > 0)
                                @foreach($trend->media as $media)
                                    <div class="swiper-slide w-full h-full bg-black flex items-center justify-center">
                                        @if($media->type === 'video')
                                            <video src="{{ asset('storage/'.$media->path) }}" class="w-full h-full object-cover" autoplay muted loop playsinline></video>
                                        @else
                                            <img src="{{ asset('storage/'.$media->path) }}" alt="{{ $trend->title }}" class="w-full h-full object-cover">
                                        @endif
                                    </div>
                                @endforeach
                            @else
                                {{-- Fallback for legacy data --}}
                                <div class="swiper-slide w-full h-full bg-black flex items-center justify-center">
                                    @if($trend->is_video)
                                        <video src="{{ $trend->first_media_url }}" class="w-full h-full object-cover" autoplay muted loop playsinline></video>
                                    @else
                                        <img src="{{ $trend->first_media_url }}" alt="{{ $trend->title }}" class="w-full h-full object-cover">
                                    @endif
                                </div>
                            @endif
                        </div>
                        <div class="swiper-button-next text-xs text-orange-400"></div>
                        <div class="swiper-button-prev text-xs text-orange-400"></div>
                        <div class="swiper-pagination"></div>
                    </div>
                </div>

                {{-- Swiper JS --}}
                <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
                <script>
                    var swiper = new Swiper(".mySwiper", {
                        navigation: {
                            nextEl: ".swiper-button-next",
                            prevEl: ".swiper-button-prev",
                        },
                        pagination: {
                            el: ".swiper-pagination",
                            clickable: true,
                        },
                        loop: true,
                    });
                </script>
            </div>

            <div class="relative bg-black/30 border border-white/5  rounded-3xl  h-[520px] p-3 backdrop-blur-xl shadow-lg flex flex-col">
                <div class="prose prose-invert max-w-none text-white/70 text-sm font-mono p-2">
                    <p id="trend-body-{{ $trend->id }}" class="overflow-hidden max-h-16">
                        {{ $trend->body }}
                    </p>
                    <button id="trend-more-btn-{{ $trend->id }}" class="text-orange-400 text-sm font-bold mt-1 hover:underline">
                        More
                    </button>
                </div>

                <div id="commentsList" class="flex flex-col gap-3 max-h-96 overflow-y-auto mt-3">

                    @forelse($trend->comments->sortByDesc('created_at') as $comment)

                    <div id="comment-{{ $comment->id }}" class="flex items-start gap-3 p-2 rounded-2xl bg-black/50 hover:bg-black/60 transition">

                        {{-- Profile --}}
                        <img src="{{ $comment->user?->profile_pic
                        ? asset('storage/'.$comment->user->profile_pic)
                        : asset('default.png') }}" class="w-8 h-8 rounded-full object-cover mt-1" alt="Profile">

                        <div class="flex-1">

                            {{-- Header --}}
                            <div class="flex justify-between items-center">
                                <span class="text-white/80 font-medium">
                                    {{ $comment->user?->first_name ?? 'Unknown' }}
                                    {{ $comment->user?->last_name ?? '' }}
                                </span>
                                <span class="text-white/50 text-xs">
                                    {{ $comment->created_at->diffForHumans() }}
                                </span>
                            </div>

                            {{-- Body --}}
                            <p class="text-white/70 text-sm mt-1 break-words">
                                {{ $comment->comment }}
                            </p>

                            {{-- Actions --}}
                            <div class="flex gap-4 mt-1 items-center text-xs text-white/50">

                                @auth
                                <button class="like-comment-btn flex items-center gap-1" data-comment="{{ $comment->id }}">
                                    <i class="fa-heart {{ $comment->likes->contains('user_id', auth()->id()) ? 'fa-solid' : 'fa-regular' }}"></i>
                                    <span class="like-count">{{ $comment->likes->count() }}</span>
                                </button>
                                @else
                                <span>❤️ {{ $comment->likes->count() }}</span>
                                @endauth

                                @auth
                                @if($comment->user_id === auth()->id())
                                <button class="delete-comment-btn text-red-400 hover:text-red-300 transition" data-comment="{{ $comment->id }}">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                                @endif
                                @endauth

                            </div>

                        </div>
                    </div>

                    @empty
                    <div class="text-center text-white/40 text-sm py-6">
                        💬 No comments yet — be the first to comment
                    </div>
                    @endforelse
                </div>
                <div class="bg-black/30 absolute bottom-3 right-3 left-3 border border-white/10 rounded-2xl p-2 w-[95%] mt-6">
                    <div class="flex items-center justify-between">
                        <div class="flex gap-3 text-white items-center">
                            <button class="trend-like-btn size-8 flex justify-center items-center rounded-xl bg-green-400/10 border border-green-400/20" data-trend-id="{{ $trend->id }}" data-is-liked="{{ isset($trend->is_liked) && $trend->is_liked ? 'true' : 'false' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-heart-icon lucide-heart {{ isset($trend->is_liked) && $trend->is_liked ? 'text-red-500' : 'text-white/70' }}">
                                    <path d="M2 9.5a5.5 5.5 0 0 1 9.591-3.676.56.56 0 0 0 .818 0A5.49 5.49 0 0 1 22 9.5c0 2.29-1.5 4-3 5.5l-5.492 5.313a2 2 0 0 1-3 .019L5 15c-1.5-1.5-3-3.2-3-5.5" />
                                </svg>
                            </button>

                            <button id="comment_btn" onclick="openCommentInput()">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-message-circle-icon lucide-message-circle">
                                    <path d="M2.992 16.342a2 2 0 0 1 .094 1.167l-1.065 3.29a1 1 0 0 0 1.236 1.168l3.413-.998a2 2 0 0 1 1.099.092 10 10 0 1 0-4.777-4.719" /></svg>
                            </button>
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-share-icon lucide-share">
                                    <path d="M12 2v13" />
                                    <path d="m16 6-4-4-4 4" />
                                    <path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8" /></svg>
                            </span>
                        </div>

                        <div class="flex gap-1 items-center text-sm text-orange-400 font-medium px-3 ">
                            <span class=" trend-likes-count" data-trend-id="{{ $trend->id }}">
                                {{ $trend->likes_count ?? 0 }}
                            </span>
                            <span>Likes</span>
                        </div>

                    </div>

                    {{-- Comment Input --}}
                    @auth
                    <div id="write_comment" class="hidden">
                        <div class="bg-green-400/20 backdrop-blur-xl rounded-2xl p-1 mt-2">
                            <form id="commentForm" data-trend="{{ $trend->id }}" method="POST" action="{{ route('trends.comment', $trend->id) }}" class="flex items-center gap-2 h-full">
                                @csrf
                                <input type="text" name="comment" maxlength="500" placeholder="Add a comment..." class="flex-1 p-2 bg-black/50 text-white rounded-xl outline-none placeholder:text-white/60" />
                                <button type="submit" class="bg-orange-400/80 font-mono text-sm rounded-xl px-3 py-2 hover:bg-orange-400 transition h-full">
                                    Post
                                </button>
                            </form>
                        </div>
                        @else
                        <div class="text-center text-sm text-white/50 py-4">
                            <a href="{{ route('login') }}" class="text-orange-400 hover:underline">
                                Log in
                            </a> to join the conversation
                        </div>
                    </div>

                    @endauth
                </div>
            </div>
        </section>




        <section class=" col-span-4  w-full">
            <div class="flex flex-col h-full justify-between gap-3">
                <h1 class="text-xs p-1 font-mono font-medium bg-orange-400 rounded-2xl w-fit text-black/90">
                    Explore More Trends
                </h1>
                <div class="flex flex-col justify-between flex-1">
                    @foreach($randomTrends as $randTrend)
                    <a href="{{ route('trends.show', $randTrend->id) }}" class="flex items-center gap-3 h-fit p-2.5 bg-green-400/10 border border-green-400/5 rounded-2xl hover:bg-black/80 transition">
                        @if($randTrend->is_video)
                        <video src="{{ $randTrend->first_media_url }}" class="w-20 h-16 rounded-xl object-cover" autoplay muted loop playsinline></video>
                        @else
                        <img src="{{ $randTrend->first_media_url }}" class="w-20 h-16 rounded-xl object-cover" alt="{{ $randTrend->title }}">
                        @endif
                        <div class="flex flex-col">
                            <h2 class="text-white/80 font-medium text-sm">{{ $randTrend->title }}</h2>
                            <p class="text-orange-400/60 text-xs">{{ Str::limit($randTrend->body, 45) }}</p>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>

        </section>
    </section>


</x-layout>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // Utility: escape HTML
    function escapeHtml(text) {
        return $('<div>').text(text).html();
    }

    // Get CSRF token from meta tag
    const csrfToken = $('meta[name="csrf-token"]').attr('content');



    // Submit comment
    $(document).on('submit', '#commentForm', function(e) {
        e.preventDefault();

        const form = $(this);
        const trendId = form.data('trend');
        const input = form.find('input[name="comment"]');
        const comment = input.val().trim();
        const btn = form.find('button[type="submit"]');



        if (!comment) return;

        btn.prop('disabled', true).text('Posting...');

        $.ajax({
            url: `/trends/${trendId}/comment`
            , method: 'POST'
            , data: {
                comment
                , _token: csrfToken
            }
            , success(res) {
                $('#commentsList').prepend(`
                    <div class="flex items-start gap-3 p-2 rounded-2xl bg-black/50 hover:bg-black/60 transition"
                         id="comment-${res.id}">
                        <img src="${res.user_photo}" class="w-8 h-8 rounded-full object-cover mt-1">
                        <div class="flex-1">
                            <div class="flex justify-between items-center">
                                <span class="text-white/80 font-medium">
                                    ${escapeHtml(res.user_name)}
                                </span>
                                <span class="text-white/50 text-xs">just now</span>
                            </div>
                            <p class="text-white/70 text-sm mt-1">
                                ${escapeHtml(res.comment)}
                            </p>
                            <div class="flex gap-3 mt-1 items-center text-xs text-white/50">
                                <button class="like-comment-btn flex items-center gap-1"
                                        data-comment="${res.id}">
                                    <i class="fa-regular fa-heart"></i>
                                    <span class="like-count">0</span>
                                </button>
                                <button class="delete-comment-btn text-red-400"
                                        data-comment="${res.id}">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                `);

                input.val('');
            }
            , error(xhr) {
                if (xhr.status === 401) {
                    alert('Please log in to comment.');
                } else {
                    alert('Error posting comment.');
                }
            }
            , complete() {
                btn.prop('disabled', false).text('Post');
            }
        });
    });

    // Like comment
    $(document).on('click', '.like-comment-btn', function() {
        const btn = $(this);
        const commentId = btn.data('comment');

        $.post(`/trends/comment/${commentId}/like`, {
                _token: csrfToken
            })
            .done(res => {
                btn.find('.like-count').text(res.likes_count);
                btn.find('i')
                    .toggleClass('fa-regular', !res.liked)
                    .toggleClass('fa-solid', res.liked);
            })
            .fail(xhr => {
                if (xhr.status === 401) {
                    alert('Login required to like comments.');
                }
            });
    });

    // Delete comment
    $(document).on('click', '.delete-comment-btn', function() {
        if (!confirm('Delete this comment?')) return;

        const commentId = $(this).data('comment');

        $.ajax({
            url: `/trends/comment/${commentId}`
            , method: 'DELETE'
            , data: {
                _token: csrfToken
            }
            , success() {
                $(`#comment-${commentId}`).fadeOut(200, function() {
                    $(this).remove();
                });
            }
            , error(xhr) {
                if (xhr.status === 401) {
                    alert('Login required to delete comments.');
                } else if (xhr.status === 403) {
                    alert('You can only delete your own comments.');
                } else {
                    alert('Error deleting comment.');
                }
            }
        });
    });



    document.addEventListener('DOMContentLoaded', () => {
        const body = document.getElementById('trend-body-{{ $trend->id }}');
        const btn = document.getElementById('trend-more-btn-{{ $trend->id }}');

        btn.addEventListener('click', () => {
            if (body.classList.contains('max-h-16')) {
                body.classList.remove('max-h-16', 'overflow-hidden');
                btn.textContent = 'Less';
            } else {
                body.classList.add('max-h-16', 'overflow-hidden');
                btn.textContent = 'More';
            }
        });
    });

    document.addEventListener('DOMContentLoaded', () => {
        window.openCommentInput = function() {
            if (write_comment.classList.contains('hidden')) {
                write_comment.classList.toggle('hidden')
            } else {
                write_comment.classList.add('hidden')
            }
        }

    })

</script>
