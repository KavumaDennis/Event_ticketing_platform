<x-layout>
    <style>
        .experience-ring {
            background: conic-gradient(from 180deg, rgba(251, 146, 60, 0.8), rgba(34, 197, 94, 0.8), rgba(251, 146, 60, 0.8));
            padding: 3px;
            border-radius: 999px;
        }
    </style>
    <div class="p-5">
        <!-- Profile Header -->
        <div x-data="{ showFollowers: false, showFollowing: false }">
            <div class="grid grid-cols-4 gap-10">
                <div class="col-span-1 h-fit grid grid-cols-3 p-3 rounded-3xl border-green-400/20 bg-green-400/10">
                    <div class="col-span-2 flex flex-col gap-3 items-center justify-center">
                        @php
                            $hasExperience = $experiences->count() > 0;
                        @endphp
                        <button type="button" id="open-experiences" class="experience-ring">
                            <img src="{{ $user->profile_photo_url }}" alt="" class='size-18 rounded-[50%] object-cover' />
                        </button>
                        <div class="">
                            <p class="text-white/40 text-xs font-mono font-light text-center">User</p>
                            <h1 class="text-white/60 text-center ">{{ $user->first_name . ' ' . $user->last_name }}</h1>
                        </div>
                    </div>
                    <div class="">
                        <div class="flex flex-col py-1">
                            <div class=" text-white text-xl">
                                {{ $user->followers()->count() }}
                            </div>
                            <p class="cursor-pointer text-white/60 text-sm flex items-baseline gap-1" @click="showFollowers = true">
                                <span class="text-orange-400/60">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users-icon lucide-users">
                                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                                        <path d="M16 3.128a4 4 0 0 1 0 7.744" />
                                        <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
                                        <circle cx="9" cy="7" r="4" /></svg>
                                </span>
                                <span class="font-mono">Followers</span>
                            </p>
                        </div>
                        <div class="flex flex-col py-1">
                            <div class=" text-white text-xl">
                                {{ $user->following()->count() }}
                            </div>

                            <p class="cursor-pointer text-white/60 text-sm flex items-baseline gap-1" @click="showFollowing = true">
                                <span class="text-orange-400/60">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user-round-check-icon lucide-user-round-check">
                                        <path d="M2 21a8 8 0 0 1 13.292-6" />
                                        <circle cx="10" cy="8" r="5" />
                                        <path d="m16 19 2 2 4-4" /></svg>
                                </span>
                                <span class="font-mono">Following</span>
                            </p>


                        </div>
                        <div class="flex flex-col py-1">
                            <h1 class="text-white text-xl">{{ $user->trends()->count() }}</h1>
                            <p class="text-white/60 text-sm flex items-baseline gap-1">
                                <span class="text-orange-400/60">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chart-no-axes-column-decreasing-icon lucide-chart-no-axes-column-decreasing">
                                        <path d="M5 21V3" />
                                        <path d="M12 21V9" />
                                        <path d="M19 21v-6" /></svg>
                                </span>
                                <span class="font-mono">Trends</span>
                            </p>
                        </div>



                    </div>
                </div>
                <div class="col-span-3 flex flex-col gap-10 p-5">
                    <div class="flex flex-col gap-3">
                        <div class="flex items-center">
                            <span class="pr-3 relative after:content-[''] flex items-center text-sm font-medium text-orange-400/70 after:bg-orange-400/80 after:absolute  after:w-[3px] after:h-[12px] after:rounded-lg after:right-0">
                                Phone
                            </span>
                            <span class="pl-3 text-white/60 font-mono font-light">0759160763</span>
                        </div>
                        <div class="flex items-center">
                            <span class="pr-3 relative after:content-[''] flex items-center text-sm font-medium text-orange-400/70 after:bg-orange-400/80 after:absolute  after:w-[3px] after:h-[12px] after:rounded-lg after:right-0">
                                Email
                            </span>
                            <span class="pl-3 text-white/60 font-mono font-light">{{ $user->email }}</span>
                        </div>

                        <div class="flex">
                            <span class="pr-3 relative after:content-[''] flex items-center h-fit  text-sm font-medium text-orange-400/70 after:bg-orange-400/80 after:absolute  after:w-[3px] after:h-[12px] after:rounded-lg after:right-0">
                                About
                            </span>
                            <span class="pl-3 text-sm text-white/60 font-mono font-light">{{ $user->bio ? $user->bio : 'No bio yet' }}</span>
                        </div>

                        @if(Auth::check() && Auth::id() == $user->id)
                        <div class="mt-5 p-4 bg-green-400/10 border border-green-400/20 rounded-2xl">
                            <h4 class="text-white/70 text-xs font-mono mb-3">Add Experience</h4>
                            <form action="{{ route('experiences.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-3">
                                @csrf
                                <input type="file" name="media" accept="image/*,video/*" required class="w-full p-2 rounded-2xl bg-[#b0a6df]/10 outline outline-[#b0a6df]/20 text-orange-400/70 text-xs font-semibold">
                                <input type="text" name="caption" maxlength="255" placeholder="Caption (optional)" class="w-full p-2 rounded-2xl bg-[#b0a6df]/10 outline outline-[#b0a6df]/20 text-orange-400/70 text-xs font-semibold placeholder-orange-400/70">
                                <button type="submit" class="w-fit bg-orange-400 text-black/90 font-mono font-medium px-4 py-2 rounded-3xl hover:bg-orange-500 transition">
                                    Share Experience
                                </button>
                            </form>
                        </div>
                        @endif

                        {{-- Referral System (Only for own profile) --}}
                        @if(Auth::check() && Auth::id() == $user->id)
                        <div class="mt-4 p-4 bg-orange-400/10 border border-orange-400/20 rounded-2xl flex flex-col gap-3">
                            <div class="flex justify-between items-center">
                                <h4 class="text-orange-400 text-xs font-bold uppercase tracking-widest">Affiliate Program</h4>
                                <span class="text-white font-mono text-sm">UGX {{ number_format($user->affiliate_earnings) }} Earned</span>
                            </div>
                            <div class="flex gap-2 items-center" x-data="{ 
                                link: '{{ route('show.signup') }}?ref={{ $user->referral_code }}',
                                copied: false,
                                copy() {
                                    navigator.clipboard.writeText(this.link);
                                    this.copied = true;
                                    setTimeout(() => this.copied = false, 2000);
                                }
                            }">
                                <input type="text" x-model="link" readonly class="flex-1 bg-black/40 border border-white/10 rounded-xl px-3 py-2 text-white/60 text-xs font-mono">
                                <button @click="copy" class="px-4 py-2 bg-orange-400 text-black rounded-xl text-xs font-bold uppercase hover:bg-white transition-all">
                                    <span x-text="copied ? 'Copied!' : 'Copy'"></span>
                                </button>
                            </div>
                            <p class="text-[10px] text-white/40 italic">Share this link and earn 5% commission on every ticket your referrals buy!</p>
                        </div>
                        @endif
                    </div>

                    <div class="flex items-end gap-5 h-full">
                        <div>
                            @auth
                            @if(auth()->id() != $user->id)
                            <form action="{{ $user->followers()->where('follower_id', auth()->id())->exists() ? route('user.unfollow', $user) : route('user.follow', $user) }}" method="POST">
                                @csrf
                                @if($user->followers()->where('follower_id', auth()->id())->exists())
                                @method('DELETE')
                                <button class="p-0.5 rounded-3xl bg-orange-400 border border-green-400/15 flex items-center gap-1 text-sm font-semibold">
                                    <span class="size-8 flex items-center justify-center rounded-full text-orange-400/70 bg-black/95 border border-green-400/15">
                                        <i class="fa-solid fa-user-check"></i>
                                    </span>
                                    <span class="pr-1 text-sm">
                                        Unfollow
                                    </span>
                                </button>
                                @else
                                <button class="p-0.5 rounded-3xl bg-orange-400 border border-green-400/15 flex items-center gap-1 text-sm font-semibold">
                                    <span class="size-8 flex items-center justify-center rounded-full text-orange-400/70 bg-black/95 border border-green-400/15">
                                        <i class="fa-solid fa-user-plus"></i>
                                    </span>
                                    <span class="pr-1 text-sm">
                                        Follow
                                    </span>
                                </button>
                                @endif
                            </form>
                            @endif
                            @endauth


                        </div>

                        <div class="flex justify-between items-center gap-4 bg-orange-400 border border-green-400/10 p-0.5 rounded-3xl">
                            <p class='text-3xl p-2 rounded-[50%] bg-black/95 text-orange-400/60'><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-facebook-icon lucide-facebook">
                                    <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z" /></svg>
                            </p>
                            <p class='text-3xl p-2 rounded-[50%] bg-black/95 text-orange-400/60'><svg xmlns="http://www.w3.org/2000/svg" width="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-instagram-icon lucide-instagram">
                                    <rect width="20" height="20" x="2" y="2" rx="5" ry="5" />
                                    <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z" />
                                    <line x1="17.5" x2="17.51" y1="6.5" y2="6.5" /></svg>
                            </p>
                            <p class='text-3xl p-2 rounded-[50%] bg-black/95 text-orange-400/60'><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-twitter-icon lucide-twitter">
                                    <path d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.6 5.6 4.1 9 4-.9-4.2 4-6.6 7-3.8 1.1 0 3-1.2 3-1.2z" /></svg>
                            </p>
                            <p class='text-3xl p-2 rounded-[50%] bg-black/95 text-orange-400/60'><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-youtube-icon lucide-youtube">
                                    <path d="M2.5 17a24.12 24.12 0 0 1 0-10 2 2 0 0 1 1.4-1.4 49.56 49.56 0 0 1 16.2 0A2 2 0 0 1 21.5 7a24.12 24.12 0 0 1 0 10 2 2 0 0 1-1.4 1.4 49.55 49.55 0 0 1-16.2 0A2 2 0 0 1 2.5 17" />
                                    <path d="m10 15 5-3-5-3z" /></svg>
                            </p>
                        </div>
                    </div>

                </div>
            </div>




            <!-- Followers Modal -->
            <div x-show="showFollowers" x-cloak class="fixed inset-0 bg-black/30 flex items-center justify-center z-50">
                <div class="w-100 max-h-[400px] overflow-y-auto bg-black/85 bg-[url(/public/bg-img.png)] bg-cover bg-center bg-fixed  bg-blend-multiply relative border border-green-400/30 backdrop-blur-[1px] p-5">
                    <div class="flex w-full justify-between items-center mb-5">
                        <h2 class="text-xs p-1 font-mono font-medium bg-orange-400 rounded-2xl w-fit text-black/90">Followers</h2>
                        <button @click="showFollowers = false" class="w-fit p-1 bg-orange-400 text-black/90 font-medium font-mono text-sm border border-green-400/10 rounded-3xl">
                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x-icon lucide-x">
                                <path d="M18 6 6 18" />
                                <path d="m6 6 12 12" /></svg>
                        </button>
                    </div>
                    <ul class="space-y-3">
                        @foreach($followers as $f)
                        <li class="flex items-center justify-between gap-3 bg-green-400/10 p-2 rounded-2xl">
                            <div class="flex items-center gap-3">
                                <img src="{{ $f->follower->profile_photo_url ?? asset('img2.jpg') }}" class="w-10 h-10 rounded-full" alt="{{ $f->follower->first_name }}">
                                <a href="{{ route('user.profile', $f->follower->id) }}" class="text-white/70 hover:underline">{{ $f->follower->first_name }} {{ $f->follower->last_name }}</a>
                            </div>
                            @if(auth()->check() && auth()->id() != $f->follower->id)
                            <form action="{{ $f->follower->followers()->where('follower_id', auth()->id())->exists() ? route('user.unfollow', $f->follower) : route('user.follow', $f->follower) }}" method="POST">
                                @csrf
                                @if($f->follower->followers()->where('follower_id', auth()->id())->exists())
                                @method('DELETE')
                                <button class="px-3 py-1 rounded-xl bg-red-600/70 text-white/70 text-xs font-mono uppercase font-bold">Unfollow</button>
                                @else
                                <button class="bg-green-400/10 border border-green-400/20 text-xs rounded-2xl p-0.5 px-2 text-orange-400/50 font-mono w-fit uppercase font-bold">Follow</button>
                                @endif
                            </form>
                            @endif
                        </li>
                        @endforeach
                    </ul>

                </div>
            </div>

            <!-- Following Modal -->
            <div x-show="showFollowing" x-cloak class="fixed inset-0 bg-black/30 flex items-center justify-center z-50">
                <div class="w-100 max-h-[400px] overflow-y-auto bg-black/85 bg-[url(/public/bg-img.png)] bg-cover bg-center bg-fixed  bg-blend-multiply relative border border-green-400/30 backdrop-blur-[1px] p-5">
                    <div class="flex w-full justify-between items-center mb-5">
                        <h2 class="text-xs p-1 font-mono font-medium bg-orange-400 rounded-2xl w-fit text-black/90">Following</h2>
                        <button @click="showFollowing = false" class="w-fit p-1 bg-orange-400 text-black/90 font-medium font-mono text-sm border border-green-400/10 rounded-3xl">
                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x-icon lucide-x">
                                <path d="M18 6 6 18" />
                                <path d="m6 6 12 12" /></svg>
                        </button>
                    </div>
                    <ul class="space-y-3">
                        @foreach($following as $f)
                        @if($f->following) {{-- only display if followed user exists --}}
                        <li class="flex items-center justify-between gap-3 bg-green-400/10  p-2 rounded-2xl">
                            <div class="flex items-center gap-3">
                                <img src="{{ $f->following->profile_photo_url ?? asset('img2.jpg') }}" class="w-10 h-10 rounded-full" alt="{{ $f->following->first_name }}">
                                <a href="{{ route('user.profile', $f->following->id) }}" class="text-white/70 hover:underline">{{ $f->following->first_name }} {{ $f->following->last_name }}</a>
                            </div>

                            @if(auth()->check() && auth()->id() != $f->following->id)
                            <form action="{{ $f->following->followers()->where('follower_id', auth()->id())->exists() ? route('user.unfollow', $f->following) : route('user.follow', $f->following) }}" method="POST">
                                @csrf
                                @if($f->following->followers()->where('follower_id', auth()->id())->exists())
                                @method('DELETE')
                                <button class="px-3 py-1 rounded-xl bg-red-600/70 text-white/70 text-xs font-mono uppercase font-bold">Unfollow</button>
                                @else
                                <button class="bg-green-400/10 border border-green-400/20 text-xs rounded-2xl p-0.5 px-2 text-orange-400/50 font-mono w-fit uppercase font-bold">Follow</button>
                                @endif
                            </form>
                            @endif
                        </li>
                        @endif
                        @endforeach

                    </ul>
                </div>
            </div>
        </div>

        @php
            $experiencePayload = $experiences->map(function($exp) {
                return [
                    'id' => $exp->id,
                    'media_url' => asset('storage/' . $exp->media_path),
                    'media_type' => $exp->media_type,
                    'caption' => $exp->caption,
                ];
            })->values();
        @endphp

        <!-- Experience Viewer -->
        <div id="experience-viewer" class="fixed inset-0 bg-black/80 hidden items-center justify-center z-50">
            <div class="w-full max-w-md bg-black/95 border border-green-400/20 rounded-3xl overflow-hidden">
                <div class="flex items-center justify-between p-3">
                    <div id="experience-progress" class="flex gap-1 w-full"></div>
                    <button id="close-experiences" class="text-white/70 hover:text-white px-2">X</button>
                </div>
                <div class="relative w-full h-[460px] bg-black">
                    <img id="experience-image" class="w-full h-full object-contain hidden" alt="Experience">
                    <video id="experience-video" class="w-full h-full object-contain hidden" playsinline></video>
                    <button id="prev-experience" class="absolute left-0 top-0 w-1/3 h-full"></button>
                    <button id="next-experience" class="absolute right-0 top-0 w-1/3 h-full"></button>
                </div>
                <div class="p-3 text-white/70 text-sm" id="experience-caption"></div>
            </div>
        </div>

        <!-- Trends Grid -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-5 mt-10">
            @foreach($trends as $trend)
            <div class="col-span-1 h-full flex flex-col gap-2">
                <div class="overflow-hidden w-full h-[150px] relative">
                    @if($trend->is_video)
                    <video src="{{ $trend->first_media_url }}" class="h-full w-full rounded-3xl object-cover opacity-80" autoplay muted loop playsinline></video>
                    @else
                    <img src="{{ $trend->first_media_url }}" class='h-full w-full rounded-3xl object-cover opacity-80' alt="{{ $trend->title }}" />
                    @endif
                    <div class="absolute bottom-0 left-0 p-4 flex flex-col justify-between grow">
                        <div class="flex items-center gap-2 mb-2">
                            <img src="{{ $trend->user->profile_pic ? asset('storage/'.$trend->user->profile_pic) : asset('default.png') }}" class="size-6 rounded-full border border-orange-400/30">
                            <span class="text-xs text-orange-400 font-bold truncate">{{ $trend->user->first_name }}</span>
                        </div>
                        <h3 class="text-white font-bold text-lg leading-tight mb-2 line-clamp-2">{{ $trend->title }}</h3>
                        <a href="{{ route('trends.show', $trend->id) }}" class="inline-block w-fit text-[10px] font-mono font-bold uppercase py-1.5 px-3 bg-orange-400 text-black rounded-2xl hover:bg-white transition-colors">
                            View Details
                        </a>
                    </div>
                </div>

               
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $trends->links() }}
        </div>

    </div>

    <script>
        const experiences = @json($experiencePayload);
        const viewer = document.getElementById('experience-viewer');
        const openBtn = document.getElementById('open-experiences');
        const closeBtn = document.getElementById('close-experiences');
        const imgEl = document.getElementById('experience-image');
        const videoEl = document.getElementById('experience-video');
        const captionEl = document.getElementById('experience-caption');
        const progressWrap = document.getElementById('experience-progress');
        const prevBtn = document.getElementById('prev-experience');
        const nextBtn = document.getElementById('next-experience');
        let currentIndex = 0;
        let timer = null;

        function buildProgress() {
            progressWrap.innerHTML = '';
            experiences.forEach((_, i) => {
                const bar = document.createElement('div');
                bar.className = 'h-1 flex-1 bg-white/10 rounded-full overflow-hidden';
                const fill = document.createElement('div');
                fill.className = 'h-full bg-orange-400/80 transition-all';
                fill.style.width = i < currentIndex ? '100%' : '0%';
                bar.appendChild(fill);
                progressWrap.appendChild(bar);
            });
        }

        function setProgress(durationMs) {
            const bars = progressWrap.querySelectorAll('div > div');
            if (!bars[currentIndex]) return;
            bars[currentIndex].style.transition = `width ${durationMs}ms linear`;
            requestAnimationFrame(() => {
                bars[currentIndex].style.width = '100%';
            });
        }

        function showExperience(index) {
            if (!experiences.length) return;
            if (index < 0) index = experiences.length - 1;
            if (index >= experiences.length) index = 0;
            currentIndex = index;
            const exp = experiences[currentIndex];
            captionEl.textContent = exp.caption || '';

            imgEl.classList.add('hidden');
            videoEl.classList.add('hidden');
            videoEl.pause();
            videoEl.removeAttribute('src');

            buildProgress();

            if (exp.media_type === 'video') {
                videoEl.src = exp.media_url;
                videoEl.classList.remove('hidden');
                videoEl.play();
                videoEl.onloadedmetadata = () => {
                    const durationMs = 30000;
                    setProgress(durationMs);
                    clearTimeout(timer);
                    timer = setTimeout(() => showExperience(currentIndex + 1), durationMs);
                };
                videoEl.onended = () => showExperience(currentIndex + 1);
            } else {
                imgEl.src = exp.media_url;
                imgEl.classList.remove('hidden');
                clearTimeout(timer);
                setProgress(30000);
                timer = setTimeout(() => showExperience(currentIndex + 1), 30000);
            }

            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            if (token) {
                fetch(`/experiences/${exp.id}/view`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json',
                    }
                }).catch(() => {});
            }
        }

        if (openBtn) {
            openBtn.addEventListener('click', () => {
                if (!experiences.length) return;
                viewer.classList.remove('hidden');
                viewer.classList.add('flex');
                showExperience(0);
            });
        }

        if (closeBtn) {
            closeBtn.addEventListener('click', () => {
                viewer.classList.add('hidden');
                viewer.classList.remove('flex');
                clearTimeout(timer);
                videoEl.pause();
            });
        }

        prevBtn?.addEventListener('click', () => showExperience(currentIndex - 1));
        nextBtn?.addEventListener('click', () => showExperience(currentIndex + 1));
    </script>


</x-layout>
