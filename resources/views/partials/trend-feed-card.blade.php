@foreach($trends as $trend)
<div class="trend-card mb-8 bg-green-400/10 border border-green-400/5 transition-all hover:border-orange-400/20 shadow-xl" data-id="{{ $trend->id }}">
    {{-- Card Header: User Info --}}
    <div class="p-4 flex items-center justify-between border-b border-white/5">
        <div class="flex items-center gap-3">
            <div class="relative">
                <div class="size-10 rounded-full p-[2px] bg-gradient-to-tr from-orange-500 to-yellow-300">
                    <img src="{{ $trend->user->profile_pic ? asset('storage/'.$trend->user->profile_pic) : asset('default.png') }}" class="size-full rounded-full object-cover border-2 border-black" alt="{{ $trend->user->first_name }}">
                </div>
            </div>
            <div>
                <h3 class="text-white font-bold text-sm tracking-tight capitalize">{{ $trend->user->first_name }} {{ $trend->user->last_name }}</h3>
                <p class="text-[10px] text-zinc-500 font-mono">{{ $trend->created_at->diffForHumans() }}</p>
            </div>
        </div>
        <button class="text-zinc-500 hover:text-white transition-colors p-2">
            <i class="fa-solid fa-ellipsis"></i>
        </button>
    </div>

    {{-- Post Media Carousel (Instagram Style) --}}
    <div class="relative aspect-video overflow-hidden bg-black/50 group/media" 
         x-data="{ 
            currentIndex: 0, 
            mediaCount: {{ $trend->media->count() }},
            muted: true,
            next() { this.currentIndex = (this.currentIndex + 1) % this.mediaCount },
            prev() { this.currentIndex = (this.currentIndex - 1 + this.mediaCount) % this.mediaCount }
         }" 
         x-init="
            let observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (!entry.isIntersecting) {
                        const videos = $el.querySelectorAll('video');
                        videos.forEach(v => { v.muted = true; });
                        muted = true;
                    }
                });
            }, { threshold: 0.5 });
            observer.observe($el);
         ">
        
        {{-- Media Slides --}}
        <div class="flex transition-transform duration-500 ease-out h-full" 
             :style="'transform: translateX(-' + (currentIndex * 100) + '%)'">
            
            @forelse($trend->media as $media)
                <div class="w-full h-full flex-shrink-0 relative">
                    @if($media->type === 'video')
                        <video src="{{ asset('storage/'.$media->path) }}" 
                               class="w-full h-full object-cover" 
                               autoplay muted loop playsinline 
                               :x-ref="'videoPlayer' + {{ $loop->index }}"></video>
                    @else
                        <img src="{{ asset('storage/'.$media->path) }}" 
                             class="w-full h-full object-cover transition-transform duration-700 opacity-90 group-hover/media:opacity-100" 
                             alt="{{ $trend->title }}">
                    @endif
                </div>
            @empty
                {{-- Fallback for existing trends without media records yet --}}
                <div class="w-full h-full flex-shrink-0">
                    @if($trend->is_video)
                        <video src="{{ $trend->first_media_url }}" class="w-full h-full object-cover" autoplay muted loop playsinline></video>
                    @else
                        <img src="{{ $trend->first_media_url }}" class="w-full h-full object-cover" alt="{{ $trend->title }}">
                    @endif
                </div>
            @endforelse
        </div>

        {{-- Navigation Arrows (Visible on Hover) --}}
        <template x-if="mediaCount > 1">
            <div class="absolute inset-0 flex items-center justify-between px-2 opacity-0 group-hover/media:opacity-100 transition-opacity pointer-events-none">
                <button @click="prev()" class="pointer-events-auto bg-black/40 hover:bg-black/60 text-white p-2 rounded-full backdrop-blur-sm transition-all">
                    <i class="fa-solid fa-chevron-left text-xs"></i>
                </button>
                <button @click="next()" class="pointer-events-auto bg-black/40 hover:bg-black/60 text-white p-2 rounded-full backdrop-blur-sm transition-all">
                    <i class="fa-solid fa-chevron-right text-xs"></i>
                </button>
            </div>
        </template>

        {{-- Navigation Dots --}}
        <template x-if="mediaCount > 1">
            <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-1.5 z-10">
                <template x-for="i in mediaCount" :key="i-1">
                    <div class="size-1.5 rounded-full transition-all duration-300" 
                         :class="currentIndex === (i-1) ? 'bg-orange-400 w-4' : 'bg-white/40'"></div>
                </template>
            </div>
        </template>

        {{-- Mute Toggle (Only if video is in the carousel) --}}
        <button @click="
            const videos = $el.closest('.trend-card').querySelectorAll('video');
            videos.forEach(v => { v.muted = !v.muted; });
            muted = !muted;
        " class="absolute top-4 right-4 bg-black/60 backdrop-blur-md p-2 rounded-full text-white/90 hover:bg-black/80 transition-all z-10 opacity-0 group-hover/media:opacity-100">
            <svg x-show="muted" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5" /><line x1="23" x2="17" y1="9" y2="15" /><line x1="17" x2="23" y1="9" y2="15" />
            </svg>
            <svg x-show="!muted" x-cloak xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5" /><path d="M15.54 8.46a5 5 0 0 1 0 7.07" /><path d="M19.07 4.93a10 10 0 0 1 0 14.14" />
            </svg>
        </button>

        @if($trend->event_id)
        <div class="absolute bottom-4 left-4 z-10">
            <a href="{{ route('event.show', $trend->event_id) }}" class="flex items-center gap-2 bg-black/60 backdrop-blur-md px-3 py-1.5 rounded-full border border-white/10 text-xs text-white/90 hover:bg-orange-400 hover:text-black transition-all">
                <i class="fa-solid fa-calendar-day text-orange-400"></i>
                <span class="font-bold">Attend Event</span>
                <i class="fa-solid fa-chevron-right text-[10px]"></i>
            </a>
        </div>
        @endif
    </div>

    {{-- Interactions --}}
    <div class="p-4">
        <div class="flex items-center gap-3 mb-3">
            <div class="flex items-center gap-1.5">
                <button onclick="toggleLike({{ $trend->id }}, this)" class="interaction-btn group/like {{ $trend->is_liked ? 'text-orange-400' : 'text-white/80' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 24 24" fill="{{ $trend->is_liked ? 'oklch(75% 0.183 55.934)' : 'none' }}" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-heart-icon lucide-heart">
                        <path d="M2 9.5a5.5 5.5 0 0 1 9.591-3.676.56.56 0 0 0 .818 0A5.49 5.49 0 0 1 22 9.5c0 2.29-1.5 4-3 5.5l-5.492 5.313a2 2 0 0 1-3 .019L5 15c-1.5-1.5-3-3.2-3-5.5" /></svg>
                </button>
                <p class="text-sm font-bold text-white/80">
                    <span id="likes-count-{{ $trend->id }}">{{ $trend->likes_count }}</span>
                </p>
            </div>
            <button onclick="toggleComments({{ $trend->id }})" class="interaction-btn group/comment text-white/80">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-message-circle-icon lucide-message-circle">
                    <path d="M2.992 16.342a2 2 0 0 1 .094 1.167l-1.065 3.29a1 1 0 0 0 1.236 1.168l3.413-.998a2 2 0 0 1 1.099.092 10 10 0 1 0-4.777-4.719" /></svg>
            </button>
            <button onclick="openShareModal({{ $trend->id }}, '{{ addslashes($trend->title) }}', '{{ route('trends.show', $trend->id) }}')" class="interaction-btn group/share text-white/80">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-send-icon lucide-send">
                    <path d="M14.536 21.686a.5.5 0 0 0 .937-.024l6.5-19a.496.496 0 0 0-.635-.635l-19 6.5a.5.5 0 0 0-.024.937l7.93 3.18a2 2 0 0 1 1.112 1.11z" />
                    <path d="m21.854 2.147-10.94 10.939" /></svg>
            </button>
        </div>

        <div class="space-y-2">


            <div x-data="{ expanded: false }" class="text-sm">
                <span class="font-semibold text-orange-400/90 mr-1 capitalize">{{ $trend->user->first_name }}:</span>
                <span class="text-white/60 font-light leading-relaxed" x-show="!expanded">{{ Str::limit($trend->body, 120) }}</span>
                <span class="text-white/60 font-light leading-relaxed" x-show="expanded" x-cloak>{{ $trend->body }}</span>

                @if(strlen($trend->body) > 120)
                <button @click="expanded = !expanded" class="text-orange-400/80 hover:text-orange-400 text-xs font-bold ml-1 transition-colors" x-text="expanded ? 'show less' : '...more'"></button>
                @endif
            </div>

            {{-- Comment Section --}}
            <div id="comments-section-{{ $trend->id }}" class="mt-4 pt-4 border-t border-white/5 space-y-3 hidden">
                <div id="comments-list-{{ $trend->id }}" class="space-y-3 max-h-40 overflow-y-auto pr-2 custom-scrollbar">
                    @foreach($trend->comments->take(5) as $comment)
                    <div class="flex gap-2 text-xs">
                        <span class="font-bold text-white shrink-0 capitalize">{{ $comment->user->first_name }}:</span>
                        <span class="text-zinc-400">{{ $comment->comment }}</span>
                    </div>
                    @endforeach
                </div>

                {{-- Inline Comment Input --}}
                <div class="flex gap-2 mt-3 pt-3 border-t border-white/5">
                    <input type="text" id="comment-input-{{ $trend->id }}" placeholder="Add a comment..." class="flex-1 bg-transparent text-xs text-white outline-none placeholder-zinc-600">
                    <button onclick="submitComment({{ $trend->id }})" class="text-orange-400 text-xs font-bold hover:text-orange-300 transition-colors uppercase tracking-tight">Post</button>
                </div>
            </div>

            @if($trend->comments_count > 0)
            <button id="view-comments-btn-{{ $trend->id }}" onclick="toggleComments({{ $trend->id }})" class="text-zinc-500 text-[10px] uppercase font-bold tracking-widest hover:text-orange-400/80 transition-colors pt-2 block">
                View all {{ $trend->comments_count }} comments
            </button>
            @endif
        </div>
    </div>
</div>
@endforeach
