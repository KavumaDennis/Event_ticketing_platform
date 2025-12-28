@php
use Carbon\Carbon;

// Today
$today = Carbon::today();

// Tomorrow
$tomorrow = $today->copy()->addDay();
$tomorrowCount = \App\Models\Event::whereDate('event_date', $tomorrow->toDateString())->count();

// Upcoming week (Mon - Sun)
$weekStart = $today->copy()->startOfWeek(Carbon::MONDAY);
$weekEnd = $today->copy()->endOfWeek(Carbon::SUNDAY);
$weekCount = \App\Models\Event::whereDate('event_date', '>=', $weekStart->toDateString())
->whereDate('event_date', '<=', $weekEnd->toDateString())
    ->count();

    // This weekend (Fri - Sun)
    $weekendStart = $today->copy()->next(Carbon::FRIDAY);
    $weekendEnd = $weekendStart->copy()->endOfWeek(Carbon::SUNDAY);
    $weekendCount = \App\Models\Event::whereDate('event_date', '>=', $weekendStart->toDateString())
    ->whereDate('event_date', '<=', $weekendEnd->toDateString())
        ->count();

        // Next month
        $monthStart = $today->copy()->startOfMonth()->addMonth();
        $monthEnd = $monthStart->copy()->endOfMonth();
        $monthCount = \App\Models\Event::whereDate('event_date', '>=', $monthStart->toDateString())
        ->whereDate('event_date', '<=', $monthEnd->toDateString())
            ->count();
            @endphp

            <x-layout>

                <section class="p-5">
                    <div class="grid grid-cols-6 gap-10">

                        <!-- Left Column: Event Details -->
                        <div class="col-span-2 flex flex-col justify-between gap-5">
                            <h1 class="text-2xl underline text-orange-400/60 mt-2">
                                {{ $event->event_name }}
                            </h1>
                            <div class="grid grid-cols-3 rounded-2xl w-full bg-green-400/10 border border-green-400/10 gap-3 p-3">
                                <div class="col-span-1 flex flex-col justify-center items-center gap-2 text-white/60">
                                    <div class="text-sm font-mono font-medium">Category</div>
                                    <div class="text-orange-400/70 text-s font-medium">{{ $event->category }}</div>
                                </div>
                                <div class="col-span-1 flex flex-col justify-center items-center gap-2 text-white/60">
                                    <div class="text-sm font-mono font-medium">Price</div>
                                    <div class="text-orange-400/70 text-s font-medium">{{ $event->price ? $event->price : 'Free' }}</div>
                                </div>
                                <div class="col-span-1 flex flex-col justify-center items-center gap-2 text-white/60">
                                    <div class="text-sm font-mono font-medium">Likes</div>
                                    <div id="likes-count-{{ $event->id }}" class="text-orange-400/70 text-s font-medium">{{ $event->likes->count() }}</div>
                                </div>
                            </div>

                            <div class="font-light text-sm text-white/60 text-wrap font-mono">
                                <p>{{ $event->description }}</p>
                            </div>

                            <div class="flex justify-between items-center">
                                <div class="flex gap-5">
                                    <a href="{{ route('event.show', $event->id) }}" class='flex gap-1 items-center'>
                                        <div class="flex items-center p-0.5 w-fit bg-orange-400/60 gap-1 rounded-3xl">
                                            <span class='size-8 flex items-center justify-center rounded-[50%] bg-black/95 text-orange-400/80'>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-ticket-icon lucide-ticket">
                                                    <path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z" />
                                                    <path d="M13 5v2" />
                                                    <path d="M13 17v2" />
                                                    <path d="M13 11v2" />
                                                </svg>
                                            </span>
                                            <span class='text-xs pr-2 font-medium font-mono'>Get Tickets</span>
                                        </div>
                                    </a>
                                    <div class="size-9 flex items-center justify-center rounded-[50%] text-black/90 border border-green-400/20 bg-green-400/10" data-event="{{ $event->id }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-bookmark-icon lucide-bookmark
        {{ Auth::check() && $event->isSavedBy(Auth::user()) ? 'text-red-500' : 'text-black/80' }}">
                                            <path d="m19 21-7-4-7 4V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16z" />
                                        </svg>
                                    </div>
                                </div>

                                <div class="flex gap-2">
                                    <span class="relative text-white/70 flex items-center pr-3 after:absolute after:w-1 after:rounded-lg after:h-4 after:bg-orange-400/70 after:right-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock-arrow-down-icon lucide-clock-arrow-down">
                                            <path d="M12 6v6l2 1" />
                                            <path d="M12.337 21.994a10 10 0 1 1 9.588-8.767" />
                                            <path d="m14 18 4 4 4-4" />
                                            <path d="M18 14v8" /></svg>
                                    </span>
                                    <div id="countdown" class="text-orange-400 font-medium font-mono text-sm"></div>
                                </div>

                            </div>



                            <!-- Organizer Section -->
                            @if($event->organizer)
                            <div class="p-3 rounded-4xl border-green-400/20 bg-green-400/10 backdrop-blur-[1px]">
                                <div class="flex justify-between items-center">
                                    <div class="flex items-center gap-3">
                                        <!-- Organizer Image -->
                                        <div class="w-fit h-fit rounded-[50%] bg-orange-400/70 border border-green-400/10 p-0.5">
                                            <img src="{{ $event->organizer->organizer_image ? asset('storage/'.$event->organizer->organizer_image) : asset('default-profile.png') }}" onerror="this.onerror=null; this.src='{{ asset('img3.jpg') }}';" class='w-12 h-12 rounded-[50%] object-cover' alt="{{ $event->organizer->business_name }}" />
                                        </div>
                                        <div class="flex flex-col text-white text-sm">
                                            <h1 class='text-orange-400/70 font-medium'>{{ $event->organizer->business_name }}</h1>
                                            <p class='text-white/60 font-mono font-light text-sm'>Organizer</p>

                                        </div>
                                    </div>
                                    <LuShare2 class='text-orange-400/60 text-xl cursor-pointer' />
                                </div>

                                <div class="grid grid-cols-3 justify-between items-center mt-5">
                                    <div class="border-r border-black/60 flex flex-col items-center justify-center pr-5 text-white/60">
                                        <p class="text-xs text-white/50">Events</p>
                                        <h1 class="text-lg font-semibold">{{ $event->organizer->events_count ?? $event->organizer->events()->count() }}</h1>
                                    </div>
                                    <div class="text-white/60 flex flex-col items-center justify-center">
                                        <p class="text-xs text-white/50">Likes</p>
                                        <h1 class="text-lg font-semibold">{{ $event->likes_count ?? 0 }}</h1>
                                    </div>
                                    <div class="border-l border-black/60 flex flex-col items-center justify-center pl-5 text-white/60">
                                        <p class="text-xs text-white/50">Rating</p>
                                        <h1 class="text-lg font-semibold">4.5</h1>
                                    </div>
                                </div>

                                <a href="{{ route('organizer.details', $event->organizer->id) }}">
                                    <div class="w-full p-3 rounded-3xl text-white/70 text-sm font-mono bg-black/90 border border-green-400/10 text-center mt-5 font-medium hover:bg-black/95 transition cursor-pointer">
                                        Organizer details
                                    </div>
                                </a>
                            </div>
                            @else
                            <div class="p-5 rounded-4xl border-green-400/20 bg-green-400/10 backdrop-blur-[1px]">
                                <div class="text-center text-white/60">
                                    <p class="text-sm">No organizer information available</p>
                                </div>
                            </div>
                            @endif


                        </div>

                        <!-- Right Column: Event Images -->
                        <div class="col-span-4 flex flex-col justify-between gap-3 rounded-4xl">
                            <div class=" h-[406px] bg-[url({{ $event->event_image ? asset('storage/'.$event->event_image) : asset('img5.jpg') }})] relative bg-center bg-contain">
                                <img src="{{ $event->event_image ? asset('storage/'.$event->event_image) : asset('img3.jpg') }}" onerror="this.onerror=null; this.src='{{ asset('img3.jpg') }}';" class="absolute mix-blend-lighten z-0 top-0 left-0 object-cover w-full h-full rounded-4xl opacity-60" alt="{{ $event->event_name }}" />
                            </div>
                            <div class="">
                                <p class="text-xs mx-2 p-1 font-mono font-medium bg-orange-400/70 rounded-2xl w-fit text-black/90">Trends tagging the event</p>
                                <div class="row-span-1 flex justify-between gap-5 py-3 pb-0">
                                    @forelse($event->trends as $trend)
                                    <div class="flex flex-col justify-between gap-1 bg-green-400/10 p-3 rounded-3xl w-1/3">
                                        <h1 class='text-white/50 font-medium'>{{ $trend->title }}</h1>
                                        <p class='text-orange-400/60 font-mono font-light text-sm'>{{ $trend->user->first_name ?? 'Unknown' }} {{ $trend->user->last_name ?? '' }}</p>
                                        <p class='text-white/60 text-sm line-clamp-3'>{{ Str::limit($trend->body, 100) }}</p>
                                        <a href="{{ route('trends.show', $trend->id) }}" class="bg-green-400/10 border border-green-400/20 text-xs rounded-2xl p-0.5 px-1 text-orange-400/50 font-mono w-fit mt-1">View Trend</a>
                                    </div>
                                    @empty
                                    <div class="flex flex-col justify-center items-center text-white/60 gap-1 w-full">
                                        <p>No trends tagged for this event.</p>
                                    </div>
                                    @endforelse
                                </div>
                            </div>



                        </div>

                    </div>
                </section>




                <div class="p-5 ">
                    <div class="flex justify-between items-center mb-6">
                        <h1 class='text-3xl font-medium text-white/80'>Events</h1>
                        <div class="flex gap-5 items-center">
                            <form method="GET" action="{{ route('events') }}" class="relative">
                                <div class="h-full relative flex items-center ">
                                    <input type="search" name="search" value="{{ request('search') }}" class="p-2  bg-orange-400/70 border border-green-400/15 w-80 rounded-3xl pr-10 outline-0 text-black/80 font-medium text-sm placeholder:text-black/80" placeholder="Search events..." />
                                    <button type="submit" class="absolute right-1 size-8 flex items-center justify-center bg-black/95 text-orange-400/80 p-2 rounded-[50%] border border-green-400/15 hover:bg-black transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search-icon lucide-search">
                                            <path d="m21 21-4.34-4.34" />
                                            <circle cx="11" cy="11" r="8" /></svg>
                                    </button>
                                </div>
                            </form>

                            <div x-data="{ open: false }" class="relative">
                                <button @click="open = !open" class="w-fit bg-orange-400/70 border border-green-400/15 p-0.5 rounded-3xl flex items-center gap-1 cursor-pointer hover:bg-orange-400/80 transition">
                                    <div class="flex justify-center items-center size-8 bg-black/95 text-orange-400/80 p-2 rounded-2xl border border-green-400/15">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-list-filter-icon lucide-list-filter">
                                            <path d="M2 5h20" />
                                            <path d="M6 12h12" />
                                            <path d="M9 19h6" /></svg>
                                    </div>
                                    <span class='text-xs font-medium mr-1 text-black/90 '>Event Filters</span>
                                </button>

                                <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95" @click.away="open = false" class="absolute top-full right-0 mt-2 w-64 bg-[url(/public/bg-img.png)] bg-blend-darken bg-black/90 border border-purple-400/20 rounded-3xl shadow-lg p-4 z-50 backdrop-blur-[1px]">
                                    <div class="flex flex-col gap-3">
                                        <h3 class="text-orange-400/80 font-semibold text-sm mb-1 border-b border-purple-400/10 pb-2">Filter by Category</h3>

                                        <div class="flex flex-wrap gap-2">
                                            <!-- "All" button -->
                                            <a href="{{ route('events') }}" class="px-4 py-2 {{ !request('category') ? 'bg-orange-400/70 text-black/90' : 'bg-black/60 text-white/70 hover:bg-orange-400/50' }} border border-green-400/15 rounded-3xl text-sm font-medium transition">
                                                All
                                            </a>

                                            @foreach($categories as $category)
                                            <a href="{{ route('events', ['category' => $category, 'search' => request('search')]) }}" class="px-4 py-2 {{ request('category') === $category ? 'bg-orange-400/70 text-black/90' : 'bg-black/60 text-white/70 hover:bg-orange-400/50' }} border border-green-400/15 rounded-3xl text-sm font-medium transition">
                                                {{ ucfirst($category) }}
                                            </a>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>


                    <div class="grid grid-cols-5 gap-4">
                        @forelse($events as $event)
                        <div class="w-full h-fit p-1  rounded-3xl bg-green-400/10">
                            <div class="w-full h-[200px] relative p-2">
                                <img src="{{ $event->event_image ? asset('storage/'.$event->event_image) : asset('img3.jpg') }}" onerror="this.onerror=null; this.src='{{ asset('img3.jpg') }}';" class="absolute mix-blend-lighten z-0 top-0 left-0 object-cover w-full h-full rounded-[20px] opacity-80" alt="{{ $event->event_name }}" />

                                <div class="flex flex-col gap-2 z-10">
                                    <h1 class='text-orange-400/80 z-10 uppercase font-medium'>{{ $event->event_name }}</h1>
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
                            </div>
                        </div>
                        @empty
                        <p class="text-white col-span-5">No events found.</p>
                        @endforelse
                    </div>

                    <!-- Pagination links -->
                    <div class="mt-6">
                        {{ $events->links('vendor.pagination.tailwind') }}
                    </div>

                </div>




                <div class="p-5">
                    <h1 class='text-white/80 text-3xl mb-5'>Explore Events by Date</h1>
                    <div class="grid grid-cols-4 gap-5">

                        {{-- Tomorrow --}}
                        <div class="p-3 col-span-1 w-full h-fit rounded-3xl border-green-400/20 bg-green-400/10">
                            <div class="flex flex-col gap-6">
                                <div class="flex justify-between items-center">
                                    <h1 class="bg-green-400/10 border border-green-400/20 text-xs rounded-2xl p-0.5 px-1 text-orange-400/50 font-mono">Tomorrow</h1>
                                    <div class="flex items-center">
                                        <span class='px-3 relative text-white/80 font-medium text-sm flex items-center'>
                                            {{ $tomorrow->format('M d') }}
                                        </span>
                                    </div>
                                </div>
                                <div class="flex flex-col gap-2 items-center">
                                    <h1 class='text-center text-xl text-orange-400/80'>Tomorrow</h1>
                                    <p class='text-white/60 font-mono font-light text-sm text-center w-[80%]'>
                                        See all the events happening tomorrow
                                    </p>
                                </div>
                                <div class="flex justify-between ">
                                    <a href="{{ route('events.byDate', ['type' => 'tomorrow']) }}" class="w-fit flex items-center gap-1 bg-orange-400/80 border border-green-400/20 p-0.5 rounded-3xl">
                                        <p class='size-8 flex items-center justify-center rounded-[50%] text-orange-400/70 bg-black/95 border border-green-400/20 text-md'>
                                            <i class="fa-solid fa-ellipsis-vertical"></i>
                                        </p>
                                        <span class='font-medium text-black/90 mr-1 text-xs font-mono'>Find events</span>
                                    </a>
                                    <p class='size-9 font-medium text-sm rounded-[50%] bg-orange-400/80 border border-green-400/20 flex items-center justify-center'>
                                        {{ $tomorrowCount }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- Upcoming Week --}}
                        <div class="p-3 col-span-1 w-full h-fit rounded-3xl border-green-400/20 bg-green-400/10">
                            <div class="flex flex-col gap-6">
                                <div class="flex justify-between items-center">
                                    <h1 class="bg-green-400/10 border border-green-400/20 text-xs rounded-2xl p-0.5 px-1 text-orange-400/50 font-mono">Upcoming Week</h1>
                                    <div class="flex items-center">
                                        <span class='px-3 relative text-white/80 font-medium text-sm flex items-center'>
                                            {{ $weekStart->format('M d') }} - {{ $weekEnd->format('M d') }}
                                        </span>
                                    </div>
                                </div>
                                <div class="flex flex-col gap-2 items-center">
                                    <h1 class='text-center text-xl text-orange-400/80'>This Week</h1>
                                    <p class='text-white/60 font-mono font-light text-sm text-center w-[80%]'>
                                        See all the events happening this week
                                    </p>
                                </div>
                                <div class="flex justify-between ">
                                    <a href="{{ route('events.byDate', ['type' => 'week']) }}" class="w-fit flex items-center gap-1 bg-orange-400/80 border border-green-400/20 p-0.5 rounded-3xl">
                                        <p class='size-8 flex items-center justify-center rounded-[50%] text-orange-400/70 bg-black/95 border border-green-400/20 text-md'>
                                            <i class="fa-solid fa-ellipsis-vertical"></i>
                                        </p>
                                        <span class='font-medium text-black/90 mr-1 text-xs font-mono'>Find events</span>
                                    </a>
                                    <p class='size-9 font-medium text-sm rounded-[50%] bg-orange-400/80 border border-green-400/20 flex items-center justify-center'>
                                        {{ $weekCount }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- This Weekend --}}
                        <div class="p-3 col-span-1 w-full h-fit rounded-3xl border-green-400/20 bg-green-400/10">
                            <div class="flex flex-col gap-6">
                                <div class="flex justify-between items-center">
                                    <h1 class="bg-green-400/10 border border-green-400/20 text-xs rounded-2xl p-0.5 px-1 text-orange-400/50 font-mono">This Weekend</h1>
                                    <div class="flex items-center">
                                        <span class='px-3 relative text-white/80 font-medium text-sm flex items-center'>
                                            {{ $weekendStart->format('M d') }} - {{ $weekendEnd->format('M d') }}
                                        </span>
                                    </div>
                                </div>
                                <div class="flex flex-col gap-2 items-center">
                                    <h1 class='text-center text-xl text-orange-400/80'>Weekend</h1>
                                    <p class='text-white/60 font-mono font-light text-sm text-center w-[80%]'>
                                        See all the events happening this weekend
                                    </p>
                                </div>
                                <div class="flex justify-between ">
                                    <a href="{{ route('events.byDate', ['type' => 'weekend']) }}" class="w-fit flex items-center gap-1 bg-orange-400/80 border border-green-400/20 p-0.5 rounded-3xl">
                                        <p class='size-8 flex items-center justify-center rounded-[50%] text-orange-400/70 bg-black/95 border border-green-400/20 text-md'>
                                            <i class="fa-solid fa-ellipsis-vertical"></i>
                                        </p>
                                        <span class='font-medium text-black/90 mr-1 text-xs font-mono'>Find events</span>
                                    </a>
                                    <p class='size-9 font-medium text-sm rounded-[50%] bg-orange-400/80 border border-green-400/20 flex items-center justify-center'>
                                        {{ $weekendCount }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- Next Month --}}
                        <div class="p-3 col-span-1 w-full h-fit rounded-3xl border-green-400/20 bg-green-400/10">
                            <div class="flex flex-col gap-6">
                                <div class="flex justify-between items-center">
                                    <h1 class="bg-green-400/10 border border-green-400/20 text-xs rounded-2xl p-0.5 px-1 text-orange-400/50 font-mono">Next Month</h1>
                                    <div class="flex items-center">
                                        <span class='px-3 relative text-white/80 font-medium text-sm flex items-center'>
                                            {{ $monthStart->format('M d') }} - {{ $monthEnd->format('M d') }}
                                        </span>
                                    </div>
                                </div>
                                <div class="flex flex-col gap-2 items-center">
                                    <h1 class='text-center text-xl text-orange-400/80'>Next Month</h1>
                                    <p class='text-white/60 font-mono font-light text-sm text-center w-[80%]'>
                                        See all the events happening next month
                                    </p>
                                </div>
                                <div class="flex justify-between ">
                                    <a href="{{ route('events.byDate', ['type' => 'month']) }}" class="w-fit flex items-center gap-1 bg-orange-400/80 border border-green-400/20 p-0.5 rounded-3xl">
                                        <p class='size-8 flex items-center justify-center rounded-[50%] text-orange-400/70 bg-black/95 border border-green-400/20 text-md'>
                                            <i class="fa-solid fa-ellipsis-vertical"></i>
                                        </p>
                                        <span class='font-medium text-black/90 mr-1 text-xs font-mono'>Find events</span>
                                    </a>
                                    <p class='size-9 font-medium text-sm rounded-[50%] bg-orange-400/80 border border-green-400/20 flex items-center justify-center'>
                                        {{ $monthCount }}
                                    </p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>



            </x-layout>


            <script>
                console.log("SCRIPT LOADED");

                const raw = "2026-08-15";
                const parts = raw.split("-");
                const year = parseInt(parts[0]);
                const month = parseInt(parts[1]) - 1; // JS months are 0-indexed
                const day = parseInt(parts[2]);

                const eventDate = new Date(year, month, day, 0, 0, 0).getTime();

                function tick() {
                    const el = document.getElementById("countdown");
                    if (!el) return; // safety check

                    const now = Date.now();
                    const diff = eventDate - now;

                    if (diff <= 0) {
                        el.innerHTML = "Event started";
                        return;
                    }

                    const days = Math.floor(diff / (1000 * 60 * 60 * 24));


                    el.innerHTML = `${days} days`;
                }

                tick();
                setInterval(tick, 1000);

            </script>
