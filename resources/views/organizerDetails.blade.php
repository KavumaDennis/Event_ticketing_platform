<x-layout>


    <section class="p-5">
        <div class="lg:grid grid-cols-4 md:flex flex-col md:gap-5 lg:gap-10">
            <div class="col-span-1 w-fit gap-5 lg:w-full lg:gap-0 h-fit grid grid-cols-3 p-3 rounded-3xl border-green-400/20 bg-green-400/10">
                <div class="col-span-2 flex flex-col gap-3 items-center justify-center">
                    <div class="border border-green-400/15  w-fit rounded-[50%] p-1 bg-orange-400/60">
                        <img src="{{ $organizer->organizer_image ? asset('storage/'.$organizer->organizer_image) : asset('default.jpg') }}" alt="{{ $organizer->business_name }}" class='size-18 rounded-[50%]' alt="" />
                    </div>
                    <div class="">
                        <p class="text-white/40 text-xs font-mono font-light text-center">Organizer</p>
                        <h1 class="text-white/60 text-center ">{{ $organizer->business_name }}</h1>
                    </div>
                </div>
                <div class="">
                    <div class="flex flex-col py-1">
                        <h1 class="text-white text-xl">{{ $organizer->events->count() }}</h1>
                        <p class="text-white/60 text-sm flex items-center gap-1">
                            <span class="text-orange-400/60">
                                <i class="fa-solid fa-tent"></i>
                            </span>
                            <span>Events</span>
                        </p>
                    </div>
                    <div class="flex flex-col py-1">
                        <h1 class="text-white text-xl">{{ $organizer->followers->count() }}</h1>

                        <p class="text-white/60 text-sm flex items-center gap-1">
                            <span class="text-orange-400/60">
                                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user-pen-icon lucide-user-pen">
                                    <path d="M11.5 15H7a4 4 0 0 0-4 4v2" />
                                    <path d="M21.378 16.626a1 1 0 0 0-3.004-3.004l-4.01 4.012a2 2 0 0 0-.506.854l-.837 2.87a.5.5 0 0 0 .62.62l2.87-.837a2 2 0 0 0 .854-.506z" />
                                    <circle cx="10" cy="7" r="4" /></svg>
                            </span>
                            <span>Followers</span>
                        </p>


                    </div>
                    <div class="flex flex-col py-1">
                        <h1 class="text-white text-xl">30</h1>
                        <p class="text-white/60 text-sm flex items-center gap-1">
                            <span class="text-orange-400/60">
                                <i class="fa-solid fa-heart"></i>
                            </span>
                            <span>Likes</span>
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
                        <span class="pl-3 text-white/60 font-mono font-light">{{ $organizer->business_email }}</span>
                    </div>
                    <div class="flex items-center">
                        <span class="pr-3 relative after:content-[''] flex items-center text-sm font-medium text-orange-400/70 after:bg-orange-400/80 after:absolute  after:w-[3px] after:h-[12px] after:rounded-lg after:right-0">
                            Website
                        </span>
                        <span class="pl-3 text-white/60 font-mono font-light">{{ $organizer->business_website }}</span>
                    </div>
                    <div class="flex">
                        <span class="pr-3 relative after:content-[''] flex items-center h-fit  text-sm font-medium text-orange-400/70 after:bg-orange-400/80 after:absolute  after:w-[3px] after:h-[12px] after:rounded-lg after:right-0">
                            About
                        </span>
                        <span class="pl-3 text-sm text-white/60 font-mono font-light">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Non modi provident quia sapiente earum impedit nisi laudantium culpa ad omnis, iusto sint, explicabo quasi? Numquam consequatur sed laudantium fugit eos.</span>
                    </div>
                </div>

                <div class="flex items-end gap-5 h-full">
                    <div>
                        <button id="follow-btn" data-organizer="{{ $organizer->id }}" class="p-0.5 rounded-3xl bg-orange-400/70 border border-green-400/15 flex items-center gap-1 text-xs font-medium">

                            <span id="follow-icon" class="size-8 flex items-center justify-center rounded-full text-orange-400/70 bg-black/95 border border-green-400/15">
                                @if(auth()->user() && auth()->user()->followedOrganizers->contains($organizer->id))
                                <i class="fa-solid fa-user-check"></i>
                                @else
                                <i class="fa-solid fa-user-plus"></i>
                                @endif
                            </span>

                            <span id="follow-text" class="pr-1 text-xs font-mono">
                                @if(auth()->user() && auth()->user()->followedOrganizers->contains($organizer->id))
                                Unfollow
                                @else
                                Follow Organizer
                                @endif
                            </span>

                        </button>


                    </div>

                    <div class="flex justify-between items-center gap-4 bg-orange-400/70 border border-green-400/10 p-0.5 rounded-3xl">
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
    </section>
    <section class="p-5">
        <!-- Events Section -->
        <h2 class="text-3xl mb-5 text-white/60">Events by {{ $organizer->business_name }}</h2>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
            @forelse($organizer->events as $event)
            <div class="w-full h-fit ">
                <div class="w-full h-[170px] relative p-2 rounded-3xl bg-green-400/10">
                    <img src="{{ $event->event_image ? asset('storage/'.$event->event_image) : asset('img3.jpg') }}" onerror="this.onerror=null; this.src='{{ asset('img3.jpg') }}';" class="absolute mix-blend-lighten z-0 top-0 left-0 object-cover w-full h-full rounded-[20px] opacity-80" alt="{{ $event->event_name }}" />

                    <div class="flex flex-col gap-2 z-10 absolute top-8">
                        <div class="w-fit z-10 bg-blend-normal bg-gray-700/90 p-2">
                            <p class="flex items-center gap-1 text-sm  text-black/70 font-medium">
                                <span> <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pin-icon lucide-map-pin">
                                        <path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0" />
                                        <circle cx="12" cy="10" r="3" /></svg>
                                </span>
                                <span class="font-medium">{{ $event->location }}</span></p>
                            <p class="flex items-center gap-1 text-sm  text-black/70 font-medium">
                                <span class='flex  font-medium'>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-timer-icon lucide-timer">
                                        <line x1="10" x2="14" y1="2" y2="2" />
                                        <line x1="12" x2="15" y1="14" y2="11" />
                                        <circle cx="12" cy="14" r="8" /></svg>
                                </span>
                                <span> {{ \Carbon\Carbon::parse($event->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($event->end_time)->format('g:i A') }}</span></p>
                            <p class="flex items-center gap-1 text-sm  text-black/70 font-medium">
                                <span class='flex font-medium'>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-banknote-icon lucide-banknote">
                                        <rect width="20" height="12" x="2" y="6" rx="2" />
                                        <circle cx="12" cy="12" r="2" />
                                        <path d="M6 12h.01M18 12h.01" /></svg>
                                </span>
                                <span>
                                    Shs {{ $event->regular_price ?? 0 }}
                                </span>
                            </p>
                        </div>
                    </div>

                    <div class="absolute bottom-1 right-1 flex items-center gap-1 bg-orange-400/80 rounded-3xl w-fit h-9 p-0.5">

                        <!-- LIKE BUTTON -->

                        <div class="flex items-center h-full gap-[3px]">
                            <button class="like-btn cursor-pointer h-full w-8 flex items-center justify-center bg-black/90 border border-black/10 rounded-[50%] font-medium" data-event="{{ $event->id }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-heart-icon lucide-heart 
         {{ Auth::check() && $event->isLikedBy(Auth::user()) ? 'text-red-500' : 'text-orange-400/80' }}">
                                    <path d="M2 9.5a5.5 5.5 0 0 1 9.591-3.676.56.56 0 0 0 .818 0A5.49 5.49 0 0 1 22 9.5c0 2.29-1.5 4-3 5.5l-5.492 5.313a2 2 0 0 1-3 .019L5 15c-1.5-1.5-3-3.2-3-5.5" />
                                </svg>
                            </button>

                            <!-- Like count -->
                            <span id="likes-count-{{ $event->id }}" class="text-black text-sm font-medium after:ml-0.5 relative after:w-1 after:bg-black/90 after:rounded-xl after:h-3 flex items-center">
                                {{ $event->likes->count() }}
                            </span></div>

                        <div class="p-2 bg-black/90 flex items-center justify-center border border-black/10 rounded-[50%] h-full w-8 text-orange-400 cursor-pointer save-btn" data-event="{{ $event->id }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-bookmark-icon lucide-bookmark
        {{ Auth::check() && $event->isSavedBy(Auth::user()) ? 'text-red-500' : 'text-orange-400/80' }}">
                                <path d="m19 21-7-4-7 4V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16z" />
                            </svg>
                        </div>



                        <a href="{{ route('event.show', $event->id) }}" class="h-full flex items-center justify-center px-3 z-30 cursor-pointer text-xs font-mono bg-black/90 border border-black/10 rounded-3xl font-medium text-orange-400/80">
                            More
                        </a>
                    </div>
                </div>
                <h1 class='text-white/70 z-10 text-sm font-medium m-1 ml-2'>{{ $event->event_name }}</h1>
            </div>
            @empty
            <p class="col-span-3 text-center text-white/60 py-10">No events hosted yet.</p>
            @endforelse
        </div>

    </section>


</x-layout>
