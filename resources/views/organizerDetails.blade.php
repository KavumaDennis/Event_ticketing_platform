<x-layout>


    <section class="p-5">
        <div class="lg:grid grid-cols-4 md:flex flex-col md:gap-5 lg:gap-10">
            <div class="col-span-1 w-fit gap-5 lg:w-full lg:gap-0 h-fit grid grid-cols-3 p-3 rounded-3xl border-green-400/20 bg-green-400/10">
                <div class="col-span-2 flex flex-col gap-3 items-center justify-center">
                    <div class="border border-green-400/15  w-fit rounded-[50%] p-1 bg-orange-400">
                        <img src="{{ $organizer->organizer_image ? asset('storage/'.$organizer->organizer_image) : asset('default.png') }}" alt="{{ $organizer->business_name }}" class='size-18 rounded-[50%]' alt="" />
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
                        <button id="follow-btn" data-organizer="{{ $organizer->id }}" class="p-0.5 rounded-3xl bg-orange-400 border border-green-400/15 flex items-center gap-1 text-xs font-medium">

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

                    <div class="relative">
                        <button id="toggle-suggestions" class="p-2 rounded-full bg-orange-400 border border-green-400/15 text-black/95 hover:bg-orange-400/90 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-down transition-transform duration-300" id="chevron-icon">
                                <path d="m6 9 6 6 6-6" /></svg>
                        </button>
                    </div>
                </div>





            </div>
        </div>
    </section>
    <section class="p-5">

        <!-- Suggested Organizers Section (Mini Cards) -->
        <div id="suggestions-container" class="hidden w-full overflow-x-auto pb-2 transition-all duration-300 ease-in-out">
            <h1 class="text-white/90 ml-1 font-bold mb-3">
                Suggested For You
            </h1>
            <div class="grid grid-cols-7 gap-3 justify-between min-w-full">
                @foreach($suggestedOrganizers as $suggested)
                <div class="col-span-1 w-full flex flex-col gap-3 items-center p-1.5 bg-green-400/10 border border-green-400/5 rounded-2xl min-w-[140px] relative group hover:bg-green-400/20 transition-all">

                    <img src="{{ $suggested->organizer_image ? asset('storage/'.$suggested->organizer_image) : asset('default.png') }}" alt="{{ $suggested->business_name }}" class="w-16 h-16 rounded-full object-cover border-2 border-orange-400/30 group-hover:border-orange-400/60 transition-colors">

                    <div class="flex flex-col items-center gap-0.5 flex-wrap">
                        <span class="text-white/90 text-xs font-medium truncate w-full text-center">{{ $suggested->business_name }}</span>
                        <span class="text-white/50 text-[10px] font-mono">{{ $suggested->followers_count ?? 0 }} followers</span>
                    </div>

                    <a href="{{ route('organizer.details', $suggested->id) }}" class="w-full py-2 text-center text-xs bg-orange-400/80 font-mono text-black rounded-2xl font-medium hover:bg-orange-400 transition-colors">
                        View
                    </a>
                </div>
                @endforeach
            </div>
        </div>


        <script>
            document.getElementById('toggle-suggestions').addEventListener('click', function(e) {
                const container = document.getElementById('suggestions-container');
                const icon = document.getElementById('chevron-icon');

                container.classList.toggle('hidden');

                // Rotate icon
                if (container.classList.contains('hidden')) {
                    icon.style.transform = 'rotate(0deg)';
                } else {
                    icon.style.transform = 'rotate(180deg)';
                }
            });

        </script>
        <!-- Events Section -->
        <h2 class="text-3xl mb-5 text-white/60">Events by {{ $organizer->business_name }}</h2>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
            @forelse($organizer->events as $event)
            <div class="w-full h-fit ">
                <div class="w-full h-[170px] relative p-2 rounded-3xl bg-green-400/10">
                    <img src="{{ $event->event_image ? asset('storage/'.$event->event_image) : asset('default.png') }}" onerror="this.onerror=null; this.src='{{ asset('default.png') }}';" class="absolute mix-blend-lighten z-0 top-0 left-0 object-cover w-full h-full rounded-[20px] opacity-80" alt="{{ $event->event_name }}" />

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
