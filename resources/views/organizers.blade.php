<x-layout>

    <div class="p-5 flex flex-col gap-10">
        <div class="grid grid-cols-2 gap-10">

            <!-- LEFT SECTION -->
            <div class="flex flex-col gap-5">

                <!-- Heading -->
                <h1 class="text-5xl w-[80%] text-orange-400/60">
                    Build and share interactive stories on Our Platform
                </h1>

                <!-- Sub text -->
                <p class="font-light w-[80%] text-white/60 ml-3 font-mono">
                    Lorem ipsum dolor sit, amet consectetur adipisicing elit. Corrupti aspernatur reprehenderit soluta eos error culpa dolorum doloremque necessitatibus velit impedit!
                </p>

                <!-- Become Organizer Button -->
                <a href="{{ route('organizer.create') }}">
                    <div class="p-1 flex gap-1 items-center bg-orange-400/60 text-black/60 font-medium text-sm w-fit rounded-4xl">
                        <span class="text-orange-400">
                            <p class="size-8 flex items-center justify-center rounded-full text-orange-400/80 bg-black/95 border border-green-400/15 text-md">
                                <i class="fa-solid fa-plus"></i>
                            </p>
                        </span>
                        <span class="pr-2 text-black/90 font-mono text-xs font-medium">Become an organizer</span>
                    </div>
                </a>

                <!-- 2 small cards -->
                <div class="grid grid-cols-2 gap-10">

                    <!-- LEFT SMALL CARD -->
                    <div class="flex flex-col justify-between p-2 rounded-3xl bg-green-400/10">
                        <div class="p-1 px-2 rounded-2xl border border-green-400/20 bg-green-400/10 font-medium text-orange-400/80 text-xs w-fit">lorem</div>
                        <div class="font-light font-mono text-sm text-white/60">
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Veritatis, unde! Saepe ipsa magnam eligendi modi.
                        </div>
                    </div>

                    <!-- RIGHT SMALL CARD -->
                    <div class="flex flex-col justify-around p-2 rounded-3xl bg-green-400/10 gap-10">
                        <div class="p-2 flex items-center justify-center border border-green-400/20 bg-green-400/10 text-orange-400/80 font-medium text-sm rounded-4xl">
                            The number dont lie
                        </div>

                        <!-- Stats -->
                        <div class="font-mono">
                            <div class="grid grid-cols-3 gap-3 mb-2">

                                <div class="flex flex-col gap-3 col-span-1 items-center">
                                    <h1 class="text-xl text-white/80 tracking-widest">{{ number_format($totalUsers) }}</h1>
                                    <p class="text-white/50">Users</p>
                                </div>

                                <div class="flex flex-col gap-3 border-l border-r border-white/60 col-span-1 items-center">
                                    <h1 class="text-xl text-white/80 tracking-widest">{{ number_format($totalEvents) }}</h1>
                                    <p class="text-white/50">Events</p>
                                </div>

                                <div class="flex flex-col gap-3 col-span-1 items-center">
                                    <h1 class="text-xl text-white/80 tracking-widest">{{ number_format($totalOrganizers) }}</h1>
                                    <p class="text-white/50">Organizers</p>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>

            </div>

            <!-- RIGHT SECTION -->
            <div class="w-full h-full flex flex-col justify-between">

                <!-- Carousel Container -->
                <div class="w-full h-[61vh] relative overflow-hidden rounded-4xl opacity-70" x-data="imageCarousel({{ count($carouselImages) }})">

                    <div class="relative w-full h-full">
                        @foreach($carouselImages as $index => $image)
                        <div x-show="currentSlide === {{ $index }}" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-500" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="absolute inset-0">
                            <img src="{{ asset($image) }}" onerror="this.onerror=null; this.src='{{ asset('img1.jpg') }}';" class="w-full h-full object-cover z-50" alt="Carousel image {{ $index + 1 }}">
                        </div>
                        @endforeach
                    </div>

                    <!-- Arrows -->
                    @if(count($carouselImages) > 1)
                    <button @click="previousSlide()" class="absolute left-4 top-1/2 -translate-y-1/2 bg-black/70 hover:bg-black/90 text-orange-400/80 p-2 rounded-full border border-green-400/15 transition z-10">
                        <i class="fa-solid fa-chevron-left"></i>
                    </button>

                    <button @click="nextSlide()" class="absolute right-4 top-1/2 -translate-y-1/2 bg-black/70 hover:bg-black/90 text-orange-400/80 p-2 rounded-full border border-green-400/15 transition z-10">
                        <i class="fa-solid fa-chevron-right"></i>
                    </button>

                    <!-- Dots -->
                    <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2 z-10">
                        @foreach($carouselImages as $index => $image)
                        <button @click="goToSlide({{ $index }})" :class="currentSlide === {{ $index }} ? 'bg-orange-400/80' : 'bg-white/40'" class="w-2 h-2 rounded-full transition"></button>
                        @endforeach
                    </div>
                    @endif

                </div>

                <!-- Bottom Text + Tiny Tags -->
                <div class="flex flex-col justify-between gap-3">
                    <h1 class="text-orange-400/60 font-medium">
                        Lorem ipsum, dolor sit amet consectetur adipisicing elit. Animi, tempore.
                    </h1>

                    <div class="flex gap-5 items-center justify-between">
                        <p class="text-white/80 w-fit text-sm">The biggest trends</p>

                        <ul class="flex justify-between items-center gap-5 p-1 border border-green-400/20 bg-green-400/10 rounded-3xl w-auto">
                            @for($i = 0; $i < 6; $i++) <li class="p-1 px-3 rounded-3xl text-xs bg-black/70 font-mono text-orange-400/60">Thing</li>
                                @endfor
                        </ul>
                    </div>

                </div>

            </div>

        </div>
    </div>


    <div class="p-5">
        <div class="flex items-center justify-between mb-5">
            <h1 class="text-3xl text-white">Top organizers</h1>
        </div>

        <div class="grid grid-cols-4 gap-5">
            @foreach($organizers as $organizer)
            <div class="h-fit col-span-1 w-full bg-green-400/10 p-3 rounded-3xl">
                <div class="flex justify-between mb-5">
                    <div class="flex items-center gap-3">
                        <div class="p-[1px] bg-orange-400/70 border border-green-400/15 rounded-[50%] overflow-hidden">
                            <img src="{{ $organizer->organizer_image ? asset('storage/'.$organizer->organizer_image) : asset('mood.png') }}" class='size-11 rounded-[50%] object-cover' alt="" />
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
                    <a href="{{ route('organizer.details', $organizer->id) }}" class='p-1 flex gap-1 items-center bg-black/70 border border-green-400/15 text-sm w-fit rounded-4xl'>
                        <span>
                            <p class='size-8 flex items-center justify-center rounded-[50%] text-black/90 bg-orange-400/70 border border-green-400/10 text-md'>
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                            </p>
                        </span>
                        <span class='pr-2 text-xs font-mono text-orange-400/60 font-medium'>Details</span>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>



    <div class="p-5">
        <div class="flex flex-col gap-3">
            <h1 class="text-3xl text-white/60">Unleash Your Creativity</h1>
            <p class="font-mono font-light text-white/60 text-sm w-[50%]">Lorem ipsum dolor sit amet consectetur adipisicing elit. Consequuntur minima doloribus quidem reiciendis, quos alias tenetur corporis commodi harum enim.</p>
        </div>

        <div class="grid grid-cols-3 gap-5 mt-5">
            <div class="p-5 col-span-1 flex flex-col gap-3 rounded-3xl bg-green-400/10">
                <span>
                    <p class='size-10 flex items-center justify-center rounded-[50%] text-black/90 bg-orange-400/70 border border-green-400/10 text-md'>
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-banknote-arrow-up-icon lucide-banknote-arrow-up">
                            <path d="M12 18H4a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5" />
                            <path d="M18 12h.01" />
                            <path d="M19 22v-6" />
                            <path d="m22 19-3-3-3 3" />
                            <path d="M6 12h.01" />
                            <circle cx="12" cy="12" r="2" /></svg>
                    </p>
                </span>
                <div class="text-xl text-orange-400/70">
                    Instant Credit
                </div>
                <div class="text-white/50 font-mono text-sm font-medium ">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Suscipit consequuntur dicta quod dolorum consectetur ducimus quibusdam dignissimos voluptatum autem libero?
                </div>
            </div>
            <div class="p-5 col-span-1 flex flex-col gap-3 rounded-3xl bg-green-400/10">
                <span>
                    <p class='size-10 flex items-center justify-center rounded-[50%] text-black/90 bg-orange-400/70 border border-green-400/10 text-md'>
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-banknote-arrow-up-icon lucide-banknote-arrow-up">
                            <path d="M12 18H4a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5" />
                            <path d="M18 12h.01" />
                            <path d="M19 22v-6" />
                            <path d="m22 19-3-3-3 3" />
                            <path d="M6 12h.01" />
                            <circle cx="12" cy="12" r="2" /></svg>
                    </p>
                </span>
                <div class="text-xl text-orange-400/70">
                    Instant Credit
                </div>
                <div class="text-white/50 font-mono text-sm font-medium ">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Suscipit consequuntur dicta quod dolorum consectetur ducimus quibusdam dignissimos voluptatum autem libero?
                </div>
            </div>
            <div class="p-5 col-span-1 flex flex-col gap-3 rounded-3xl bg-green-400/10">
                <span>
                    <p class='size-10 flex items-center justify-center rounded-[50%] text-black/90 bg-orange-400/70 border border-green-400/10 text-md'>
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-banknote-arrow-up-icon lucide-banknote-arrow-up">
                            <path d="M12 18H4a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5" />
                            <path d="M18 12h.01" />
                            <path d="M19 22v-6" />
                            <path d="m22 19-3-3-3 3" />
                            <path d="M6 12h.01" />
                            <circle cx="12" cy="12" r="2" /></svg>
                    </p>
                </span>
                <div class="text-xl text-orange-400/70">
                    Instant Credit
                </div>
                <div class="text-white/50 font-mono text-sm font-medium ">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Suscipit consequuntur dicta quod dolorum consectetur ducimus quibusdam dignissimos voluptatum autem libero?
                </div>
            </div>
        </div>
    </div>



</x-layout>



{{-- @push('carousel') --}}
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('imageCarousel', (totalSlides) => ({
            currentSlide: 0
            , totalSlides
            , autoplayInterval: null,

            init() {
                this.startAutoplay();
            },

            nextSlide() {
                this.currentSlide = (this.currentSlide + 1) % this.totalSlides;
                this.resetAutoplay();
            },

            previousSlide() {
                this.currentSlide = (this.currentSlide - 1 + this.totalSlides) % this.totalSlides;
                this.resetAutoplay();
            },

            goToSlide(index) {
                this.currentSlide = index;
                this.resetAutoplay();
            },

            startAutoplay() {
                this.autoplayInterval = setInterval(() => {
                    this.nextSlide();
                }, 4000);
            },

            resetAutoplay() {
                clearInterval(this.autoplayInterval);
                this.startAutoplay();
            }
        }));
    });

</script>
{{-- @endpush --}}
