<x-layout>
    @push('meta')
        <meta property="og:title" content="{{ $event->event_name }}">
        <meta property="og:description" content="{{ \Illuminate\Support\Str::limit($event->description ?? 'Event details', 150) }}">
        <meta property="og:image" content="{{ $event->event_image ? asset('storage/'.$event->event_image) : asset('default.png') }}">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta property="og:type" content="website">
        <meta name="twitter:card" content="summary_large_image">
    @endpush
    <div class="grid md:grid-cols-1 lg:grid-cols-2 grid-rows-1 gap-10 p-5 overflow-hidden" x-data="ticketHandler({
            regular: {{ $event->regular_price ?? 0 }},
            vip: {{ $event->vip_price ?? 0 }},
            vvip: {{ $event->vvip_price ?? 0 }}
         })">

        <!-- Event Image -->
        <div class="w-full h-fit">
            <img src="{{ $event->event_image ? asset('storage/'.$event->event_image) : asset('default.png') }}" onerror="this.onerror=null; this.src='{{ asset('default.png') }}';" class="w-full h-[200px] md:h-[300px] lg:h-[482px] opacity-80 object-cover rounded-4xl" alt="{{ $event->event_name }}" />
        </div>

        <!-- Event Info -->
        <div class="grid grid-cols-2 lg:flex flex-col justify-center my-auto gap-5 h-fit">
            <div id="event-details-section" class="col-span-2 lg:col-span-2 flex flex-col gap-5">

            <h1 class="text-2xl col-span-2 text-orange-400/80">{{ $event->event_name }}</h1>

            <p class="text-sm col-span-2 text-white/60 font-mono font-light w-full md:w-[80%]">
                {!! \App\Support\ContentFormatter::linkify($event->description ?? 'No description available.') !!}
            </p>

            <!-- Ticket Section -->

            @csrf
            <div class="p-2 lg:w-fit rounded-3xl col-span-2 md:col-span-1 bg-green-400/10 border border-green-400/5 md:w-full h-fit">
                <div class="flex gap-3 mb-5">
                    <label @click="selectType('regular')" :class="selected === 'regular' ? 'bg-orange-400 text-black border border-green-400/10' : 'text-white/60 bg-white/5 border border-white/20'" class="p-1 px-2 text-sm flex items-center font-medium border rounded-3xl">
                        <input type="radio" name="ticket_type" value="regular" class="hidden">
                        Regular
                    </label>

                    <label @click="selectType('vip')" :class="selected === 'vip' ? 'bg-orange-400 text-black border border-green-400/10' : 'text-white/60 bg-white/5 border border-white/20'" class="p-1 px-2 text-sm flex items-center font-medium border rounded-3xl">
                        <input type="radio" name="ticket_type" value="vip" class="hidden">
                        VIP
                    </label>

                    <label @click="selectType('vvip')" :class="selected === 'vvip' ? 'bg-orange-400 text-black border border-green-400/10' : 'text-white/60 bg-white/5 border border-white/20'" class="p-1 px-2 text-sm flex items-center font-medium border rounded-3xl">
                        <input type="radio" name="ticket_type" value="vvip" class="hidden">
                        VVIP
                    </label>
                </div>

                <!-- Quantity Navigation -->
                <div class="flex items-center justify-between gap-5">
                    <div class="flex gap-3 bg-orange-400 p-0.5 rounded-3xl">
                        <button type="button" @click="decrease" class='size-8 text-sm flex justify-center items-center bg-black text-orange-400/70 p-2 rounded-[50%]'>
                            <i class="fa-solid fa-minus"></i>
                        </button>

                        <button type="button" @click="increase" class='size-8 text-sm flex justify-center items-center bg-black text-orange-400/70 p-2 rounded-[50%]'>
                            <i class="fa-solid fa-plus"></i>
                        </button>
                    </div>

                    <input type="hidden" name="quantity" x-model="quantity">

                    <div class="size-9 rounded-[14px] flex justify-center items-center bg-green-400/10 border border-green-400/20 text-white/80 text-lg font-semibold">
                        <span x-text="quantity"></span>
                    </div>

                    <div class="p-2 px-3 flex items-center text-sm font-mono bg-orange-400 rounded-3xl font-medium border border-green-400/20">
                        Ugx <span class="ml-1" x-text="total.toLocaleString()"></span>
                    </div>

                    <div>
                        @if($event->isSoldOut())
                            <div class="flex flex-col gap-2">
                                <span class="text-xs text-orange-400 font-bold uppercase tracking-widest bg-orange-400/10 px-3 py-1 rounded-full w-fit">Sold Out</span>
                                @auth
                                    @if($event->waitlist()->where('user_id', auth()->id())->exists())
                                        <form action="{{ route('waitlist.leave', $event->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="flex gap-1 items-center bg-zinc-800 text-white/60 rounded-3xl p-0.5 border border-white/10 hover:bg-zinc-700 transition">
                                                <span class='size-8 text-sm flex items-center justify-center rounded-[50%] bg-black/95 text-white/40'>
                                                    <i class="fa-solid fa-user-slash"></i>
                                                </span>
                                                <span class='text-sm pr-3 font-medium font-mono'>Leave Waitlist</span>
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('waitlist.join', $event->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="flex gap-1 items-center bg-orange-400 text-black rounded-3xl p-0.5 border border-green-400/20 hover:bg-orange-400 transition">
                                                <span class='size-8 text-sm flex items-center justify-center rounded-[50%] bg-black/95 text-orange-400/70'>
                                                    <i class="fa-solid fa-clock"></i>
                                                </span>
                                                <span class='text-sm pr-3 font-medium font-mono'>Join Waitlist</span>
                                            </button>
                                        </form>
                                    @endif
                                @else
                                    <a href="{{ route('login') }}" class="text-xs text-white/40 underline hover:text-orange-400 transition">Login to join waitlist</a>
                                @endauth
                            </div>
                        @else
                            <form action="{{ route('payment.page', $event->id) }}" method="GET">
                                <input type="hidden" name="ticket_type" id="ticket_type_input">
                                <input type="number" name="quantity" id="quantity_input" class="hidden">

                                <button type="submit" class="buy-btn flex gap-1 items-center bg-orange-400 rounded-3xl p-0.5 border border-green-400/20">
                                    <span class='size-8 text-sm flex items-center justify-center rounded-[50%] bg-black/95 text-orange-400/70'>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-ticket">
                                            <path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z" />
                                            <path d="M13 5v2" />
                                            <path d="M13 17v2" />
                                            <path d="M13 11v2" /></svg>
                                    </span>
                                    <span class='text-sm pr-2 font-medium font-mono'>Purchase Tickets</span>
                                </button>
                            </form>
                        @endif
                    </div>

                </div>
            </div>



            <!-- Event Details -->
            <div class="col-span-2 md:col-span-1 flex flex-col gap-3">
                <div class="flex items-center gap-2">
                    <span class='relative pr-3 text-orange-400/80 font-medium flex items-center after:content-[""] after:bg-orange-400/80 after:absolute  after:w-[3px] after:h-3 after:rounded-lg after:right-0'>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pin-icon lucide-map-pin">
                            <path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0" />
                            <circle cx="12" cy="10" r="3" /></svg>
                    </span>
                    <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                        <span class='text-white/70 font-mono font-light'>{{ $event->location }}</span>
                        <div x-data="mapHandler('{{ $event->location }}')">
                            <button @click="showMap = true" class="flex items-center gap-1.5 px-3 py-1 rounded-full bg-orange-400/20 text-orange-400 text-[10px] font-bold uppercase tracking-wider border border-orange-400/20 hover:bg-orange-400 hover:text-black transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z" />
                                    <circle cx="12" cy="10" r="3" /></svg>
                                Nearby Hotels
                            </button>

                            <!-- Map Modal -->
                            <div x-show="showMap" x-cloak class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm" @click.away="showMap = false">
                                <div class="bg-black/85 bg-[url(/public/bg-img.png)] bg-cover bg-center bg-fixed  bg-blend-multiply border border-green-400/30 backdrop-blur-[1px] p-6 w-full max-w-4xl h-[80vh] flex flex-col shadow-2xl shadow-orange-400/10">
                                    <div class="flex justify-between items-center mb-4">
                                        <div class="flex flex-col">
                                            <h2 class="text-orange-400 font-bold text-xl">Nearby Hotels</h2>
                                            <p class="text-white/60 text-xs font-mono">Finding accommodations near {{ $event->location }}</p>
                                        </div>
                                    
                                    </div>

                                    <div class="flex-1 rounded-2xl overflow-hidden border border-white/10">
                                        <iframe width="100%" height="100%" frameborder="0" style="border:0" :src="'https://maps.google.com/maps?q=hotels%20near%20' + encodeURIComponent(location) + '&t=&z=14&ie=UTF8&iwloc=&output=embed'" allowfullscreen></iframe>
                                    </div>

                                    <div class="mt-4 flex justify-between items-center">
                                        <a :href="'https://www.booking.com/searchresults.html?ss=' + encodeURIComponent(location)" target="_blank" class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-blue-600 text-white text-[10px] font-bold uppercase tracking-wider hover:bg-blue-500 transition-all">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M2 20h20" />
                                                <path d="M7 33v-30" />
                                                <path d="M11 3v2" />
                                                <path d="M11 17v2" />
                                                <path d="M11 9v2" />
                                                <path d="M15 3v2" />
                                                <path d="M15 17v2" />
                                                <path d="M15 9v2" />
                                                <path d="M19 3v2" />
                                                <path d="M19 17v2" />
                                                <path d="M19 9v2" />
                                                <rect width="16" height="18" x="4" y="3" rx="2" /></svg>
                                            Book Your Stay
                                        </a>
                                        <button @click="showMap = false" class="px-3 py-1.5 rounded-lg bg-orange-400 text-black text-[10px] font-bold uppercase tracking-wider hover:bg-orange-300 transition-all">Close Map</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <span class='relative pr-3 text-orange-400/80 font-medium flex items-center after:content-[""] after:bg-orange-400/80 after:absolute  after:w-[3px] after:h-3 after:rounded-lg after:right-0'>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-tent-icon lucide-tent">
                            <path d="M3.5 21 14 3" />
                            <path d="M20.5 21 10 3" />
                            <path d="M15.5 21 12 15l-3.5 6" />
                            <path d="M2 21h20" /></svg></span>
                    <span class='text-white/70 font-mono font-light'>{{ $event->venue }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class='relative pr-3 text-orange-400/80 font-medium flex items-center after:content-[""] after:bg-orange-400/80 after:absolute  after:w-[3px] after:h-3 after:rounded-lg after:right-0'>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar-icon lucide-calendar">
                            <path d="M8 2v4" />
                            <path d="M16 2v4" />
                            <rect width="18" height="18" x="3" y="4" rx="2" />
                            <path d="M3 10h18" /></svg>
                    </span>
                    <span class='text-white/70 font-mono font-light'>{{ $event->event_date }}</span>
                </div>

                <div class="flex items-center gap-2">
                    <span class='relative pr-3 text-orange-400/80 font-medium flex items-center after:content-[""] after:bg-orange-400/80 after:absolute  after:w-[3px] after:h-3 after:rounded-lg after:right-0'>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-timer-icon lucide-timer">
                            <line x1="10" x2="14" y1="2" y2="2" />
                            <line x1="12" x2="15" y1="14" y2="11" />
                            <circle cx="12" cy="14" r="8" /></svg>
                    </span>
                    <span class='text-white/70 font-mono font-light'>
                        {{ \Carbon\Carbon::parse($event->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($event->end_time)->format('g:i A') }}
                    </span>
                </div>

            </div>
            </div>

            <div id="event-comments-section" class="col-span-2 lg:col-span-2 bg-green-400/10 border border-green-400/10 rounded-3xl p-4 hidden opacity-0 transition-all duration-500 ease-out">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-white/80 font-bold text-[11px] uppercase">Event Comments</h2>
                    <span class="text-white/40 text-[10px]">{{ $event->comments->count() }} comments</span>
                </div>

                <div id="event-comments-list" class="space-y-3 max-h-64 overflow-y-auto pr-2">
                    @forelse($event->comments as $comment)
                        <div id="event-comment-{{ $comment->id }}" class="flex items-start gap-3 p-2 rounded-2xl bg-black/50">
                            <img src="{{ $comment->user?->profile_pic ? asset('storage/'.$comment->user->profile_pic) : asset('default.png') }}" class="w-8 h-8 rounded-full object-cover" alt="">
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <div class="text-white/80 text-[10px] font-bold">
                                        {{ $comment->user?->first_name ?? 'Unknown' }}
                                        {{ $comment->user?->last_name ?? '' }}
                                    </div>
                                    <span class="text-white/30 text-[10px]">{{ $comment->created_at->diffForHumans() }}</span>
                                </div>
                                <div class="text-white/60 text-xs">
                                    {!! \App\Support\ContentFormatter::linkify($comment->comment) !!}
                                </div>
                                <div class="flex items-center gap-3 mt-2 text-[10px]">
                                    <button class="event-like-comment-btn text-orange-400/80 hover:text-orange-400" data-comment="{{ $comment->id }}">
                                        ❤️ <span class="like-count">{{ $comment->likes->count() }}</span>
                                    </button>
                                    @if(auth()->check() && $comment->user_id === auth()->id())
                                        <button class="event-delete-comment-btn text-red-400 hover:text-red-300" data-comment="{{ $comment->id }}">
                                            Delete
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-white/40 text-xs">No comments yet — be the first to comment.</p>
                    @endforelse
                </div>

                @auth
                <form id="eventCommentForm" data-event="{{ $event->id }}" class="mt-3 flex items-center gap-2">
                    @csrf
                    <input type="text" name="comment" maxlength="500" placeholder="Add a comment..." class="flex-1 p-2 bg-black/50 text-white rounded-xl outline-none placeholder:text-white/60 text-xs">
                    <button type="submit" class="px-3 py-2 bg-orange-400 text-black rounded-xl text-[10px] font-bold uppercase">Post</button>
                </form>
                @else
                    <p class="text-white/40 text-[10px] mt-3">Login to comment.</p>
                @endauth
            </div>

            <!-- Organizer -->
            <div class="col-span-2 md:col-span-1 flex justify-between">
                <div class="mt-3 flex gap-3 items-center">
                    <span class='text-xs p-1 font-mono font-medium bg-orange-400 rounded-2xl w-fit text-black/90'>Organized By</span>
                    <a href="{{ route('organizer.details',$event->organizer_id ) }}" class='font-medium text-white/60 text-sm underline'>
                        {{ optional($event->organizer)->business_name ?? 'Unknown Organizer' }}
                    </a>
                </div>
                <div class="flex items-center gap-3">
                    {{-- Like Button --}}
                    <div class="flex items-center gap-1">
                        <button class="like-btn cursor-pointer size-9 flex items-center justify-center border border-green-400/20 bg-green-400/10 rounded-[14px] font-medium" data-event="{{ $event->id }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-heart-icon lucide-heart 
         {{ Auth::check() && $event->isLikedBy(Auth::user()) ? 'text-red-500' : 'text-orange-400/80' }}">
                                <path d="M2 9.5a5.5 5.5 0 0 1 9.591-3.676.56.56 0 0 0 .818 0A5.49 5.49 0 0 1 22 9.5c0 2.29-1.5 4-3 5.5l-5.492 5.313a2 2 0 0 1-3 .019L5 15c-1.5-1.5-3-3.2-3-5.5" />
                            </svg>
                        </button>
                        <!-- Like count -->
                        <span id="likes-count-{{ $event->id }}" class="text-white/80 text-sm font-medium after:ml-1 relative after:w-1 after:bg-orange-400 after:rounded-xl after:h-3 flex items-center">
                            {{ $event->likes->count() }}
                        </span>
                    </div>

                    <button id="toggle-event-comments" class="size-9 flex items-center justify-center border border-green-400/20 bg-green-400/10 rounded-[14px] text-orange-400/80 hover:text-orange-400 transition" title="Comments">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-message-circle-icon lucide-message-circle">
                            <path d="M2.992 16.342a2 2 0 0 1 .094 1.167l-1.065 3.29a1 1 0 0 0 1.236 1.168l3.413-.998a2 2 0 0 1 1.099.092 10 10 0 1 0-4.777-4.719" />
                        </svg>
                    </button>

                    <div class="flex items-center gap-2" x-data="shareHandler('{{ url()->current() }}', '{{ $event->event_name }}')">
                        <button @click="share" class="size-9 flex items-center justify-center rounded-[14px] bg-white/5 border border-white/20 text-orange-400/70 hover:bg-orange-400/80 hover:text-black transition" title="Share Event">
                            <i class="fa-solid fa-share-nodes"></i>
                        </button>

            
                        
                    </div>

                </div>
                </form>
            </div>

        </div>

    </div>
    </div>

    <div class="p-5 grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-green-400/10 border border-green-400/10 rounded-3xl p-4">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-white/80 font-bold text-[11px] uppercase">Similar Events</h3>
                <span class="text-white/30 text-[10px]">{{ $similarEvents->count() }} found</span>
            </div>
            <div class="space-y-3">
                @forelse($similarEvents as $similar)
                    <a href="{{ route('event.show', $similar->id) }}" class="flex items-center gap-3 p-2 rounded-2xl bg-black/50 hover:bg-black/60 transition">
                        <img src="{{ $similar->event_image ? asset('storage/'.$similar->event_image) : asset('default.png') }}" class="w-12 h-12 rounded-xl object-cover" alt="">
                        <div class="flex-1">
                            <div class="text-white/80 text-xs font-bold">{{ $similar->event_name }}</div>
                            <div class="text-white/40 text-[10px] font-mono">{{ $similar->event_date }} · {{ $similar->location }}</div>
                        </div>
                    </a>
                @empty
                    <p class="text-white/40 text-xs">No similar events yet.</p>
                @endforelse
            </div>
        </div>

        <div class="bg-green-400/10 border border-green-400/10 rounded-3xl p-4">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-white/80 font-bold text-[11px] uppercase">People Also Bought</h3>
                <span class="text-white/30 text-[10px]">{{ $alsoBoughtEvents->count() }} found</span>
            </div>
            <div class="space-y-3">
                @forelse($alsoBoughtEvents as $also)
                    <a href="{{ route('event.show', $also->id) }}" class="flex items-center gap-3 p-2 rounded-2xl bg-black/50 hover:bg-black/60 transition">
                        <img src="{{ $also->event_image ? asset('storage/'.$also->event_image) : asset('default.png') }}" class="w-12 h-12 rounded-xl object-cover" alt="">
                        <div class="flex-1">
                            <div class="text-white/80 text-xs font-bold">{{ $also->event_name }}</div>
                            <div class="text-white/40 text-[10px] font-mono">{{ $also->event_date }} · {{ $also->location }}</div>
                        </div>
                    </a>
                @empty
                    <p class="text-white/40 text-xs">No related purchases yet.</p>
                @endforelse
            </div>
        </div>
    </div>

</x-layout>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('ticketHandler', (prices) => ({
            selected: 'regular'
            , quantity: 1
            , prices,

            get total() {
                return (this.prices[this.selected] ?? 0) * this.quantity;
            },

            selectType(type) {
                this.selected = type;

                // update hidden input
                document.getElementById('ticket_type_input').value = type;
            },

            increase() {
                this.quantity++;
                document.getElementById('quantity_input').value = this.quantity;
            },

            decrease() {
                if (this.quantity > 1) {
                    this.quantity--;
                    document.getElementById('quantity_input').value = this.quantity;
                }
            }
        }));

        Alpine.data('shareHandler', (url, title) => ({
            url: url,
            title: title,
            showModal: false,
            copied: false,

            async share() {
                if (navigator.share) {
                    try {
                        await navigator.share({
                            title: this.title,
                            url: this.url
                        });
                    } catch (err) {
                        if (err.name !== 'AbortError') {
                            this.showModal = true;
                        }
                    }
                } else {
                    this.showModal = true;
                }
            },

            copyLink() {
                navigator.clipboard.writeText(this.url).then(() => {
                    this.copied = true;
                    setTimeout(() => this.copied = false, 2000);
                });
            }
        }));

        Alpine.data('mapHandler', (location) => ({
            location: location,
            showMap: false
        }));
    });

    // initialize default values
    document.addEventListener("DOMContentLoaded", () => {
        document.getElementById('ticket_type_input').value = 'regular';
        document.getElementById('quantity_input').value = 1;
    });

    function escapeHtml(str) {
        return str
            .replaceAll('&', '&amp;')
            .replaceAll('<', '&lt;')
            .replaceAll('>', '&gt;')
            .replaceAll('"', '&quot;')
            .replaceAll("'", '&#039;');
    }

    $(document).on('submit', '#eventCommentForm', function(e) {
        e.preventDefault();
        const form = $(this);
        const eventId = form.data('event');
        const input = form.find('input[name="comment"]');
        const comment = input.val().trim();

        if (!comment) return;

        $.post(`/events/${eventId}/comment`, {
            _token: "{{ csrf_token() }}",
            comment
        }).done((res) => {
            input.val('');
            $('#event-comments-list').prepend(`
                <div id="event-comment-${res.id}" class="flex items-start gap-3 p-2 rounded-2xl bg-black/50">
                    <img src="${res.user_photo}" class="w-9 h-9 rounded-full object-cover" alt="">
                    <div class="flex-1">
                        <div class="flex items-center justify-between">
                            <div class="text-white/80 text-xs font-bold">${escapeHtml(res.user_name)}</div>
                            <span class="text-white/30 text-[10px]">${escapeHtml(res.created_at)}</span>
                        </div>
                        <div class="text-white/60 text-sm">${res.comment_html ?? escapeHtml(res.comment)}</div>
                        <div class="flex items-center gap-3 mt-2 text-xs">
                            <button class="event-like-comment-btn text-orange-400/80 hover:text-orange-400" data-comment="${res.id}">
                                ❤️ <span class="like-count">0</span>
                            </button>
                            <button class="event-delete-comment-btn text-red-400 hover:text-red-300" data-comment="${res.id}">
                                Delete
                            </button>
                        </div>
                    </div>
                </div>
            `);
        }).fail((xhr) => {
            if (xhr.status === 401) alert('Please log in to comment.');
            else alert('Error posting comment.');
        });
    });

    document.addEventListener('DOMContentLoaded', () => {
        const toggleBtn = document.getElementById('toggle-event-comments');
        const details = document.getElementById('event-details-section');
        const comments = document.getElementById('event-comments-section');
        if (!toggleBtn || !details || !comments) return;

        toggleBtn.addEventListener('click', () => {
            const isOpen = !comments.classList.contains('hidden');
            if (isOpen) {
                comments.classList.add('opacity-0');
                setTimeout(() => comments.classList.add('hidden'), 200);
                details.classList.remove('hidden');
                details.classList.remove('opacity-0');
                details.classList.add('opacity-100');
            } else {
                details.classList.add('opacity-0');
                setTimeout(() => details.classList.add('hidden'), 200);
                comments.classList.remove('hidden');
                setTimeout(() => comments.classList.remove('opacity-0'), 10);
                comments.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }
        });
    });

    $(document).on('click', '.event-like-comment-btn', function() {
        const btn = $(this);
        const commentId = btn.data('comment');

        $.post(`/events/comment/${commentId}/like`, {
            _token: "{{ csrf_token() }}"
        }).done((res) => {
            btn.find('.like-count').text(res.likes_count);
        }).fail((xhr) => {
            if (xhr.status === 401) alert('Login required to like comments.');
        });
    });

    $(document).on('click', '.event-delete-comment-btn', function() {
        if (!confirm('Delete this comment?')) return;
        const commentId = $(this).data('comment');

        $.ajax({
            url: `/events/comment/${commentId}`,
            type: 'DELETE',
            data: {
                _token: "{{ csrf_token() }}"
            }
        }).done(() => {
            $(`#event-comment-${commentId}`).fadeOut(200, function() { $(this).remove(); });
        }).fail((xhr) => {
            if (xhr.status === 401) alert('Login required to delete comments.');
            else if (xhr.status === 403) alert('You can only delete your own comments.');
            else alert('Error deleting comment.');
        });
    });

</script>
