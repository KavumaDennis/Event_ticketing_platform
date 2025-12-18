<x-layout>
    <section class="p-5">
        <div class="grid grid-cols-2 gap-10 p-5 bg-green-400/10 rounded-4xl">
            <div class="col-span-1 flex flex-col gap-3 justify-center">
                <h1 class="text-2xl text-orange-400/70">Lorem ipsum dolor sit amet consectetur adipisicing.</h1>
                <p class="text-white/70 font-mono font-light text-sm">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Eum optio quo hic nisi. Quas, dolores!</p>
            </div>
            <div class="col-span-1 flex justify-between items-center">
                <div class="p-5 px-10  flex flex-col items-center justify-center gap-3 bg-green-400/10 rounded-3xl">
                    <p class="text-white/60 font-medium">twelve</p>
                    <div class="text-orange-400/70">
                        <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-message-circle-question-mark-icon lucide-message-circle-question-mark">
                            <path d="M2.992 16.342a2 2 0 0 1 .094 1.167l-1.065 3.29a1 1 0 0 0 1.236 1.168l3.413-.998a2 2 0 0 1 1.099.092 10 10 0 1 0-4.777-4.719" />
                            <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3" />
                            <path d="M12 17h.01" /></svg>
                    </div>
                </div>
                <div class="p-5 px-10  flex flex-col items-center justify-center gap-3 bg-green-400/10 rounded-3xl">
                    <p class="text-white/60 font-medium">twelve</p>
                    <div class="text-orange-400/70">
                        <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-message-circle-question-mark-icon lucide-message-circle-question-mark">
                            <path d="M2.992 16.342a2 2 0 0 1 .094 1.167l-1.065 3.29a1 1 0 0 0 1.236 1.168l3.413-.998a2 2 0 0 1 1.099.092 10 10 0 1 0-4.777-4.719" />
                            <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3" />
                            <path d="M12 17h.01" /></svg>
                    </div>
                </div>
                <div class="p-5 px-10  flex flex-col items-center justify-center gap-3 bg-green-400/10 rounded-3xl">
                    <p class="text-white/60 font-medium">twelve</p>
                    <div class="text-orange-400/70">
                        <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-message-circle-question-mark-icon lucide-message-circle-question-mark">
                            <path d="M2.992 16.342a2 2 0 0 1 .094 1.167l-1.065 3.29a1 1 0 0 0 1.236 1.168l3.413-.998a2 2 0 0 1 1.099.092 10 10 0 1 0-4.777-4.719" />
                            <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3" />
                            <path d="M12 17h.01" /></svg>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section class="p-5">
        <div class="grid grid-cols-4 gap-5">
            <div class="w-full h-60 rounded-3xl flex flex-col justify-between pt-5">
                <div class="flex flex-col gap-5">
                    <p class="font-mono font-light text-white/70 text-sm">Become an organizer</p>
                    <h1 class="text-2xl text-orange-400/70">Get your eventing journey started, create your first event</h1>
                </div>
                <a href="{{ route('organizer.signup') }}">
                    <div class='p-1 flex gap-1 items-center bg-orange-400/60 text-black/60 font-medium text-sm w-fit rounded-4xl'>
                        <span class='text-orange-400 '>
                            <p class='size-8 flex items-center justify-center rounded-[50%] text-orange-400/80 bg-black/95 border border-green-400/15 text-md'>
                                <i class="fa-solid fa-plus"></i>
                            </p>
                        </span>
                        <span class='pr-2 text-black/90'>Become an organizer</span>
                    </div>
                </a>
            </div>
            <div class="w-full h-60 rounded-4xl flex justify-center items-center bg-green-400/10 p-1">
                <img src="{{ asset('img1.jpg') }}" class="w-full h-full object-cover rounded-[28.5px]" alt="">
            </div>
            <div class="w-full h-60 rounded-4xl flex justify-center items-center bg-green-400/10 p-1">
                <img src="{{ asset('img1.jpg') }}" class="w-full h-full object-cover rounded-[28.5px]" alt="">
            </div>
            <div class="w-full h-60 rounded-3xl bg-green-400/10 flex flex-col justify-between p-5">
                <h1 class="text-orange-400/70 text-2xl">Hey organizer, Akavaako offers a better experience when you have an account </h1>
                <div class="flex justify-between items-center gap-3 p-1 bg-orange-400/60 border border-green-400/10 rounded-3xl pl-3">
                    <a href="{{ route('events.create') }}" class='text-black/80 font-medium text-xs'>Create an event</a>
                    @guest
                    <a href="{{ route('show.login') }}" class='bg-black/90 border border-green-400/15 text-xs font-medium text-orange-400/60 p-2 px-3 rounded-3xl flex items-center'>Log In</a>
                    @endguest

                    @auth
                    <a href="" class='bg-black/90 border border-green-400/15 text-xs font-medium text-orange-400/60 p-2 px-3 rounded-3xl flex items-center'>Manage events</a>
                    @endauth
                </div>
            </div>
        </div>
    </section>

    <section class="p-5">
        <div class="flex items-center justify-between mb-5">
            <h1 class="text-3xl text-white/60">Discover fellow organizers</h1>
            <div class="flex  gap-3 bg-orange-400 p-1 rounded-3xl">
                <span class='bg-black text-orange-400 p-2 rounded-2xl'>
                    <LuArrowBigLeftDash /></span>
                <span class='bg-black text-orange-400 p-2 rounded-2xl'>
                    <LuArrowBigRightDash /></span>
            </div>
        </div>
        <div class="grid grid-cols-4 gap-5">
            @foreach($organizers as $organizer)
            <div class="h-fit col-span-1 w-full bg-green-400/10 p-3 rounded-3xl">
                <div class="flex justify-between mb-5">
                    <div class="flex items-center gap-3">
                        <div class="p-px bg-orange-400/70 border border-green-400/15 rounded-[50%] overflow-hidden">
                            <img src="{{ $organizer->organizer_image ? asset('storage/'.$organizer->organizer_image) : asset('mood.png') }}" class='size-11 rounded-[50%] object-cover' alt="{{ $organizer->business_name }}" />
                        </div>
                        <p class='text-orange-400/60 text-sm font-semibold'>
                            {{ $organizer->business_name }}
                        </p>
                    </div>
                    <div class="text-sm text-white/70 bg-black/60 flex items-center border border-green-400/15 rounded-md h-fit px-1">
                        <span class='pr-2 text-sm font-medium flex items-center relative after:content-[""] after:bg-orange-400/80 after:absolute after:w-[3px] after:h-[12px] after:rounded-lg after:right-0'>
                            Events
                        </span>
                        <span class='pl-2'>{{ $organizer->events_count }}</span>
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
                    <a href="{{ route('organizer.details', $organizer->id) }}" class='p-1 flex gap-1 items-center bg-black/95 border border-green-400/15 text-sm w-fit rounded-4xl'>
                        <span>
                            <p class='size-8 flex items-center justify-center rounded-[50%] text-black/90 bg-orange-400/70 border border-green-400/10 text-md'>
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                            </p>
                        </span>
                        <span class='pr-2 text-sm text-orange-400/60 font-medium'>Details</span>
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
            <div class="w-fit bg-orange-400/70 border border-green-400/15 p-1 rounded-3xl  flex items-center gap-1 cursor-pointer">
                <div class="size-8 flex justify-center items-center bg-black text-orange-400/80 border border-green-400/15 p-2 rounded-[50%]">
                    <i class="fa-solid fa-filter"></i>
                </div>
                <span class='text-sm font-medium mr-1'>Trend Filters</span>
            </div>
        </div>
        <div class="grid grid-cols-4 gap-7">
            @foreach($trends as $trend)
            <div class="col-span-1 h-full flex flex-col gap-2">
                <div class="overflow-hidden w-full h-[150px]">
                    <img src="{{ $trend->image ? asset('storage/'.$trend->image) : asset('img1.jpg') }}" class='h-full w-full rounded-3xl object-cover opacity-70' alt="{{ $trend->title }}" />
                </div>

                <div class="flex justify-between items-center">
                    <div class="text-white/70 flex items-center gap-2">
                        <button class="trend-like-btn size-8 flex justify-center items-center rounded-[50%] border border-green-400/20 bg-green-400/10" data-trend-id="{{ $trend->id }}" data-is-liked="{{ isset($trend->is_liked) && $trend->is_liked ? 'true' : 'false' }}">
                            {{-- <i class="fa-solid fa-heart {{ isset($trend->is_liked) && $trend->is_liked ? 'text-red-500' : 'text-white/70' }}"></i> --}}
                            <p class="{{ isset($trend->is_liked) && $trend->is_liked ? 'text-red-500' : 'text-white/70' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-heart-icon lucide-heart ">
                                    <path d="M2 9.5a5.5 5.5 0 0 1 9.591-3.676.56.56 0 0 0 .818 0A5.49 5.49 0 0 1 22 9.5c0 2.29-1.5 4-3 5.5l-5.492 5.313a2 2 0 0 1-3 .019L5 15c-1.5-1.5-3-3.2-3-5.5" />
                                </svg>
                            </p>
                        </button>
                        <span class="text-sm text-white/70 font-medium trend-likes-count" data-trend-id="{{ $trend->id }}">
                            {{ $trend->likes_count ?? 0 }} Likes
                        </span>
                    </div>

                    <a href="{{ route('trends.show', $trend->id) }}" class="w-fit bg-orange-400/70 border border-green-400/15 p-0.5 rounded-3xl flex items-center gap-1 cursor-pointer">
                        <p class="size-8 flex items-center justify-center rounded-[50%] text-orange-400/80 bg-black/95 border border-green-400/15 text-md">
                            <i class="fa-solid fa-ellipsis-vertical"></i>
                        </p>
                        <span class="text-sm font-medium mr-1">Read More</span>
                    </a>
                </div>

                <div class="p-4 h-[150px] bg-green-400/10 rounded-3xl">
                    <h2 class="text-lg font-semibold text-orange-400/80 mb-2">{{ $trend->title }}</h2>
                    <p class="text-sm font-light font-mono text-white/70 line-clamp-3">
                        {{ Str::limit($trend->body, 150) }}
                    </p>
                </div>
            </div>
            @endforeach
        </div>
    </section>
</x-layout>
