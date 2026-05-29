@extends('layouts.dashboard')

@section('title', 'Cards')

@section('content')
    <div class="flex flex-col lg:grid lg:grid-cols-4 gap-6 relative">
        <div class="flex flex-col gap-3 relative">
            <div class="col-span-1 p-4 bg-green-400/10 h-fit rounded-2xl">
                <h3 class="font-semibold text-white/70">Your Profile</h3>
                <div class="mt-3 text-white/60">
                    <p class="flex items-center gap-2">
                        <span
                            class="relative text-orange-400/70 text-sm font-medium pr-3 after:absolute after:right-0 after:w-1 after:h-3 after:bg-orange-400 after:rounded-lg flex items-center">
                            Events
                        </span>
                        <span>
                            {{ $stats['events_count'] }}
                        </span>
                    </p>
                    <p class="flex items-center gap-2">
                        <span
                            class="relative text-orange-400/70 text-sm font-medium pr-3 after:absolute after:right-0 after:w-1 after:h-3 after:bg-orange-400 after:rounded-lg flex items-center">
                            Trends
                        </span>
                        <span>
                            {{ $stats['trends_count'] }}
                    </p>
                    </span>
                    <p class="flex items-center gap-2">
                        <span
                            class="relative text-orange-400/70 text-sm font-medium pr-3 after:absolute after:right-0 after:w-1 after:h-3 after:bg-orange-400 after:rounded-lg flex items-center">
                            Following
                        </span>
                        <span>
                            {{ $stats['followers_count'] }}
                        </span>
                    </p>
                    <p class="flex items-center gap-2">
                        <span
                            class="relative text-orange-400/70 text-sm font-medium pr-3 after:absolute after:right-0 after:w-1 after:h-3 after:bg-orange-400 after:rounded-lg flex items-center">
                            Saved
                        </span>
                        <span>
                            {{ $stats['saved_count'] }}
                        </span>
                    </p>
                </div>
            </div>
            <a href="#" id="editProfileBtn">
                <div
                    class="flex-1 h-fit px-3 py-2.5 rounded-lg bg-orange-400 text-black text-[10px] font-bold uppercase tracking-wider hover:bg-orange-300 transition-all text-center">
                    Edit profile
                </div>
            </a>

            <div class="col-span-1 p-2 bg-white/5 border border-white/5 rounded-2xl">
                <div class="flex items-center gap-3">
                    @php $hasExperience = $activeExperiences->count() > 0; @endphp
                    <div class="relative w-13 h-13 flex items-center justify-center">
                        <!-- Animated Gradient Ring -->
                        <div
                            class="{{ $hasExperience ? 'absolute inset-0 rounded-full bg-linear-to-tr from-purple-500 to-green-400 animate-spin [animation-duration:5s]' : '' }}">
                        </div>

                        <!-- Inner Mask & Image -->
                        <div
                            class="relative w-12 h-12 bg-white dark:bg-gray-900 rounded-full p-0.5 flex items-center justify-center">
                            <img src="{{ Auth::user()->profile_pic ? asset('storage/' . Auth::user()->profile_pic) : asset('default.png') }}"
                                class="w-full h-full rounded-full object-cover" alt="Profile photo">
                        </div>
                    </div>


                    <div class="flex flex-col">
                        <span class="text-white/70 text-sm font-medium">Experiences</span>
                        <span class="text-orange-400/80 text-xs font-mono">
                            {{ $hasExperience ? $activeExperiences->count() . ' active' : 'No active experiences' }}
                        </span>
                    </div>
                </div>

                <form action="{{ route('experiences.store') }}" method="POST" enctype="multipart/form-data"
                    class="mt-4 flex flex-col gap-3">
                    @csrf
                    <input type="file" name="media" accept="image/*,video/*" required
                        class="w-full p-2.5 rounded-lg bg-white/5 outline outline-white/20 text-orange-400/70 text-xs font-semibold">
                    <input type="text" name="caption" maxlength="255"
                        placeholder="Say something about this experience..."
                        class="w-full p-2.5 rounded-lg bg-white/5 outline outline-white/20 text-orange-400/70 text-xs font-semibold placeholder-orange-400/70">
                    <button type="submit"
                        class="w-full bg-orange-400 text-black/90 text-xs font-bold font-mono p-2.5 rounded-3xl hover:bg-orange-500 transition">
                        Share Experience
                    </button>
                </form>
            </div>

            @if (Auth::user()->organizer)
                <a href="{{ route('organizer.settings') }}" class="">
                    <div
                        class="flex-1 h-fit px-3 py-2.5 rounded-lg text-black text-[10px] font-bold uppercase tracking-wider hover:bg-orange-300 transition-all bg-green-400/70 text-center">
                        Manage Organizer
                    </div>
                </a>
            @endif

            {{-- Modal Overlay --}}
            <div id="editProfileModal"
                class="fixed inset-0 bg-black/30 backdrop-blur-sm flex items-center justify-center z-50 hidden">
                <div
                    class="bg-black/85 bg-[url(/public/bg-img.png)] bg-cover bg-center bg-fixed  bg-blend-multiply border border-green-400/30 backdrop-blur-[1px] w-full max-w-lg p-4 shadow-xl relative">

                    {{-- Close Button --}}
                    <button id="closeModal"
                        class="absolute top-3 right-3 text-white/60 hover:text-white text-xl">&times;</button>

                    <h2 class="text-xl font-bold mb-4 text-orange-400">Edit Profile</h2>

                    <form action="{{ route('user.dashboard.updateProfile') }}" method="POST" enctype="multipart/form-data"
                        class="flex flex-col gap-3">

                        @csrf
                        @method('PUT')

                        {{-- First Name --}}
                        <div class="grid grid-cols-2 gap-5">
                            <div class="flex flex-col gap-1 col-span-1">
                                <label class="text-white/60 font-medium ml-1 text-sm">First Name</label>
                                <input type="text" name="first_name" value="{{ Auth::user()->first_name }}"
                                    class="w-full p-3 rounded-xl bg-[#b0a6df]/10 outline outline-[#b0a6df]/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70">
                                @error('first_name')
                                    <p class="text-red-400 text-xs">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Last Name --}}
                            <div class="flex flex-col gap-1 col-span-1">
                                <label class="text-white/60 font-medium ml-1 text-sm">Last Name</label>
                                <input type="text" name="last_name" value="{{ Auth::user()->last_name }}"
                                    class="w-full p-3 rounded-xl bg-[#b0a6df]/10 outline outline-[#b0a6df]/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70">
                                @error('last_name')
                                    <p class="text-red-400 text-xs">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-5">
                            {{-- Email --}}
                            <div class="flex flex-col gap-1">
                                <label class="text-white/60 font-medium ml-1 text-sm">Email</label>
                                <input type="email" name="email" value="{{ Auth::user()->email }}"
                                    class="w-full p-3 rounded-xl bg-[#b0a6df]/10 outline outline-[#b0a6df]/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70">
                                @error('email')
                                    <p class="text-red-400 text-xs">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Username --}}
                            <div class="flex flex-col gap-1">
                                <label class="text-white/60 font-medium ml-1 text-sm">Username</label>
                                <input type="text" name="username" value="{{ Auth::user()->username }}"
                                    class="w-full p-3 rounded-xl bg-[#b0a6df]/10 outline outline-[#b0a6df]/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70">
                                @error('username')
                                    <p class="text-red-400 text-xs">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>


                        {{-- Bio --}}
                        <div class="flex flex-col gap-1">
                            <label class="text-white/60 font-medium ml-1 text-sm">Bio</label>
                            <textarea name="bio" rows="3"
                                class="w-full p-3 rounded-xl bg-[#b0a6df]/10 outline outline-[#b0a6df]/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70">{{ Auth::user()->bio }}</textarea>
                            @error('bio')
                                <p class="text-red-400 text-xs">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Profile Picture --}}
                        <div class="flex flex-col gap-1">
                            <label class="text-white/60 font-medium ml-1 text-sm">Profile Picture</label>
                            <div class="flex justify-between items-center gap-3">
                                <input type="file" name="profile_pic"
                                    class="w-full flex-1 p-3 rounded-xl bg-[#b0a6df]/10 outline outline-[#b0a6df]/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70">

                                {{-- Current Photo Preview --}}
                                @if (Auth::user()->profile_pic)
                                    <div class="bg-orange-400 p-0.5 rounded-full border border-orange-400/30">
                                        <img src="{{ asset('storage/' . Auth::user()->profile_pic) }}"
                                            class="w-10 h-10 rounded-full object-cover">
                                    </div>
                                @endif

                            </div>

                            @error('profile_pic')
                                <p class="text-red-400 text-xs">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Save Button --}}
                        <button type="submit"
                            class="mt-4 w-full bg-orange-400 text-black/90 font-mono font-medium p-2 rounded-3xl hover:bg-orange-500 transition">
                            Save Changes
                        </button>
                    </form>
                </div>
            </div>



        </div>

        <div class="col-span-12 lg:col-span-3">
            <div class="mb-6">
                <h3
                    class="text-xs p-1 font-mono font-medium bg-orange-400 rounded-2xl w-fit text-black/90 mb-3 tracking-tighter">
                    Your Experiences</h3>
                @if ($experiences->isEmpty())
                    <div class="p-6 bg-green-400/5 border border-dashed border-green-400/20 rounded-3xl text-center">
                        <p class="text-zinc-500 text-sm">No experiences yet. Share your first one!</p>
                    </div>
                @else
                    @php
                        $myExperiencesPayload = $experiences
                            ->map(function ($exp) {
                                return [
                                    'id' => $exp->id,
                                    'media_url' => asset('storage/' . $exp->media_path),
                                    'media_type' => $exp->media_type,
                                    'caption' => $exp->caption,
                                ];
                            })
                            ->values();
                    @endphp
                    <div class="flex flex-wrap gap-4">
                        @foreach ($experiences as $exp)
                           <div class="flex flex-col items-center gap-2 w-24">
    <button type="button"
        class="relative w-18 h-18 flex items-center justify-center rounded-full experience-my cursor-pointer overflow-hidden"
        data-exp-id="{{ $exp->id }}">
        
        <!-- Animated Gradient Ring Layer -->
        <div class="absolute inset-0 bg-gradient-to-tr from-purple-500 via-green-500 to-yellow-400 animate-spin [animation-duration:3s]"></div>
        
        <!-- Inner Mask & Media Content Container -->
        <div class="relative w-[calc(100%-4px)] h-[calc(100%-4px)] bg-white dark:bg-gray-900 rounded-full flex items-center justify-center">
            @if ($exp->media_type === 'video')
                <video src="{{ asset('storage/' . $exp->media_path) }}"
                    class="w-16 h-16 rounded-full object-cover" muted loop
                    playsinline></video>
            @else
                <img src="{{ asset('storage/' . $exp->media_path) }}"
                    class="w-16 h-16 rounded-full object-cover" alt="Experience media">
            @endif
        </div>
    </button>
    
    <span class="text-[10px] text-white/60 text-center line-clamp-1">
        {{ $exp->caption ?: 'Experience' }}
    </span>
</div>

                        @endforeach
                    </div>

                    {{-- My Experiences Viewer --}}
                    <div id="experience-viewer-my"
                        class="fixed inset-0 bg-black/80 hidden items-center justify-center z-50">
                        <div
                            class="w-full max-w-md bg-black/85 bg-[url(/public/bg-img.png)] bg-cover bg-center bg-fixed  bg-blend-multiply border border-green-400/30 backdrop-blur-[1px] overflow-hidden">
                            <div class="p-3">
                                <div id="experience-progress-my" class="flex gap-1 w-full"></div>
                                <div class="mt-3 flex items-center justify-between">
                                    <div class="flex items-center gap-2 min-w-0">
                                        <x-user-avatar :user="Auth::user()"
                                            size="size-7 rounded-full border border-orange-400/30" />
                                        <span
                                            class="text-xs text-white/80 font-bold truncate">{{ Auth::user()->first_name }}
                                            {{ Auth::user()->last_name }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <form action="{{ route('experiences.destroy', $exp) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-[10px] bg-red-500/20 border border-red-500/30 text-red-400 rounded-2xl px-2 h-6 hover:bg-red-500/30 transition">
                                                Delete
                                            </button>
                                        </form>
                                        <button id="toggle-pause-my" type="button"
                                            class="size-6.5 bg-black/60 border border-green-400/20 text-white rounded-full flex items-center justify-center hover:bg-orange-400 hover:text-black transition-colors backdrop-blur-lg">
                                            <i id="toggle-pause-my-icon" class="fas fa-pause text-xs"></i>
                                        </button>
                                        <button id="close-experiences-my" type="button"
                                            class="size-6 rounded-full flex items-center justify-center bg-orange-400 text-white/70 hover:text-white px-2">
                                            <i class="fa-solid fa-xmark text-xs text-black"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="relative w-full h-[460px] bg-black">
                                <img id="experience-image-my" class="w-full h-full object-cover hidden" alt="Experience">
                                <video id="experience-video-my" class="w-full h-full object-cover hidden"
                                    playsinline></video>
                                <button id="prev-experience-my" type="button"
                                    class="absolute left-0 top-0 w-1/3 h-full"></button>
                                <button id="next-experience-my" type="button"
                                    class="absolute right-0 top-0 w-1/3 h-full"></button>
                            </div>
                            <div class="p-3 text-white/70 text-sm" id="experience-caption-my"></div>
                        </div>
                    </div>

                    <script>
                        (function() {
                            const myExperiences = @json($myExperiencesPayload);
                            const viewer = document.getElementById('experience-viewer-my');
                            const closeBtn = document.getElementById('close-experiences-my');
                            const img = document.getElementById('experience-image-my');
                            const video = document.getElementById('experience-video-my');
                            const caption = document.getElementById('experience-caption-my');
                            const progress = document.getElementById('experience-progress-my');
                            const prev = document.getElementById('prev-experience-my');
                            const next = document.getElementById('next-experience-my');
                            const togglePause = document.getElementById('toggle-pause-my');
                            const togglePauseIcon = document.getElementById('toggle-pause-my-icon');

                            let currentIndex = 0;
                            let timer = null;
                            let paused = false;
                            let remainingMs = 0;
                            let startedAt = 0;
                            let currentDurationMs = 5000;

                            function clearTimer() {
                                if (timer) clearTimeout(timer);
                                timer = null;
                            }

                            function buildProgress() {
                                progress.innerHTML = '';
                                myExperiences.forEach((_, i) => {
                                    const bar = document.createElement('div');
                                    bar.className = 'h-1 flex-1 bg-white/10 rounded-full overflow-hidden';
                                    const fill = document.createElement('div');
                                    fill.className = 'h-full bg-orange-400/80 transition-all';
                                    fill.style.width = i < currentIndex ? '100%' : '0%';
                                    bar.appendChild(fill);
                                    progress.appendChild(bar);
                                });
                            }

                            function setProgress(durationMs) {
                                const bars = progress.querySelectorAll('div > div');
                                if (!bars[currentIndex]) return;
                                bars[currentIndex].style.transition = `width ${durationMs}ms linear`;
                                requestAnimationFrame(() => {
                                    bars[currentIndex].style.width = '100%';
                                });
                            }

                            function scheduleNext(durationMs) {
                                currentDurationMs = durationMs;
                                remainingMs = durationMs;
                                startedAt = Date.now();
                                clearTimer();
                                timer = setTimeout(() => showExperience(currentIndex + 1), durationMs);
                            }

                            function pause() {
                                if (paused) return;
                                paused = true;
                                const elapsed = Date.now() - startedAt;
                                remainingMs = Math.max(0, currentDurationMs - elapsed);
                                clearTimer();
                                if (!video.classList.contains('hidden')) video.pause();
                                if (togglePauseIcon) togglePauseIcon.className = 'fas fa-play text-xs';
                            }

                            function resume() {
                                if (!paused) return;
                                paused = false;
                                startedAt = Date.now();
                                clearTimer();
                                if (!video.classList.contains('hidden')) video.play();
                                setProgress(remainingMs);
                                timer = setTimeout(() => showExperience(currentIndex + 1), remainingMs);
                                if (togglePauseIcon) togglePauseIcon.className = 'fas fa-pause text-xs';
                            }

                            function showExperience(index) {
                                if (!myExperiences.length) return;
                                if (index < 0) index = myExperiences.length - 1;
                                if (index >= myExperiences.length) index = 0;
                                currentIndex = index;
                                paused = false;
                                if (togglePauseIcon) togglePauseIcon.className = 'fas fa-pause text-xs';

                                const exp = myExperiences[currentIndex];
                                caption.textContent = exp.caption || '';

                                img.classList.add('hidden');
                                video.classList.add('hidden');
                                video.pause();
                                video.removeAttribute('src');

                                buildProgress();

                                if (exp.media_type === 'video') {
                                    video.src = exp.media_url;
                                    video.classList.remove('hidden');
                                    video.play();
                                    video.onloadedmetadata = () => {
                                        const durationMs = Math.min((video.duration || 8) * 1000, 15000);
                                        setProgress(durationMs);
                                        scheduleNext(durationMs);
                                    };
                                    video.onended = () => showExperience(currentIndex + 1);
                                } else {
                                    img.src = exp.media_url;
                                    img.classList.remove('hidden');
                                    setProgress(5000);
                                    scheduleNext(5000);
                                }
                            }

                            document.querySelectorAll('.experience-my').forEach((btn) => {
                                btn.addEventListener('click', () => {
                                    const expId = parseInt(btn.getAttribute('data-exp-id'), 10);
                                    const idx = myExperiences.findIndex(e => e.id === expId);
                                    viewer.classList.remove('hidden');
                                    viewer.classList.add('flex');
                                    showExperience(idx === -1 ? 0 : idx);
                                });
                            });

                            closeBtn?.addEventListener('click', () => {
                                viewer.classList.add('hidden');
                                viewer.classList.remove('flex');
                                clearTimer();
                                video.pause();
                            });

                            prev?.addEventListener('click', () => showExperience(currentIndex - 1));
                            next?.addEventListener('click', () => showExperience(currentIndex + 1));

                            togglePause?.addEventListener('click', () => {
                                if (paused) resume();
                                else pause();
                            });
                        })();
                    </script>
                @endif
            </div>

            <h3
                class="text-xs p-1 font-mono font-medium bg-orange-400 rounded-2xl w-fit text-black/90 mb-3 tracking-tighter">
                Followed Organizers' Events</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                @forelse($followedOrganizersEvents as $ev)
                    <div class="w-full h-fit p-1 ">
                        <div class="w-full h-[100px] rounded-2xl bg-green-400/10 relative p-2">
                            <img src="{{ $ev->event_image ? asset('storage/' . $ev->event_image) : asset('default.png') }}"
                                onerror="this.onerror=null; this.src='{{ asset('default.png') }}';"
                                class="absolute mix-blend-lighten z-0 top-0 left-0 object-cover w-full h-full rounded-[20px]"
                                alt="{{ $ev->event_name }}" />

                            <div class="flex flex-col gap-2 z-10">
                                <div
                                    class="absolute bottom-1 right-1 flex items-center gap-1 bg-orange-400/80 rounded-3xl w-fit h-9 p-0.5">
                                    <!-- LIKE BUTTON -->
                                    <div class="flex items-center h-full gap-[3px]">
                                        <button
                                            class="like-btn cursor-pointer h-full w-8 flex items-center justify-center bg-black/90 border border-black/10 rounded-[50%] font-medium"
                                            data-event="{{ $ev->id }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-heart-icon lucide-heart 
         {{ Auth::check() && $ev->isLikedBy(Auth::user()) ? 'text-red-500' : 'text-orange-400/80' }}">
                                                <path
                                                    d="M2 9.5a5.5 5.5 0 0 1 9.591-3.676.56.56 0 0 0 .818 0A5.49 5.49 0 0 1 22 9.5c0 2.29-1.5 4-3 5.5l-5.492 5.313a2 2 0 0 1-3 .019L5 15c-1.5-1.5-3-3.2-3-5.5" />
                                            </svg>
                                        </button>

                                        <!-- Like count -->
                                        <span id="likes-count-{{ $ev->id }}"
                                            class="text-black text-sm font-medium after:ml-0.5 relative after:w-1 after:bg-black/90 after:rounded-xl after:h-3 flex items-center">
                                            {{ $ev->likes->count() }}
                                        </span>
                                    </div>
                                    <a href="{{ route('event.show', $ev->id) }}"
                                        class="h-full flex items-center justify-center px-3 z-30 cursor-pointer text-xs font-mono bg-black/90 border border-black/10 rounded-3xl font-medium text-orange-400/80">
                                        More
                                    </a>
                                </div>
                            </div>
                        </div>
                        <h1 class='text-white/80 z-10 text-sm font-medium mx-1 mt-1 line-clamp-1'>{{ $ev->event_name }}
                        </h1>
                        <p class='text-green-400/80 text-[10px] mx-1 font-mono'>by
                            {{ $ev->organizer?->business_name ?? 'Unknown' }}</p>
                    </div>
                @empty
                    <div
                        class="col-span-12 p-8 bg-green-400/5 border border-dashed border-green-400/20 rounded-3xl text-center">
                        <p class="text-zinc-500 text-sm mb-2">No events from organizers you follow.</p>
                        <a href="{{ route('organizers') }}"
                            class="text-orange-400 text-xs font-bold hover:underline">Find organizers to follow</a>
                    </div>
                @endforelse
            </div>


        </div>



        {{-- Modal Script --}}
        <script>
            const modal = document.getElementById('editProfileModal');
            const openBtn = document.getElementById('editProfileBtn');
            const closeBtn = document.getElementById('closeModal');

            openBtn.addEventListener('click', (e) => {
                e.preventDefault();
                modal.classList.remove('hidden');
            });

            closeBtn.addEventListener('click', () => {
                modal.classList.add('hidden');
            });

            window.addEventListener('click', (e) => {
                if (e.target === modal) modal.classList.add('hidden');
            });
        </script>
    @endsection
