<x-layout>
    <div class="grid grid-cols-2 grid-rows-1 gap-10 p-5 overflow-hidden" x-data="ticketHandler({
            regular: {{ $event->regular_price ?? 0 }},
            vip: {{ $event->vip_price ?? 0 }},
            vvip: {{ $event->vvip_price ?? 0 }}
         })">

        <!-- Event Image -->
        <div class="w-full h-fit">
            <img src="{{ $event->event_image ? asset('storage/'.$event->event_image) : asset('img3.jpg') }}" onerror="this.onerror=null; this.src='{{ asset('img3.jpg') }}';" class="w-full h-[482px] opacity-80 object-cover rounded-4xl" alt="{{ $event->event_name }}" />
        </div>

        <!-- Event Info -->
        <div class="flex flex-col justify-center my-auto gap-5 h-fit">

            <h1 class="text-2xl text-orange-400/80">{{ $event->event_name }}</h1>

            <p class="text-sm text-white/60 font-mono font-light w-[80%]">
                {{ $event->description ?? 'No description available.' }}
            </p>

            <!-- Ticket Section -->

            @csrf
            <div class="p-2 w-fit rounded-3xl bg-green-400/10 border border-green-400/5">
                <div class="flex gap-3 mb-5">
                    <label @click="selectType('regular')" :class="selected === 'regular' ? 'bg-orange-400/70 text-black border border-green-400/10' : 'text-white/60 border-white/50'" class="p-1 px-2 text-sm flex items-center font-medium border rounded-3xl">
                        <input type="radio" name="ticket_type" value="regular" class="hidden">
                        Regular
                    </label>

                    <label @click="selectType('vip')" :class="selected === 'vip' ? 'bg-orange-400/70 text-black border border-green-400/10' : 'text-white/60 border-white/50'" class="p-1 px-2 text-sm flex items-center font-medium border rounded-3xl">
                        <input type="radio" name="ticket_type" value="vip" class="hidden">
                        VIP
                    </label>

                    <label @click="selectType('vvip')" :class="selected === 'vvip' ? 'bg-orange-400/70 text-black border border-green-400/10' : 'text-white/60 border-white/50'" class="p-1 px-2 text-sm flex items-center font-medium border rounded-3xl">
                        <input type="radio" name="ticket_type" value="vvip" class="hidden">
                        VVIP
                    </label>
                </div>

                <!-- Quantity Navigation -->
                <div class="flex items-center gap-5">
                    <div class="flex gap-3 bg-orange-400/70 p-0.5 rounded-3xl">
                        <button type="button" @click="decrease" class='size-8 text-sm flex justify-center items-center bg-black text-orange-400/70 p-2 rounded-[50%]'>
                            <i class="fa-solid fa-minus"></i>
                        </button>

                        <button type="button" @click="increase" class='size-8 text-sm flex justify-center items-center bg-black text-orange-400/70 p-2 rounded-[50%]'>
                            <i class="fa-solid fa-plus"></i>
                        </button>
                    </div>

                    <input type="hidden" name="quantity" x-model="quantity">

                    <div class="size-9 rounded-[50%] flex justify-center items-center bg-green-400/10 border border-green-400/20 text-white/80 text-lg font-semibold">
                        <span x-text="quantity"></span>
                    </div>

                    <div class="p-2 px-3 flex items-center text-sm bg-orange-400/70 rounded-3xl font-medium border border-green-400/20">
                        Ugx <span class="ml-1" x-text="total.toLocaleString()"></span>
                    </div>

                    <div>
                       

                        <form action="{{ route('payment.page', $event->id) }}" method="GET">
                            <input type="hidden" name="ticket_type" id="ticket_type_input">
                            <input type="number" name="quantity" id="quantity_input" class="hidden">

                            <button type="submit" class="buy-btn flex gap-1 items-center bg-orange-400/70 rounded-3xl p-0.5 border border-green-400/20">
                                <span class='size-8 text-sm flex items-center justify-center rounded-[50%] bg-black/95 text-orange-400/70'>
                                    <i class="fa-solid fa-ticket"></i>
                                </span>
                                <span class='text-sm pr-2 font-semibold font-mono'>Purchase Tickets</span>
                            </button>
                        </form>


                    </div>

                </div>
            </div>



            <!-- Event Details -->
            <div class="flex flex-col gap-3">
                <div class="flex items-center gap-2">
                    <span class='relative pr-3 text-orange-400/80 font-medium flex items-center after:content-[""] after:bg-orange-400/80 after:absolute  after:w-[3px] after:h-3 after:rounded-lg after:right-0'>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pin-icon lucide-map-pin">
                            <path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0" />
                            <circle cx="12" cy="10" r="3" /></svg>
                    </span>
                    <span class='text-white/70 font-mono font-light'>{{ $event->location }}</span>
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

            <!-- Organizer -->
            <div class="flex justify-between">
                <div class="mt-3 flex gap-3 items-center">
                    <span class='p-1 px-2 bg-orange-400/70 rounded-lg text-sm font-medium'>Organized By</span>
                    <a href="{{ route('organizer.details',$event->organizer_id ) }}" class='font-medium text-white/70 text-sm underline'>
                        {{ optional($event->organizer)->business_name ?? 'Unknown Organizer' }}
                    </a>
                </div>
                <div class="flex items-center gap-3">
                    {{-- Like Button --}}
                    <div class="flex items-center gap-1">
                        <button class="like-btn cursor-pointer size-8 flex items-center justify-center border border-green-400/20 bg-green-400/10 rounded-[50%] font-medium" data-event="{{ $event->id }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-heart-icon lucide-heart 
         {{ Auth::check() && $event->isLikedBy(Auth::user()) ? 'text-red-500' : 'text-orange-400/80' }}">
                                <path d="M2 9.5a5.5 5.5 0 0 1 9.591-3.676.56.56 0 0 0 .818 0A5.49 5.49 0 0 1 22 9.5c0 2.29-1.5 4-3 5.5l-5.492 5.313a2 2 0 0 1-3 .019L5 15c-1.5-1.5-3-3.2-3-5.5" />
                            </svg>
                        </button>
                        <!-- Like count -->
                        <span id="likes-count-{{ $event->id }}" class="text-white/80 text-sm font-medium after:ml-1 relative after:w-1 after:bg-orange-400/70 after:rounded-xl after:h-3 flex items-center">
                            {{ $event->likes->count() }}
                        </span>
                    </div>

                    <div class="flex items-center gap-2">
                        <button id="share-btn" class="size-8 flex items-center justify-center rounded-[50%] bg-black/80 border border-green-400/20 text-orange-400/70 hover:bg-orange-400/80 hover:text-black transition" data-link="{{ url()->current() }}" title="Share Event">
                            <i class="fa-solid fa-share-nodes"></i>
                        </button>
                    </div>

                </div>
                </form>
            </div>

        </div>

    </div>
    </div>
</x-layout>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('ticketHandler', (prices) => ({
        selected: 'regular',
        quantity: 1,
        prices,

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
});

// initialize default values
document.addEventListener("DOMContentLoaded", () => {
    document.getElementById('ticket_type_input').value = 'regular';
    document.getElementById('quantity_input').value = 1;
});
</script>
