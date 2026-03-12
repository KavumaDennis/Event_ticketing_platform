@extends('layouts.dashboard')

@section('title','Trends Feed')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex flex-col lg:flex-row gap-8">
        
        {{-- LEFT COLUMN: Trends Feed --}}
        <div class="w-full lg:flex-1 min-w-0" x-data="{ activeTab: 'discovery' }">

            {{-- SUCCESS MESSAGE --}}
            @if (session('success'))
            <div class="p-3 mb-6 bg-green-500/20 border border-green-500/50 text-green-400 rounded-2xl text-sm font-mono text-center animate-pulse">
                {{ session('success') }}
            </div>
            @endif

            @include('partials.experiences-following', ['experienceUsers' => $experienceUsers, 'seenExperienceIds' => $seenExperienceIds])

            {{-- FEED TABS --}}
            <div class="flex items-center gap-4 mb-8 sticky top-0 z-10 bg-orange-400/80 backdrop-blur-xl p-0.5 rounded-3xl">
                <button @click="activeTab = 'discovery'" 
                        :class="activeTab === 'discovery' ? 'bg-black/90 text-orange-400 font-bold' : 'text-black'"
                        class="flex-1 py-3 rounded-3xl text-xs uppercase font-mono tracking-widest transition-all duration-300">
                    Discover trends
                </button>
                <button @click="activeTab = 'my-posts'" 
                        :class="activeTab === 'my-posts' ? 'bg-black/90 text-orange-400 font-bold' : 'text-black'"
                        class="flex-1  py-3 rounded-3xl text-xs uppercase font-mono tracking-widest transition-all duration-300">
                    My Posts
                </button>
                <a href="{{ route('trends.create') }}" class="size-10 bg-black/90 border border-green-400/20 rounded-full flex items-center justify-center text-orange-400 hover:bg-green-800/80 hover:text-black transition-all">
                    <i class="fa-solid fa-plus uppercase"></i>
                </a>
            </div>

            {{-- DISCOVERY FEED (Infinite Scroll) --}}
            <div x-show="activeTab === 'discovery'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" id="discovery-feed">
                <div id="feed-container">
                    @include('partials.trend-feed-card', ['trends' => $trends])
                </div>

                {{-- Loading Indicator --}}
                <div id="loading-trigger" class="py-10 flex flex-col items-center justify-center gap-3">
                    <div id="loader" class="size-6 border-2 border-orange-400/20 border-t-orange-400 rounded-full animate-spin"></div>
                    <p id="no-more-msg" class="hidden text-zinc-600 text-xs font-mono uppercase tracking-widest">End of the road. Create your own trend!</p>
                </div>
            </div>

            {{-- MY POSTS (Grid with Management) --}}
            <div x-show="activeTab === 'my-posts'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <div class="grid grid-cols-2 gap-4">
                    @forelse($myTrends as $trend)
                    <div class="group hover:border-orange-400/20 transition-all">
                        <div class="relative h-30  w-full aspect-square rounded-2xl overflow-hidden mb-3">
                            @if($trend->is_video)
                            <video src="{{ $trend->first_media_url }}" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all" autoplay muted loop playsinline></video>
                            @else
                            <img src="{{ $trend->first_media_url }}" 
                                 class="w-full h-full object-cover transition-all" alt="{{ $trend->title }}">
                            @endif
                        </div>
                        <h4 class="text-white/80 font-bold text-sm line-clamp-1 mb-2">{{ $trend->title }}</h4>
                        <div class="flex items-center justify-between gap-2">
                            <div class="flex gap-1">
                                <button onclick='openEditModal({!! json_encode(["id" => $trend->id, "title" => $trend->title, "body" => $trend->body]) !!})' class="size-7 rounded-lg flex items-center justify-center bg-orange-400 text-black transition-all">
                                    <i class="fa-solid fa-pen-to-square text-[10px]"></i>
                                </button>
                                <form action="{{ route('trends.destroy', $trend->id) }}" method="POST" onsubmit="return confirm('Delete this trend?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="size-7 rounded-lg flex items-center justify-center bg-red-500 text-white transition-all">
                                        <i class="fa-solid fa-trash text-[10px]"></i>
                                    </button>
                                </form>
                            </div>
                            <a href="{{ route('trends.show', $trend->id) }}" class="text-xs p-1 font-mono font-medium bg-orange-400 rounded-2xl w-fit text-black/90">View Details</a>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-2 py-20 text-center opacity-40">
                        <i class="fa-solid fa-bullhorn text-4xl mb-4 text-zinc-700"></i>
                        <p class="text-sm font-mono uppercase tracking-widest text-zinc-500">You haven't posted any trends yet</p>
                    </div>
                    @endforelse
                </div>
            </div>

        </div>

        {{-- RIGHT COLUMN: Sidebar (Creators) --}}
        <div class="w-full lg:w-96 shrink-0 hidden lg:block">
            <div class="sticky top-0">
                <div class="flex flex-col gap-6">
                    <div class="">
                        {{-- Search Bar for Trends --}}
                        <div class="mb-6 relative group">
                            <input type="text" id="trends-search" placeholder="Search trends..." 
                                   class="w-full bg-green-400/10 border border-green-400/10 rounded-2xl py-3 px-11 text-sm text-white placeholder-zinc-500 focus:outline-none focus:border-orange-400/30 transition-all">
                            <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-zinc-500 group-focus-within:text-orange-400/70 transition-colors"></i>
                            <div id="search-loader" class="hidden absolute right-4 top-1/2 -translate-y-1/2">
                                <div class="size-4 border-2 border-orange-400/20 border-t-orange-400 rounded-full animate-spin"></div>
                            </div>
                        </div>

                        <h2 class="text-xs p-1 font-mono font-medium bg-orange-400 rounded-2xl w-fit text-black/90 mb-3">Creators to Follow</h2>
                        <div class="flex flex-col gap-2">
                            @forelse($topCreators as $creator)
                            <div class="flex items-center justify-between gap-3 h-fit p-3 bg-green-400/10 border border-green-400/5 rounded-2xl hover:border-orange-400/30 transition">
                                <div class="flex items-center gap-3 flex-1 min-w-0">
                                    <div class="w-10 h-10 rounded-full bg-orange-400 border border-green-400/10 p-0.5 flex-shrink-0">
                                        <img src="{{ $creator->profile_pic ? asset('storage/'.$creator->profile_pic) : asset('default.png') }}" onerror="this.onerror=null; this.src='{{ asset('default.png') }}';" class='w-full h-full rounded-full object-cover' alt="{{ $creator->first_name }}" />
                                    </div>
                                    <div class="flex flex-col min-w-0">
                                        <a href="{{ route('user.profile', $creator->id) }}" class='text-orange-400/90 font-medium text-sm hover:text-orange-400 transition truncate'>
                                            {{ $creator->first_name }} {{ $creator->last_name }}
                                        </a>
                                        <p class='text-white/50 font-mono text-xs'>{{ $creator->trends_count }} trends</p>
                                    </div>
                                </div>
                                <form action="{{ route('user.follow', $creator->id) }}" method="POST">
                                    @csrf
                                    <button class="px-3 py-1.5 bg-white/5 border border-white/20 text-orange-400 text-center rounded-lg flex items-center justify-center gap-2 hover:text-white duration-150 transition-colors text-[10px] font-bold uppercase flex-shrink-0">
                                        Follow
                                    </button>
                                </form>
                            </div>
                            @empty
                            <div class="text-center py-4">
                                <p class="text-white/60 text-sm">No creators found</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- EDIT MODAL (Unchanged Logic, Updated UI) --}}
<div id="editModal" class="hidden fixed inset-0 bg-black/80 backdrop-blur-md flex items-center justify-center z-50 p-4">
    <div class="bg-black/85 bg-[url(/public/bg-img.png)] bg-cover bg-center bg-fixed  bg-blend-multiply border border-green-400/30 backdrop-blur-[1px] w-full max-w-lg p-6 shadow-2xl relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-orange-500 to-orange-500"></div>
        <button onclick="closeEditModal()" class="absolute top-4 right-4 text-zinc-500 hover:text-white transition-colors">
            <i class="fa-solid fa-xmark text-lg"></i>
        </button>

        <h2 class="text-xl font-bold mb-6 text-white tracking-tight">Update Post</h2>

        <form id="editForm" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-[10px] uppercase tracking-widest text-zinc-500 font-bold mb-1.5 ml-1">Title</label>
                <input type="text" id="edit_title" name="title" 
                       class="w-full p-3 rounded-xl bg-white/5 outline outline-white/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70" required>
            </div>
            <div>
                <label class="block text-[10px] uppercase tracking-widest text-zinc-500 font-bold mb-1.5 ml-1">Body</label>
                <textarea id="edit_body" name="body" rows="4"
                          class="w-full p-3 rounded-lg bg-white/5 outline outline-white/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70"></textarea>
            </div>
            <div>
                <label class="block text-[10px] uppercase tracking-widest text-zinc-500 font-bold mb-1.5 ml-1">Replace Media (Optional)</label>
                <input type="file" name="media[]" multiple
                       class="w-full bg-zinc-900 border border-white/5 rounded-2xl p-2.5 text-zinc-400 focus:border-orange-400/50 focus:outline-none transition-all text-xs">
            </div>
            <button class="w-full p-3 bg-black/80 border border-green-400/10 rounded-3xl font-mono text-white/70 text-center text-sm font-medium hover:bg-green-400/10 transition-all mt-4">
                Save Changes
            </button>
        </form>
    </div>
</div>

    </div>
</div>

{{-- SHARE MODAL --}}
<div id="shareModal" class="hidden fixed inset-0 bg-black/80 backdrop-blur-md flex items-center justify-center z-[60] p-4">
    <div class="bg-zinc-950 border border-white/10 w-full max-w-md p-6 rounded-[2.5rem] shadow-2xl relative">
        <button onclick="closeShareModal()" class="absolute top-4 right-4 text-zinc-500 hover:text-white transition-colors">
            <i class="fa-solid fa-xmark text-lg"></i>
        </button>

        <h2 class="text-xl font-bold mb-6 text-white tracking-tight">Share Trend</h2>

        {{-- Social Icons --}}
        <div class="grid grid-cols-4 gap-4 mb-8">
            <a id="share-whatsapp" href="#" target="_blank" class="flex flex-col items-center gap-2 group">
                <div class="size-12 rounded-2xl bg-green-500/10 flex items-center justify-center text-green-500 group-hover:bg-green-500 group-hover:text-black transition-all">
                    <i class="fa-brands fa-whatsapp text-xl"></i>
                </div>
                <span class="text-[10px] text-zinc-500 font-bold uppercase tracking-tighter">WhatsApp</span>
            </a>
            <a id="share-facebook" href="#" target="_blank" class="flex flex-col items-center gap-2 group">
                <div class="size-12 rounded-2xl bg-blue-600/10 flex items-center justify-center text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-all">
                    <i class="fa-brands fa-facebook-f text-xl"></i>
                </div>
                <span class="text-[10px] text-zinc-500 font-bold uppercase tracking-tighter">Facebook</span>
            </a>
            <a id="share-twitter" href="#" target="_blank" class="flex flex-col items-center gap-2 group">
                <div class="size-12 rounded-2xl bg-white/5 flex items-center justify-center text-white group-hover:bg-white group-hover:text-black transition-all">
                    <i class="fa-brands fa-x-twitter text-xl"></i>
                </div>
                <span class="text-[10px] text-zinc-500 font-bold uppercase tracking-tighter">Twitter</span>
            </a>
            <a id="share-email" href="#" class="flex flex-col items-center gap-2 group">
                <div class="size-12 rounded-2xl bg-orange-500/10 flex items-center justify-center text-orange-500 group-hover:bg-orange-500 group-hover:text-black transition-all">
                    <i class="fa-solid fa-envelope text-xl"></i>
                </div>
                <span class="text-[10px] text-zinc-500 font-bold uppercase tracking-tighter">Email</span>
            </a>
        </div>

        {{-- Internal Share (Friends) --}}
        <h3 class="text-xs font-bold text-zinc-500 uppercase tracking-widest mb-4 ml-1">Send to Friends</h3>
        <div class="max-h-60 overflow-y-auto pr-2 custom-scrollbar space-y-2">
            @forelse($friends as $friend)
            <div class="flex items-center justify-between p-3 bg-white/5 rounded-2xl border border-white/5 hover:border-orange-400/30 transition-all group">
                <div class="flex items-center gap-3">
                    <img src="{{ $friend->profile_pic ? asset('storage/'.$friend->profile_pic) : asset('default.png') }}" 
                         class="size-8 rounded-full border border-white/10" alt="{{ $friend->first_name }}">
                    <span class="text-sm font-medium text-white/90 capitalize">{{ $friend->first_name }} {{ $friend->last_name }}</span>
                </div>
                <button onclick="shareToInternalFriend({{ $friend->id }})" 
                        class="text-[10px] font-bold uppercase bg-orange-400/10 text-orange-400 px-3 py-1 rounded-full group-hover:bg-orange-400 group-hover:text-black transition-all">Send</button>
            </div>
            @empty
            <p class="text-zinc-600 text-xs text-center py-4 italic">Follow more people to share with them!</p>
            @endforelse
        </div>
    </div>
</div>

@push('scripts')
<script>
    // --- INFINITE SCROLL LOGIC ---
    let nextPageUrl = "{{ $trends->nextPageUrl() }}";
    const feedContainer = document.getElementById('feed-container');
    const loadingTrigger = document.getElementById('loading-trigger');
    const loader = document.getElementById('loader');
    const noMoreMsg = document.getElementById('no-more-msg');
    let isLoading = false;

    const observer = new IntersectionObserver((entries) => {
        if (entries[0].isIntersecting && nextPageUrl && !isLoading) {
            loadMoreTrends();
        }
    }, { 
        root: document.querySelector('main'),
        rootMargin: '100px',
        threshold: 0.1 
    });

    observer.observe(loadingTrigger);

    async function loadMoreTrends() {
        if (!nextPageUrl) return;
        
        isLoading = true;
        loader.classList.remove('hidden');
        
        try {
            const url = new URL(nextPageUrl);
            const searchParams = new URLSearchParams(window.location.search);
            if (searchParams.has('search')) {
                url.searchParams.set('search', searchParams.get('search'));
            }

            const response = await fetch(url.toString(), {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            const data = await response.json();
            
            if (data.html) {
                // Append content
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = data.html;
                while (tempDiv.firstChild) {
                    feedContainer.appendChild(tempDiv.firstChild);
                }
                
                nextPageUrl = data.next_page;
                
                if (!nextPageUrl) {
                    loader.classList.add('hidden');
                    noMoreMsg.classList.remove('hidden');
                }
            }
        } catch (error) {
            console.error('Error loading trends:', error);
        } finally {
            isLoading = false;
        }
    }

    // --- SEARCH LOGIC ---
    const searchInput = document.getElementById('trends-search');
    const searchLoader = document.getElementById('search-loader');
    let searchTimeout = null;

    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchLoader.classList.remove('hidden');
        
        searchTimeout = setTimeout(() => {
            const query = this.value.trim();
            performSearch(query);
        }, 500);
    });

    async function performSearch(query) {
        isLoading = true;
        try {
            const url = new URL(window.location.href);
            if (query) {
                url.searchParams.set('search', query);
            } else {
                url.searchParams.delete('search');
            }
            url.searchParams.set('page', 1);

            // Update URL bar without reloading
            window.history.replaceState({}, '', url.toString());

            const response = await fetch(url.toString(), {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            const data = await response.json();
            
            if (data.html !== undefined) {
                feedContainer.innerHTML = data.html;
                nextPageUrl = data.next_page;
                
                if (!nextPageUrl) {
                    loader.classList.add('hidden');
                    if (query && data.html === "") {
                         noMoreMsg.textContent = "No trends found matching your search.";
                    } else {
                         noMoreMsg.textContent = "End of the road. Create your own trend!";
                    }
                    noMoreMsg.classList.remove('hidden');
                } else {
                    loader.classList.remove('hidden');
                    noMoreMsg.classList.add('hidden');
                }
            }
        } catch (error) {
            console.error('Error performing search:', error);
        } finally {
            isLoading = false;
            searchLoader.classList.add('hidden');
        }
    }

    // --- INTERACTION LOGIC ---
    async function toggleLike(trendId, btn) {
        try {
            const response = await fetch(`/trends/${trendId}/like`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || window.csrfToken
                }
            });
            const data = await response.json();
            
            if (data.liked !== undefined) {
                const svg = btn.querySelector('svg');
                const likesCount = document.getElementById(`likes-count-${trendId}`);
                
                if (data.liked) {
                    // Liked: Fill heart, color button orange
                    if(svg) svg.setAttribute('fill', 'oklch(0.75 0.183 55.934)'); 
                    btn.classList.add('text-orange-400');
                    btn.classList.remove('text-white/80');
                } else {
                    // Unliked: Remove fill, white button
                    if(svg) svg.setAttribute('fill', 'none');
                    btn.classList.remove('text-orange-400');
                    btn.classList.add('text-white/80');
                }
                
                if (likesCount) likesCount.textContent = data.likes_count;
            }
        } catch (error) {
            console.error('Error toggling like:', error);
        }
    }

    function toggleComments(trendId) {
        const section = document.getElementById(`comments-section-${trendId}`);
        const btn = document.getElementById(`view-comments-btn-${trendId}`);
        section.classList.toggle('hidden');
        if (btn) btn.classList.toggle('hidden');
    }

    async function submitComment(trendId) {
        const input = document.getElementById(`comment-input-${trendId}`);
        const content = input.value.trim();
        if (!content) return;

        try {
            const response = await fetch(`/trends/${trendId}/comment`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.csrfToken
                },
                body: JSON.stringify({ comment: content })
            });
            const data = await response.json();

            if (data.id) {
                const list = document.getElementById(`comments-list-${trendId}`);
                const commentDiv = document.createElement('div');
                commentDiv.className = 'flex gap-2 text-xs opacity-0 translate-x-2 transition-all duration-300';
                const renderedComment = data.comment_html ?? data.comment;
                commentDiv.innerHTML = `<span class="font-bold text-white shrink-0 capitalize">${data.user_name}:</span> <span class="text-zinc-400">${renderedComment}</span>`;
                list.prepend(commentDiv);
                setTimeout(() => commentDiv.classList.remove('opacity-0', 'translate-x-2'), 10);
                input.value = '';
            }
        } catch (error) {
            console.error('Error submitting comment:', error);
        }
    }

    // --- SHARING LOGIC ---
    let currentShareId = null;

    function openShareModal(trendId, title, url) {
        currentShareId = trendId;
        const encodedUrl = encodeURIComponent(url);
        const encodedTitle = encodeURIComponent(title);

        document.getElementById('share-whatsapp').href = `https://api.whatsapp.com/send?text=${encodedTitle}%20${encodedUrl}`;
        document.getElementById('share-facebook').href = `https://www.facebook.com/sharer/sharer.php?u=${encodedUrl}`;
        document.getElementById('share-twitter').href = `https://twitter.com/intent/tweet?text=${encodedTitle}&url=${encodedUrl}`;
        document.getElementById('share-email').href = `mailto:?subject=${encodedTitle}&body=${encodedUrl}`;

        document.getElementById('shareModal').classList.remove('hidden');
    }

    function closeShareModal() {
        document.getElementById('shareModal').classList.add('hidden');
    }

    async function shareToInternalFriend(friendId) {
        if (!currentShareId) return;

        try {
            const response = await fetch('/trend/share', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.csrfToken
                },
                body: JSON.stringify({
                    trend_id: currentShareId,
                    friend_id: friendId
                })
            });
            const data = await response.json();

            if (data.success) {
                alert('Shared successfully!');
                closeShareModal();
            }
        } catch (error) {
            console.error('Error sharing post:', error);
        }
    }

    // --- MODAL LOGIC ---
    window.openEditModal = function (trend) {
        document.getElementById("edit_title").value = trend.title ?? "";
        document.getElementById("edit_body").value = trend.body ?? "";
        document.getElementById("editForm").action = "/trends/" + trend.id;
        document.getElementById("editModal").classList.remove("hidden");
    }

    window.closeEditModal = function () {
        document.getElementById("editModal").classList.add("hidden");
    }
</script>
@endpush
@endsection


