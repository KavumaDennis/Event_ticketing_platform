<x-layout>
    <section class="p-5">
        <h1 class="text-3xl text-white/80 mb-5">Saved Events</h1>

        @if($savedEvents->isEmpty())
        <p class="text-white/60">No saved events yet.</p>
        @else
        <div class="grid grid-cols-5 gap-4">
            @foreach($savedEvents as $saved)
            @php
            $event = $saved->event; // get the related event
            @endphp

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
            @endforeach
        </div>
        @endif
    </section>
</x-layout>
