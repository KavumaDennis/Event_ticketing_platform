<x-layout>
    <div class="p-5 h-fit">
        <h1 class="text-3xl text-white/60 w-fit">On the move: these are our latest news</h1>
        <div class="grid grid-cols-10 gap-5 mt-5">
            <div class="col-span-1 flex justify-end items-end">

                <div class="flex flex-col justify-between items-center gap-4 bg-orange-400/70 border border-green-400/10 p-1 rounded-3xl">
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
            <div class="col-span-9 grid grid-cols-3 gap-5 p-2 rounded-4xl h-fit bg-green-400/10">
                @foreach($topTrends as $trend)
                <div class="h-90 p-2 grid grid-rows-5 rounded-3xl bg-green-400/10">
                    <div class="w-full h-full row-span-2">
                        <img src="{{ $trend->image ? asset('storage/'.$trend->image) : asset('img1.jpg') }}" class="w-full h-full rounded-2xl object-cover" alt="{{ $trend->title }}" />
                    </div>
                    <div class="row-span-3 flex flex-col justify-between">
                        <p class="text-white/70 text-sm font-light font-mono p-1">{{ Str::limit($trend->body, 250) }}</p>
                        <div class="flex justify-between items-center">
                            <a href="{{ route('trends.show', $trend->id) }}" class='p-1 flex gap-1 items-center bg-black/90 border border-green-400/15 w-fit rounded-4xl'>
                                <p class='size-8 flex items-center justify-center rounded-[50%] text-black/90 bg-orange-400/70 border border-green-400/15 text-md'><i class="fa-solid fa-ellipsis-vertical"></i></p>
                                <span class='pr-2 text-xs font-mono text-orange-400/70 font-medium'>More Details</span>
                            </a>

                            <div class="flex items-center gap-1">
                                <button class="trend-like-btn cursor-pointer size-9 flex items-center justify-center border border-green-400/20 bg-green-400/10 rounded-[50%] font-medium" data-trend-id="{{ $trend->id }}" data-is-liked="{{ isset($trend->is_liked) && $trend->is_liked ? 'true' : 'false' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-heart-icon lucide-heart {{ isset($trend->is_liked) && $trend->is_liked ? 'text-red-500' : 'text-orange-400/80' }}">
                                        <path d="M2 9.5a5.5 5.5 0 0 1 9.591-3.676.56.56 0 0 0 .818 0A5.49 5.49 0 0 1 22 9.5c0 2.29-1.5 4-3 5.5l-5.492 5.313a2 2 0 0 1-3 .019L5 15c-1.5-1.5-3-3.2-3-5.5" />
                                    </svg>
                                </button>
                                {{-- <span id="trend-likes-count-{{ $trend->id }}" class="text-white/60 text-sm font-medium after:ml-0.5 relative after:w-1 after:bg-white/60 after:rounded-xl after:h-3 flex items-center">
                                {{ $trend->likes_count ?? 0 }}
                                </span> --}}
                                <span class="text-white/60 text-sm font-medium after:ml-0.5 relative after:w-1 after:bg-white/60 after:rounded-xl after:h-3 flex items-center trend-likes-count" data-trend-id="{{ $trend->id }}">
                                    {{ $trend->likes_count ?? 0 }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        <div class="flex justify-end">
            <div class="p-0.5 rounded-3xl bg-orange-400/70 w-fit mt-5 border border-green-400/15">
                <a class='flex gap-1 items-center'>
                    <p class='size-8 flex items-center justify-center rounded-[50%] text-orange-400/80 bg-black/95 border border-green-400/15 text-md'><i class="fa-solid fa-ellipsis-vertical"></i></p>
                    <span class='text-sm pr-2 font-semibold'>Find more trends</span>
                </a>
            </div>
        </div>

    </div>


    <div class="p-5">
        <div class="grid grid-cols-10 gap-5">
            <div class="col-span-7">
                <div class="relative w-full h-fit p-5 rounded-4xl bg-black/70 bg-blend-darken overflow-hidden">
                    <img src="{{ asset('img2.jpg') }}" class="absolute object-cover top-0 left-0 w-full h-full -z-1" alt="" />
                    <h1 class="text-4xl w-[70%] text-orange-400/70">Discover, experience and at the same time get the chance to create your own events</h1>
                    <div class="p-0.5 rounded-3xl bg-orange-400/70 w-fit mt-5 border border-green-400/15">
                        <a class='flex gap-1 items-center'>
                            <p class='size-8 flex items-center justify-center rounded-[50%] text-orange-400/80 bg-black/95 border border-green-400/15 text-md'><i class="fa-solid fa-ellipsis-vertical"></i></p>
                            <span class='text-sm pr-2 font-semibold'>Find more trends</span>
                        </a>
                    </div>
                </div>
                <div class="p-5 pb-0">
                    <h1 class='text-white/60 text-3xl mb-3'>Trending Creators</h1>

                    <div class="flex justify-between items-center">

                        @foreach ($topCreators as $creator)
                        <div class="flex flex-col">
                            <div class="border border-black/70 rounded-[50%] p-1 bg-orange-400/60 w-fit">
                                <img src="{{ $creator->profile_pic ? asset('storage/'.$creator->profile_pic) : asset('img2.jpg') }}" class='size-18 rounded-[50%]' alt="{{ $creator->first_name }}" />
                            </div>

                            <div class="">
                                <p class="text-white/40 text-xs font-mono font-light text-center">
                                    {{ $creator->trends_count }} trends
                                </p>

                                @php
                                $formattedName = strtoupper(substr($creator->first_name, 0, 1)) . '_' . $creator->last_name;
                                @endphp

                                <a href="{{ route('user.profile', $creator->id) }}" class="hover:text-orange-400 transition">
                                    <p class="text-white/70 font-medium text-sm text-center">
                                        {{ $formattedName }}
                                    </p>
                                </a>
                            </div>
                        </div>
                        @endforeach

                    </div>
                </div>



            </div>

            <div class="col-span-3 flex flex-col justify-between w-full h-full p-3 bg-green-400/10 rounded-4xl">

                @if($topEvent)
                <h1 class='text-orange-400/70 w-full h-fit text-xl'>
                    {{ $topEvent->event_name }}
                </h1>

                <div class="p-1 border border-green-400/20 bg-green-400/10 rounded-3xl">
                    <ul class='flex justify-between items-center'>
                        <li class='p-1 px-3 flex items-center gap-2 rounded-3xl text-sm font-medium text-black/70 bg-pink-400/70'>
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar-icon lucide-calendar">
                                    <path d="M8 2v4" />
                                    <path d="M16 2v4" />
                                    <rect width="18" height="18" x="3" y="4" rx="2" />
                                    <path d="M3 10h18" /></svg>
                            </span>
                            <span>
                                {{ $topEvent->date ?? 'To be communicated' }}
                            </span>
                        </li>

                        <li class='p-1 px-3 flex items-center gap-2 rounded-3xl text-sm font-medium text-black/70 bg-blue-400/70'>

                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-banknote-icon lucide-banknote">
                                    <rect width="20" height="12" x="2" y="6" rx="2" />
                                    <circle cx="12" cy="12" r="2" />
                                    <path d="M6 12h.01M18 12h.01" /></svg>
                            </span>
                            <span>
                                {{ $topEvent->price ? 'UGX '.$topEvent->price : 'Free' }}
                            </span>
                        </li>
                    </ul>
                </div>

                <div class="w-auto h-fit overflow-hidden">
                    <img src="{{ $topEvent->image ? asset('storage/'.$topEvent->image) : asset('img2.jpg') }}" class='w-full h-60 object-cover rounded-3xl' alt="{{ $topEvent->title }}" />
                </div>

                <div class="flex items-center justify-between">
                    <div class="p-0.5 rounded-3xl bg-orange-400/70 w-fit border border-green-400/15">
                        <a href="{{ route('event.show', $topEvent->id) }}" class='flex gap-1 items-center'>
                            <span class='text-black/95 '>
                                <p class='size-8 flex items-center justify-center rounded-[50%] 
                                text-orange-400/70 bg-black/90 border border-green-400/15 text-md'>
                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                </p>
                            </span>
                            <span class='text-xs pr-2 font-semibold'>Event Details</span>
                        </a>
                    </div>
                    <div class="flex items-center gap-1">
                        <button class="like-btn cursor-pointer size-8 flex items-center justify-center border border-green-400/20 bg-green-400/10 rounded-[50%] font-medium" data-event="{{ $topEvent->id }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-heart-icon lucide-heart 
         {{ Auth::check() && $topEvent->isLikedBy(Auth::user()) ? 'text-red-500' : 'text-orange-400/80' }}">
                                <path d="M2 9.5a5.5 5.5 0 0 1 9.591-3.676.56.56 0 0 0 .818 0A5.49 5.49 0 0 1 22 9.5c0 2.29-1.5 4-3 5.5l-5.492 5.313a2 2 0 0 1-3 .019L5 15c-1.5-1.5-3-3.2-3-5.5" />
                            </svg>
                        </button>

                        <!-- Like count -->
                        <span id="likes-count-{{ $topEvent->id }}" class="text-black text-sm font-medium after:ml-2 relative after:w-1 after:bg-orange-400/80 after:rounded-xl after:h-3 flex items-center">
                            {{ $topEvent->likes->count() }}
                        </span>
                    </div>
                </div>
            </div>
            @else
            <p class="text-white/60">No events available.</p>
            @endif

        </div>


    </div>
    </div>

    <div class="p-5">
        <div class="p-5 flex justify-between items-center w-full bg-green-400/10 rounded-3xl">
            <h1 class='w-[50%] text-white/70 font-light font-mono'>Lorem ipsum dolor sit amet consectetur adipisicing elit. Saepe tenetur animi amet aut, sed laboriosam.</h1>
            <div class="p-0.5 rounded-3xl bg-orange-400/70 w-fit border border-green-400/10">
                <a href="{{ route('trends.create') }}" class='flex gap-1 items-center'>
                    <p class='size-8 flex items-center justify-center rounded-[50%] text-orange-400/80 bg-black/90 border border-green-400/15 text-md'>
                        <i class="fa-solid fa-pen-nib"></i>
                    </p>
                    <span class='text-xs pr-2 font-mono font-medium'>Write Your First Trend</span>
                </a>
            </div>
        </div>
    </div>

    <section class="p-5">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl text-white/60">
                Events are better with trends
            </h1>
        </div>

        @if(session('success'))
        <div class="bg-green-500/20 border border-green-400/40 text-green-300 rounded-3xl p-3 mb-4">
            {{ session('success') }}
        </div>
        @endif

        @error('title')
        <div class="bg-red-500/20 border border-red-400/40 text-red-300 rounded-3xl p-3 mb-4">{{ $message }}</div>
        @enderror

        @error('body')
        <div class="bg-red-500/20 border border-red-400/40 text-red-300 rounded-3xl p-3 mb-4">{{ $message }}</div>
        @enderror

        <div class="grid grid-cols-4 gap-10">
            @foreach($trends as $trend)
            <div class="col-span-1 h-full flex flex-col gap-2">
                <div class="overflow-hidden w-full h-[150px]">
                    <img src="{{ $trend->image ? asset('storage/'.$trend->image) : asset('img1.jpg') }}" class='h-full w-full rounded-3xl object-cover opacity-80' alt="{{ $trend->title }}" />
                </div>

                <div class="flex justify-between items-center">
                    <div class="text-white/70 flex items-center gap-2">
                        <button class="trend-like-btn size-8 flex justify-center items-center rounded-[50%] bg-green-400/10 border border-green-400/20" data-trend-id="{{ $trend->id }}" data-is-liked="{{ isset($trend->is_liked) && $trend->is_liked ? 'true' : 'false' }}">
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

                    <a href="{{ route('trends.show', $trend->id) }}" class="w-fit bg-orange-400/70 border border-green-400/15 p-0.5 rounded-3xl flex items-center gap-1 cursor-pointer">
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


        <div class="mt-8">
            {{ $trends->links() }}
        </div>
    </section>

</x-layout>
