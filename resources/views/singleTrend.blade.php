<x-layout>

    <section class="grid grid-cols-14 gap-5 p-5">
        <section class="col-span-10 grid grid-cols-2 gap-3 w-full h-fit">

            <div class="h-[520px] flex flex-col justify-between">
                <a href="{{ route('trends') }}" class="inline-flex w-fit items-center gap-1 p-1 rounded-3xl bg-orange-400/70 hover:text-orange-300 transition">
                    <div class="size-8 rounded-[50%] bg-black/90 border border-green-400/10 flex items-center justify-center">
                        <i class="fa-solid fa-arrow-left text-orange-400/80"></i>
                    </div>
                    <p class="text-black/90 text-sm font-medium pr-1">Back to Trends</p>
                </a>

                <div class="bg-green-400/10 p-5 rounded-3xl w-fit">
                    <h1 class="text-3xl font-medium text-orange-400/90 mb-4">{{ $trend->title }}</h1>
                    <div class="flex gap-5 items-center text-sm text-white/50">
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

                <img src="{{ $trend->image ? asset('storage/'.$trend->image) : asset('default.jpg') }}  " alt="{{ $trend->title }}" class="w-full h-[300px] object-cover rounded-3xl border border-green-400/15">
            </div>

            <div class="relative bg-black/30 border border-white/5 rounded-3xl  h-[520px] p-3 backdrop-blur-xl shadow-lg flex flex-col">
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
                        : asset('default.jpg') }}" class="w-8 h-8 rounded-full object-cover mt-1" alt="Profile">

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
                            <button class="trend-like-btn size-8 flex justify-center items-center rounded-[50%] bg-green-400/10 border border-green-400/20" data-trend-id="{{ $trend->id }}" data-is-liked="{{ isset($trend->is_liked) && $trend->is_liked ? 'true' : 'false' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-heart-icon lucide-heart {{ isset($trend->is_liked) && $trend->is_liked ? 'text-red-500' : 'text-white/70' }}">
                                    <path d="M2 9.5a5.5 5.5 0 0 1 9.591-3.676.56.56 0 0 0 .818 0A5.49 5.49 0 0 1 22 9.5c0 2.29-1.5 4-3 5.5l-5.492 5.313a2 2 0 0 1-3 .019L5 15c-1.5-1.5-3-3.2-3-5.5" />
                                </svg>
                            </button>

                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-message-circle-icon lucide-message-circle">
                                    <path d="M2.992 16.342a2 2 0 0 1 .094 1.167l-1.065 3.29a1 1 0 0 0 1.236 1.168l3.413-.998a2 2 0 0 1 1.099.092 10 10 0 1 0-4.777-4.719" /></svg>
                            </span>
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
                        </a> to join the discussion
                    </div>
                    @endauth
                </div>
            </div>
        </section>




        <section class=" col-span-4  w-full">
            <div class="">
                <h1 class="text-xl font-medium text-orange-400/70 mb-2">
                    Top Organizers
                </h1>

                <div class="flex flex-col gap-5 bg-green-400/10 p-3 rounded-4xl">

                    @foreach($topOrganizers as $organizer)
                    <div class="flex items-center gap-5 p-2 border border-green-400/20 bg-green-400/10 rounded-2xl">
                        <div class="border border-green-400/15  w-fit rounded-[50%]">
                            <img src="{{ $organizer->organizer_image ? asset('storage/'.$organizer->organizer_image) : asset('default.jpg') }}" alt="{{ $organizer->business_name }}" class='size-13 rounded-[50%]' alt="" />
                        </div>
                        <div class="flex items-center justify-between w-4/5">
                            <div>
                                <p class="font-medium text-sm text-white/80">{{ $organizer->business_name }}</p>
                                <div class="text-xs text-white/60">{{ $organizer->followers_count ?? $org->followers->count() }} followers</div>
                            </div>
                            <a href="{{ route('organizer.details', $organizer->id) }}" class="text-xs text-black/90 font-medium font-mono bg-orange-400/70 rounded-3xl px-2 py-1">Details</a>
                        </div>
                    </div>
                    @endforeach

                </div>
            </div>

            <div class="">
                <h1 class="text-xl font-medium text-orange-400/70 mt-6 mb-3">
                    Explore More Trends
                </h1>

                <div class="grid grid-cols-1 gap-4">
                    @foreach($randomTrends as $randTrend)
                    <a href="{{ route('trends.show', $randTrend->id) }}" class="flex items-center gap-3 p-3 bg-green-400/10 rounded-3xl hover:bg-black/80 transition">
                        <img src="{{ $randTrend->image ? asset('storage/'.$randTrend->image) : asset('default.jpg') }}" class="w-16 h-16 rounded-2xl object-cover" alt="{{ $randTrend->title }}">
                        <div class="flex flex-col">
                            <h2 class="text-white/80 font-medium">{{ $randTrend->title }}</h2>
                            <p class="text-orange-400/60 text-sm">{{ Str::limit($randTrend->body, 50) }}</p>
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

</script>
