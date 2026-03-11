<x-layout>
    <section class="p-5">
        <div class="grid  lg:grid-cols-2 gap-10 p-5 bg-green-400/10 rounded-4xl">
            <div class="col-span-1 flex flex-col gap-3 justify-center">
                <h1 class="text-2xl text-orange-400/70">You dream it, we will ticket it</h1>
                <p class="text-white/70 font-mono font-light text-sm">Whether its your first events, or your biggest event, we are here to help you make it happen</p>
            </div>
            <div class="col-span-1 flex justify-between items-center">
                <div class="p-5 px-5  flex flex-col items-center justify-center gap-3 bg-green-400/10 rounded-3xl">
                    <p class="text-white/60 font-medium">Automated Emails</p>
                    <div class="text-orange-400/70">
                        <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-mails-icon lucide-mails">
                            <path d="M17 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2v-8a2 2 0 0 1 1-1.732" />
                            <path d="m22 5.5-6.419 4.179a2 2 0 0 1-2.162 0L7 5.5" />
                            <rect x="7" y="3" width="15" height="12" rx="2" /></svg>
                    </div>
                </div>
                <div class="p-5 px-5  flex flex-col items-center justify-center gap-3 bg-green-400/10 rounded-3xl">
                    <p class="text-white/60 font-medium">
                        Direct, Instant Payouts</p>
                    <div class="text-orange-400/70">
                        <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-hand-coins-icon lucide-hand-coins">
                            <path d="M11 15h2a2 2 0 1 0 0-4h-3c-.6 0-1.1.2-1.4.6L3 17" />
                            <path d="m7 21 1.6-1.4c.3-.4.8-.6 1.4-.6h4c1.1 0 2.1-.4 2.8-1.2l4.6-4.4a2 2 0 0 0-2.75-2.91l-4.2 3.9" />
                            <path d="m2 16 6 6" />
                            <circle cx="16" cy="9" r="2.9" />
                            <circle cx="6" cy="5" r="3" /></svg>
                    </div>
                </div>
                <div class="p-5 px-5  flex flex-col items-center justify-center gap-3 bg-green-400/10 rounded-3xl">
                    <p class="text-white/60 font-medium">Order Management</p>
                    <div class="text-orange-400/70">
                        <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chart-no-axes-gantt-icon lucide-chart-no-axes-gantt">
                            <path d="M6 5h12" />
                            <path d="M4 12h10" />
                            <path d="M12 19h8" /></svg>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section class="p-5">
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-5">
            <div class="w-full h-60 rounded-3xl flex flex-col justify-between pt-5">
                <div class="flex flex-col gap-5">
                    <p class="font-mono font-light text-white/70 text-sm">Become an organizer</p>
                    <h1 class="text-2xl text-orange-400/70">Get your eventing journey started, create your first event</h1>
                </div>
                <a href="{{ route('organizer.signup') }}">
                    <div class='p-1 flex gap-1 items-center bg-orange-400 text-black/60 font-medium text-sm w-fit rounded-4xl'>
                        <span class='text-orange-400 '>
                            <p class='size-8 flex items-center justify-center rounded-[50%] text-orange-400/80 bg-black/95 border border-green-400/15 text-md'>
                                <i class="fa-solid fa-plus"></i>
                            </p>
                        </span>
                        <span class='pr-2 text-black/90 font-mono text-xs'>Become an organizer</span>
                    </div>
                </a>
            </div>
            <!-- Pro Boost Card -->
            <div class="w-full h-60 rounded-4xl flex flex-col justify-between bg-black/85 bg-[url(/public/bg-img.png)] bg-cover bg-center bg-fixed  bg-blend-multiply border border-green-400/20 backdrop-blur-[1px] p-6 relative overflow-hidden group">
                <div class="relative z-10">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="bg-orange-400/20 text-orange-400 text-[10px] px-2 py-0.5 rounded-full font-bold uppercase tracking-wider">Most Popular</span>
                    </div>
                    <h3 class="text-orange-400 font-bold text-xl mb-1">Pro Boost</h3>
                    <p class="text-white/60 text-xs leading-relaxed font-mono">Analytics + 5x Boosted Reach. Make your events stand out.</p>
                </div>
                <div class="relative z-10">
                    <p class="text-green-400 font-mono text-lg mb-3">UGX 50,000</p>
                    <form action="{{ route('organizer.tier.buy') }}" method="POST">
                        @csrf
                        <input type="hidden" name="tier" value="pro">
                        <button type="submit" class="w-full bg-orange-400 hover:bg-orange-400 text-black font-mono font-medium py-2 rounded-3xl transition-all shadow-lg shadow-orange-400/10">Upgrade to Pro</button>
                    </form>
                </div>
                <div class="absolute -right-10 -top-10 w-32 h-32 bg-orange-400/5 rounded-full blur-3xl group-hover:bg-orange-400/15 transition-all"></div>
            </div>

            <!-- Elite Boost Card -->
            <div class="w-full h-60 rounded-4xl flex flex-col justify-between bg-black/85 bg-[url(/public/bg-img.png)] bg-cover bg-center bg-fixed  bg-blend-multiply border border-green-400/20 backdrop-blur-[1px] p-6 relative overflow-hidden group">
                <div class="relative z-10">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="bg-green-400/20 text-green-400 text-[10px] px-2 py-0.5 rounded-full font-bold uppercase tracking-wider">Max Visibility</span>
                    </div>
                    <h3 class="text-green-400 font-bold text-xl mb-1">Elite Boost</h3>
                    <p class="text-white/60 text-xs leading-relaxed font-mono">Homepage & Trends Priority. Be the first thing users see.</p>
                </div>
                <div class="relative z-10">
                    <p class="text-orange-400 font-mono text-lg mb-3">UGX 150,000</p>
                    <form action="{{ route('organizer.tier.buy') }}" method="POST">
                        @csrf
                        <input type="hidden" name="tier" value="elite">
                        <button type="submit" class="w-full bg-green-400/60 hover:bg-green-400 text-black font-mono font-medium py-2 rounded-3xl transition-all shadow-lg shadow-green-400/10">Go Elite</button>
                    </form>
                </div>
                <div class="absolute -right-10 -top-10 w-32 h-32 bg-green-400/5 rounded-full blur-3xl group-hover:bg-green-400/15 transition-all"></div>
            </div>
            <div class="w-full h-60 rounded-3xl bg-green-400/10 flex flex-col justify-between p-5">
                <h1 class="text-orange-400/70 text-2xl">Hey organizer, Akavaako offers a better experience when you have an account </h1>
                <div class="flex justify-between items-center gap-3 p-1 bg-orange-400 border border-green-400/10 rounded-3xl pl-3">
                    <a href="{{ route('events.create') }}" class='text-black/80 font-medium font-mono text-xs'>Create an event</a>
                    @guest
                    <a href="{{ route('show.login') }}" class='bg-black/90 border border-green-400/15 text-xs font-medium text-orange-400/60 p-2 px-3 rounded-3xl flex items-center'>Log In</a>
                    @endguest

                    @auth
                    <a href="{{ route('user.dashboard.events') }}" class='bg-black/90 border border-green-400/15 text-xs font-medium text-orange-400/60 p-2 px-3 rounded-3xl flex items-center font-mono text-xs'>Manage events</a>
                    @endauth
                </div>
            </div>
        </div>
    </section>

    <section class="p-5">
        <div class="flex items-center justify-between mb-5">
            <h1 class="text-3xl text-white/60">Discover fellow organizers</h1>

        </div>
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-5">
            @foreach($organizers as $organizer)
            <div class="h-fit col-span-1 w-full bg-green-400/10 p-3 rounded-3xl">
                <div class="flex justify-between mb-5">
                    <div class="flex items-center gap-3">
                        <div class="p-0.5 bg-orange-400 border border-green-400/15 rounded-[50%] overflow-hidden">
                            <img src="{{ $organizer->organizer_image ? asset('storage/'.$organizer->organizer_image) : asset('mood.png') }}" class='size-11 rounded-[50%] object-cover' alt="" />
                        </div>
                        <p class='text-orange-400/60 text-sm font-semibold'>
                            {{ $organizer->business_name }}
                        </p>
                    </div>
                    <div class="text-sm text-white/70 bg-black/60 flex items-center border border-green-400/15 rounded-md h-fit px-1">
                        <span class='pr-2.5 text-sm font-medium flex items-center relative after:content-[""] after:bg-orange-400/80 after:absolute after:w-[3px] after:h-3 after:rounded-lg after:right-0'>
                            Events
                        </span>
                        <span class='pl-1'>{{ $organizer->events_count }}</span>
                    </div>
                </div>
                <div class="flex justify-between items-center pt-5 border-t border-white/30">
                    <div class="flex items-center gap-3">
                        <p class='text-xl text-white/60'>
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-facebook-icon lucide-facebook">
                                <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z" /></svg>
                        </p>
                        <p class='text-xl text-white/60'>
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-instagram-icon lucide-instagram">
                                <rect width="20" height="20" x="2" y="2" rx="5" ry="5" />
                                <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z" />
                                <line x1="17.5" x2="17.51" y1="6.5" y2="6.5" /></svg>
                        </p>
                        <p class='text-xl text-white/60'>
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-twitter-icon lucide-twitter">
                                <path d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.6 5.6 4.1 9 4-.9-4.2 4-6.6 7-3.8 1.1 0 3-1.2 3-1.2z" /></svg>
                        </p>
                        <p class='text-xl text-white/60'>
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-youtube-icon lucide-youtube">
                                <path d="M2.5 17a24.12 24.12 0 0 1 0-10 2 2 0 0 1 1.4-1.4 49.56 49.56 0 0 1 16.2 0A2 2 0 0 1 21.5 7a24.12 24.12 0 0 1 0 10 2 2 0 0 1-1.4 1.4 49.55 49.55 0 0 1-16.2 0A2 2 0 0 1 2.5 17" />
                                <path d="m10 15 5-3-5-3z" /></svg>
                        </p>

                    </div>
                    <a href="{{ route('organizer.details', $organizer->id) }}" class='p-1 flex gap-1 items-center bg-black/70 border border-green-400/15 text-sm w-fit rounded-4xl'>
                        <span>
                            <p class='size-8 flex items-center justify-center rounded-[50%] text-black/90 bg-orange-400 border border-green-400/10 text-md'>
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                            </p>
                        </span>
                        <span class='pr-2 text-xs font-mono text-orange-400/60 font-medium'>Details</span>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </section>


    <section class="p-5">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl text-white/60">
                Follow trends to create events that capture audiances
            </h1>

        </div>
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-10">
            @foreach($trends as $trend)
            <div class="col-span-1 h-full flex flex-col gap-2">
                <div class="overflow-hidden w-full h-[150px]">
                    @if($trend->is_video)
                    <video src="{{ $trend->first_media_url }}" class='h-full w-full rounded-3xl object-cover opacity-80' autoplay muted loop playsinline></video>
                    @else
                    <img src="{{ $trend->first_media_url }}" class='h-full w-full rounded-3xl object-cover opacity-80' alt="{{ $trend->title }}" />
                    @endif
                </div>

                <div class="flex justify-between items-center">
                    <div class="text-white/70 flex items-center gap-2">
                        <button class="trend-like-btn size-8 flex justify-center items-center rounded-xl ml-2 bg-green-400/10 border border-green-400/20" data-trend-id="{{ $trend->id }}" data-is-liked="{{ isset($trend->is_liked) && $trend->is_liked ? 'true' : 'false' }}">
                            {{-- <i class="fa-solid fa-heart {{ isset($trend->is_liked) && $trend->is_liked ? 'text-red-500' : 'text-white/70' }}"></i> --}}
                            <p class="{{ isset($trend->is_liked) && $trend->is_liked ? 'text-red-500' : 'text-white/70' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-heart-icon lucide-heart ">
                                    <path d="M2 9.5a5.5 5.5 0 0 1 9.591-3.676.56.56 0 0 0 .818 0A5.49 5.49 0 0 1 22 9.5c0 2.29-1.5 4-3 5.5l-5.492 5.313a2 2 0 0 1-3 .019L5 15c-1.5-1.5-3-3.2-3-5.5" />
                                </svg>
                            </p>
                        </button>
                        <div class="flex gap-1 items-center text-sm text-white/70 font-medium">
                            <span class=" trend-likes-count" data-trend-id="{{ $trend->id }}">
                                {{ $trend->likes_count ?? 0 }}
                            </span>
                            <span>Likes</span>
                        </div>
                    </div>

                    <a href="{{ route('trends.show', $trend->id) }}" class="w-fit bg-orange-400 border border-green-400/15 p-0.5 rounded-3xl flex items-center gap-1 cursor-pointer">
                        <p class="size-8 flex items-center justify-center rounded-[50%] text-orange-400/80 bg-black/95 border border-green-400/15">
                            <i class="fa-solid fa-ellipsis-vertical"></i>
                        </p>
                        <span class="text-xs font-mono font-medium mr-1">Read Post</span>
                    </a>
                </div>

                <div class="p-4 h-[150px] bg-green-400/10 rounded-3xl">
                    <h2 class="text-md font-semibold text-orange-400/70 mb-2">{{ $trend->title }}</h2>
                    <p class="text-sm font-light font-mono text-white/70 line-clamp-3">
                        {{ Str::limit($trend->body, 200) }}
                    </p>
                </div>
            </div>
            @endforeach
        </div>
    </section>
</x-layout>
