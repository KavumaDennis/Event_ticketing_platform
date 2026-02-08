<x-layout>

    <section class="p-5">
        <div class="flex flex-col gap-4 items-center">
            <div class="flex md:flex-row flex-col items-center gap-4">
                <a href="" class="bg-orange-400/60 border border-green-400/15 text-black/80 font-medium p-2 px-3 rounded-3xl font-mono text-sm">Contact Us</a>
                <p class="md:text-sm text-xs text-white/60 font-medium font-mono">Lorem ipsum dolor sit amet consectetur adipisicing</p>
            </div>
            <div class="text-4xl lg:text-5xl tracking-wide text-center  lg:leading-15 text-white/70">
                A ticket system that <br /> works like an <span class="text-orange-400/60">Organizer</span>
            </div>
            <div class="w-[80%] lg:w-[50%] text-center text-white/50 font-light font-mono text-sm">Lorem ipsum dolor sit amet consectetur, adipisicing elit. Rem sunt suscipit cumque, ea temporibus tempore sed ipsam assumenda est labore ullam reprehenderit et reiciendis repudiandae.</div>
            <div class="flex items-center gap-2 bg-orange-400/70 p-0.5 rounded-3xl font-semibold border border-green-400/15">
                <a href="{{{ route('organizer.create') }}}" class="px-3 p-2 text-xs font-medium font-mono bg-black/95 text-orange-400/70 rounded-3xl border border-green-400/15">Become an organizer</a>
                <a href="{{ route('trends.create') }}" class="text-xs font-medium font-mono pr-2">Your first post</a>
            </div>
        </div>
    </section>

    <section class="p-5">
        <div class="flex flex-col md:flex-row items-center gap-3 md:items-end justify-between mb-5">
            <div class="text-3xl text-center md:text-start text-white/60 md:w-[60%]">
                Comprehensive platform <br class="hidden lg:block" />for discovering and booking events
            </div>

            <div class="flex justify-between items-center md:gap-4 gap-6 bg-orange-400/70 border border-green-400/10 p-0.5 rounded-3xl">
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
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 lg:gap-15">
            <div class="flex flex-col p-2 justify-between bg-green-400/10 backdrop-blur-[1px] rounded-4xl overflow-hidden">
                <div class="flex flex-col gap-3 md:gap-2 lg:gap-4 p-2 px-2">
                    <div class="flex justify-between">
                        <div class="flex justify-between items-center gap-3">
                            <span class="md:px-2 lg:px-3 p-1 px-3 bg-green-400/10 border border-green-400/20 text-white/60 font-mono rounded-2xl font-medium text-xs">Events</span>
                            <span class="md:px-2 lg:px-3 p-1 px-3 bg-green-400/10 border border-green-400/20 text-white/60 font-mono rounded-2xl font-medium text-xs">Experience</span>
                        </div>
                        <div class="lg:size-9 md:size-8 size-9 flex justify-center items-center rounded-[50%] bg-[#b0a6df]/90 border border-black/70 text-black/90 font-bold">
                            <i class="fa-solid fa-tent"></i>
                        </div>
                    </div>
                    <div class="text-4xl md:text-2xl lg:text-4xl font-medium text-orange-400/70">
                        <h1>All</h1>
                        <h1>Events</h1>
                    </div>
                    <div class="md:text-xs lg:text-sm font-medium text-white/50 font-mono">
                        <p>Discover events that match your passions, connect with like-minded individuals, and create unforgettable memories. Start exploring now!</p>
                    </div>
                </div>
                <div class="h-52 md:h-38 lg:h-52 relative">
                    <img src="{{ asset('img1.jpg') }}" class="h-full w-full absolute object-cover rounded-3xl z-10 opacity-50 right-0 left-0" alt="" />
                    <a href="{{ route('events') }}" class="p-0.5 absolute left-3 bottom-3 text-sm bg-orange-400/70 border border-green-400/15 z-50 font-medium rounded-3xl flex items-center gap-2">
                        <span class='bg-black/90 border border-green-400/15 size-8 flex items-center justify-center rounded-[50%]'>
                            <p class='text-sm text-orange-400/90'>
                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-rectangle-ellipsis-icon lucide-rectangle-ellipsis">
                                    <rect width="20" height="12" x="2" y="6" rx="2" />
                                    <path d="M12 12h.01" />
                                    <path d="M17 12h.01" />
                                    <path d="M7 12h.01" /></svg>
                            </p>
                        </span>
                        <span class='text-black/90 font-medium text-xs font-mono  mr-1'>Explore more</span>
                    </a>
                </div>
            </div>
            <div class="flex flex-col p-2 justify-between bg-green-400/10 backdrop-blur-[1px]  rounded-4xl overflow-hidden">
                <div class="flex flex-col gap-3 md:gap-2 lg:gap-4 p-2 px-2">
                    <div class="flex justify-between">
                        <div class="flex justify-between items-center gap-3">
                            <span class="md:px-2 lg:px-3 p-1 px-3 bg-green-400/10 border border-green-400/20 text-white/60 font-mono rounded-2xl font-medium text-xs">Lifestyle</span>
                            <span class="md:px-2 lg:px-3 p-1 px-3 bg-green-400/10 border border-green-400/20 text-white/60 font-mono rounded-2xl font-medium text-xs">News</span>
                        </div>
                        <div class="lg:size-9 md:size-8 size-9 flex justify-center items-center rounded-[50%] bg-[#b0a6df]/90 border border-black/70 text-black/90 font-bold">
                            <i class="fa-solid fa-chart-simple"></i>
                        </div>
                    </div>
                    <div class="text-4xl md:text-2xl lg:text-4xl font-medium text-orange-400/70">
                        <h1>All</h1>
                        <h1>Trends</h1>
                    </div>
                    <div class="md:text-xs lg:text-sm  font-medium text-white/50 font-mono">
                        <p>Discover events that match your passions, connect with like-minded individuals, and create unforgettable memories. Start exploring now!</p>
                    </div>
                </div>
                <div class="h-52 md:h-38 lg:h-52 relative">
                    <img src="{{ asset('trends.jpg') }}" class="h-full w-full absolute object-cover rounded-3xl z-10 opacity-50 right-0 left-0" alt="" />
                    <a href="{{ route('trends') }}" class="p-0.5 absolute left-3 bottom-3 text-sm bg-orange-400/70 border border-green-400/15 z-50 font-medium rounded-3xl flex items-center gap-2">
                        <span class='bg-black/90 border border-green-400/15 size-8 flex items-center justify-center rounded-[50%]'>
                            <p class='text-sm text-orange-400/90'>
                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-rectangle-ellipsis-icon lucide-rectangle-ellipsis">
                                    <rect width="20" height="12" x="2" y="6" rx="2" />
                                    <path d="M12 12h.01" />
                                    <path d="M17 12h.01" />
                                    <path d="M7 12h.01" /></svg>
                            </p>
                        </span>
                        <span class='text-black/90 font-medium text-xs font-mono mr-1'>Explore more</span>
                    </a>
                </div>
            </div>
            <div class="flex flex-col p-2 justify-between bg-green-400/10 backdrop-blur-[1px]  rounded-4xl overflow-hidden">
                <div class="flex flex-col gap-3 md:gap-2 lg:gap-4 p-2 px-2">
                    <div class="flex justify-between">
                        <div class="flex justify-between items-center gap-3">
                            <span class="md:px-2 lg:px-3 p-1 px-3 bg-green-400/10 border border-green-400/20 text-white/60 font-mono rounded-2xl font-medium text-xs">Blur</span>
                            <span class="md:px-2 lg:px-3 p-1 px-3 bg-green-400/10 border border-green-400/20 text-white/60 font-mono rounded-2xl font-medium text-xs">Blur</span>
                        </div>
                        <div class="lg:size-9 md:size-8 size-9 flex justify-center items-center rounded-[50%] bg-[#b0a6df]/90 border border-black/70 text-black/90 font-bold">
                            <i class="fa-solid fa-business-time"></i>
                        </div>
                    </div>
                    <div class="text-4xl md:text-2xl lg:text-4xl font-medium text-orange-400/70">
                        <h1>All</h1>
                        <h1>Business</h1>
                    </div>
                    <div class="md:text-xs lg:text-sm  font-medium text-white/50 font-mono">
                        <p>Discover events that match your passions, connect with like-minded individuals, and create unforgettable memories. Start exploring now!</p>
                    </div>
                </div>
                <div class="h-52 md:h-38 lg:h-52 relative">
                    <img src="{{ asset('business.jpg') }}" class="h-full w-full absolute object-cover rounded-3xl z-10 opacity-50 right-0 left-0" alt="" />
                    <a href="{{ route('organizers') }}" class="p-0.5 absolute left-3 bottom-3 text-sm bg-orange-400/70 border border-green-400/15 z-50 font-medium rounded-3xl flex items-center gap-2">
                        <span class='bg-black/90 border border-green-400/15 size-8 flex items-center justify-center rounded-[50%]'>
                            <p class='text-sm text-orange-400/90'>
                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-rectangle-ellipsis-icon lucide-rectangle-ellipsis">
                                    <rect width="20" height="12" x="2" y="6" rx="2" />
                                    <path d="M12 12h.01" />
                                    <path d="M17 12h.01" />
                                    <path d="M7 12h.01" /></svg>
                            </p>
                        </span>
                        <span class='text-black/90 font-medium font-mono text-xs mr-1'>Explore more</span>
                    </a>
                </div>
            </div>



        </div>
    </section>

    <section class="p-5">
        <div class="w-full h-[400px] grid grid-cols-3 rounded-4xl overflow-hidden">
            <div class="col-span-1 w-full h-ful relative">
                <img src="{{ asset('reviews.jpg') }}" class="absolute w-full h-full object-cover opacity-50" alt="" />
            </div>
            <div x-data='reviewSlider(@json($reviewsForAlpine))' class="col-span-2 grid grid-cols-3 p-4 bg-green-400/10 backdrop-blur-[1px]">
                <div class="flex flex-col justify-between col-span-2 pr-3">
                    <div class="text-5xl">
                        <h1 class="tracking-wide text-black/80">Deliver extraordinary experiences on every channel</h1>
                    </div>
                    <div class="flex items-end justify-between w-full">
                        <div class="text-white/70 flex gap-3 items-center">
                            <button id="openReviewsModal" class="flex items-center font-mono text-xs font-medium text-black/90 gap-1 bg-orange-400/70 p-0.5 rounded-3xl">
                                <span class='size-8 flex justify-center items-center text-md bg-black/90 text-orange-400/80 p-2 rounded-[50%]'>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-message-circle-code-icon lucide-message-circle-code">
                                        <path d="m10 9-3 3 3 3" />
                                        <path d="m14 15 3-3-3-3" />
                                        <path d="M2.992 16.342a2 2 0 0 1 .094 1.167l-1.065 3.29a1 1 0 0 0 1.236 1.168l3.413-.998a2 2 0 0 1 1.099.092 10 10 0 1 0-4.777-4.719" /></svg>
                                </span>
                                <span class="pr-2 font-medium">
                                    Review
                                </span>
                            </button>
                            <p class="font-mono text-xs">
                                Write your review
                            </p>
                        </div>
                        <div class="flex gap-3 bg-black/90 p-0.5 border border-green-400/10  rounded-3xl">
                            <!-- Left Arrow -->
                            <span @click="prevReview()" class='size-8 flex justify-center items-center text-md bg-orange-400/70 text-black p-2 rounded-[50%] cursor-pointer hover:bg-orange-500 transition'>
                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M13 9a1 1 0 0 1-1-1V5.061a1 1 0 0 0-1.811-.75l-6.835 6.836a1.207 1.207 0 0 0 0 1.707l6.835 6.835a1 1 0 0 0 1.811-.75V16a1 1 0 0 1 1-1h2a1 1 0 0 0 1-1v-4a1 1 0 0 0-1-1z" />
                                    <path d="M20 9v6" />
                                </svg>
                            </span>

                            <!-- Right Arrow -->
                            <span @click="nextReview()" class='size-8 flex justify-center items-center text-md bg-orange-400/70 text-black p-2 rounded-[50%] cursor-pointer hover:bg-orange-500 transition'>
                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M11 9a1 1 0 0 0 1-1V5.061a1 1 0 0 1 1.811-.75l6.836 6.836a1.207 1.207 0 0 1 0 1.707l-6.836 6.835a1 1 0 0 1-1.811-.75V16a1 1 0 0 0-1-1H9a1 1 0 0 1-1-1v-4a1 1 0 0 1 1-1z" />
                                    <path d="M4 9v6" />
                                </svg>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="w-full flex flex-col justify-between col-span-1 p-4 bg-black/20 rounded-2xl">
                    <!-- Review Card -->
                    <div class="flex flex-col mb-4">
                        <img :src="currentReview.user_photo" class="size-15 rounded-[50%] object-cover" alt="Reviewer photo" />
                        <div class="text-white/70 text-sm font-mono max-h-40 overflow-y-auto p-2 w-full">
                            <p x-text="currentReview.body"></p>
                        </div>
                    </div>

                    <div class="border-t pt-4 border-white/20">
                        <div class="flex gap-1 mb-1">
                            <template x-for="i in 5" :key="i">
                                <svg class="w-4 h-4" :class="i <= currentReview.rating ? 'text-yellow-400' : 'text-white/30'" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2l2.9 6 6.6.6-5 4.5 1.5 6.4L12 16.9 6 19.5l1.5-6.4-5-4.5 6.6-.6L12 2z" />
                                </svg>
                            </template>
                        </div>

                        <p class="text-xs text-white/50 mb-1" x-text="new Date(currentReview.created_at).toLocaleString([], { 
        year: 'numeric', 
        month: 'short', 
        day: '2-digit', 
        hour: '2-digit', 
        minute: '2-digit' 
        })"></p>

                        <p class="text-white font-semibold" x-text="currentReview.user_name"></p>
                    </div>
                </div>
            </div>

        </div>

        <div id="reviewsModal" class="fixed inset-0 bg-black/30 hidden items-center justify-center z-50">
            <div class="bg-black/85 bg-[url(/public/bg-img.png)] bg-cover bg-center bg-fixed  bg-blend-multiply relative border border-green-400/30 backdrop-blur-[1px] rounded-2xl w-full max-w-2xl p-6">
                <button id="closeReviewsModal" class="absolute top-4 right-4 text-white/70 hover:text-white text-xl">&times;</button>
                <h2 class="text-xl font-semibold text-white mb-4">Website Reviews</h2>
                {{-- Review Form --}}
                @auth
                <form id="reviewForm" class="flex flex-col gap-2 mb-4">
                    @csrf
                    <div class="flex items-center gap-1">
                        <label class="text-white/70">Rating:</label>
                        <div id="reviewStars" class="flex gap-1">
                            @for ($i = 1; $i <= 5; $i++) <svg data-value="{{ $i }}" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="star cursor-pointer text-white/50">
                                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
                                </svg>
                                @endfor
                        </div>
                    </div>

                    <input type="hidden" name="rating" id="reviewRating" value="0">

                    <!-- Replaced input with textarea -->
                    <textarea name="body" placeholder="Write your review..." class="flex-1 p-2 rounded-xl bg-black/50 text-white outline-none resize-none" maxlength="500" rows="4"></textarea>

                    <button type="submit" class="bg-orange-400 px-4 py-2 rounded-xl text-sm hover:bg-orange-500 transition">Post</button>
                </form>
                @endauth

            </div>
        </div>

    </section>

    {{-- <section class="p-5">
        <div class="w-full flex justify-between items-center mb-5">
            <h1 class='text-3xl text-white/60'>Akavaako is changing thousands of lives</h1>
            <div class="flex  gap-3 bg-orange-400/70 p-1 rounded-3xl">
                <span class='size-8 flex justify-center items-center text-md bg-black text-orange-400 p-2 rounded-[50%]'>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-big-left-dash-icon lucide-arrow-big-left-dash">
                        <path d="M13 9a1 1 0 0 1-1-1V5.061a1 1 0 0 0-1.811-.75l-6.835 6.836a1.207 1.207 0 0 0 0 1.707l6.835 6.835a1 1 0 0 0 1.811-.75V16a1 1 0 0 1 1-1h2a1 1 0 0 0 1-1v-4a1 1 0 0 0-1-1z" />
                        <path d="M20 9v6" /></svg>
                </span>
                <span class='size-8 flex justify-center items-center text-md bg-black text-orange-400 p-2 rounded-[50%]'>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-big-right-dash-icon lucide-arrow-big-right-dash">
                        <path d="M11 9a1 1 0 0 0 1-1V5.061a1 1 0 0 1 1.811-.75l6.836 6.836a1.207 1.207 0 0 1 0 1.707l-6.836 6.835a1 1 0 0 1-1.811-.75V16a1 1 0 0 0-1-1H9a1 1 0 0 1-1-1v-4a1 1 0 0 1 1-1z" />
                        <path d="M4 9v6" /></svg>
                </span>
            </div>
        </div>
        <div class="grid grid-cols-4 gap-7">
            <div class="relative rounded-3xl w-full h-90 overflow-hidden">
                <div class="absolute top-3 left-3 flex items-center gap-2">
                    <div class="rounded-[50%] p-[2pxv] bg-black/60 border border-purple-400/60 overflow-hidden">
                        <img src="{{ asset('img6.jpg') }}" class='size-11 object-cover rounded-[50%]' alt="" />
    </div>
    <div class="flex flex-col text-white font-medium">
        <p class='text-sm'>@kavumadennis</p>
        <p class='text-xs'>100K likes</p>
    </div>
    </div>
    <img src="{{ asset('img1.jpg') }}" class='object-cover rounded-2xl w-full h-full' alt="" />
    </div>
    <div class="relative rounded-3xl w-full h-90 overflow-hidden">
        <div class="absolute top-3 left-3 flex items-center gap-2">
            <div class="rounded-[50%] p-[2pxv] bg-black/60 border border-purple-400/60 overflow-hidden">
                <img src="{{ asset('img6.jpg') }}" class='size-11 object-cover rounded-[50%]' alt="" />
            </div>
            <div class="flex flex-col text-white font-medium">
                <p class='text-sm'>@kavumadennis</p>
                <p class='text-xs'>100K likes</p>
            </div>
        </div>
        <img src="{{ asset('img1.jpg') }}" class='object-cover rounded-2xl w-full h-full' alt="" />
    </div>
    <div class="relative rounded-3xl w-full h-90 overflow-hidden">
        <div class="absolute top-3 left-3 flex items-center gap-2">
            <div class="rounded-[50%] p-[2pxv] bg-black/60 border border-purple-400/60 overflow-hidden">
                <img src="{{ asset('img6.jpg') }}" class='size-11 object-cover rounded-[50%]' alt="" />
            </div>
            <div class="flex flex-col text-white font-medium">
                <p class='text-sm'>@kavumadennis</p>
                <p class='text-xs'>100K likes</p>
            </div>
        </div>
        <img src="{{ asset('img1.jpg') }}" class='object-cover rounded-2xl w-full h-full' alt="" />
    </div>
    <div class="relative rounded-3xl w-full h-90 overflow-hidden">
        <div class="absolute top-3 left-3 flex items-center gap-2">
            <div class="rounded-[50%] p-[2pxv] bg-black/60 border border-purple-400/60 overflow-hidden">
                <img src="{{ asset('img6.jpg') }}" class='size-11 object-cover rounded-[50%]' alt="" />
            </div>
            <div class="flex flex-col text-white font-medium">
                <p class='text-sm'>@kavumadennis</p>
                <p class='text-xs'>100K likes</p>
            </div>
        </div>
        <img src="{{ asset('img1.jpg') }}" class='object-cover rounded-2xl w-full h-full' alt="" />
    </div>

    </div>
    </section> --}}


    <div class="p-5 ">
        <div class="flex flex-col md:flex-row justify-between md:items-center gap-3 mb-6">
            <h1 class='text-3xl font-medium text-white/80'>Events</h1>
            <div class="flex gap-5 items-center">
                <form id="search-form" method="GET" action="{{ route('home') }}" class="relative">
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
                                <a href="{{ route('home') }}" class="category-filter px-2 py-1 {{ !request('category') ? 'bg-orange-400/70 text-black/90' : 'bg-black/60 text-white/70 hover:bg-orange-400/50' }} border border-green-400/15 rounded-3xl text-sm font-medium transition" data-category="">
                                    All
                                </a>

                                @foreach($categories as $category)
                                <a href="{{ route('home', ['category' => $category, 'search' => request('search')]) }}" class="category-filter px-3 py-1 {{ request('category') === $category ? 'bg-orange-400/70 text-black/90' : 'bg-black/60 text-white/70 hover:bg-orange-400/50' }} border border-green-400/15 rounded-3xl text-sm font-medium transition" data-category="{{ $category }}">
                                    {{ ucfirst($category) }}
                                </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>


        <div id="events-container">
            @include('partials.events-grid', ['events' => $events])
        </div>

    </div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @push('scripts')
    <script>
        $(document).ready(function() {
            const container = $('#events-container');
            const searchInput = $('input[name="search"]');
            const searchForm = $('#search-form');

            function updateEvents(url) {
                $.ajax({
                    url: url
                    , type: 'GET'
                    , success: function(data) {
                        container.html(data);
                        // Update active class on category buttons
                        const urlObj = new URL(url);
                        const category = urlObj.searchParams.get('category') || '';

                        $('.category-filter').each(function() {
                            const isMatch = $(this).data('category') == category;
                            $(this).toggleClass('bg-orange-400/70 text-black/90', isMatch);
                            $(this).toggleClass('bg-black/60 text-white/70 hover:bg-orange-400/50', !isMatch);
                        });

                        window.history.pushState({}, '', url);
                    }
                });
            }

            searchForm.on('submit', function(e) {
                e.preventDefault();
                const url = new URL(this.action);
                url.searchParams.set('search', searchInput.val());
                // Preserve category if present
                const params = new URLSearchParams(window.location.search);
                if (params.has('category')) {
                    url.searchParams.set('category', params.get('category'));
                }
                updateEvents(url.toString());
            });

            $('.category-filter').on('click', function(e) {
                e.preventDefault();
                updateEvents($(this).attr('href'));
            });

            $(document).on('click', '#pagination-links a', function(e) {
                e.preventDefault();
                updateEvents($(this).attr('href'));
            });
        });

    </script>
    @endpush

</x-layout>


<script>
    // ---------------------------
    // Submit Review
    // ---------------------------
    $(document).on('submit', '#reviewForm', function(e) {
        e.preventDefault();

        const body = $(this).find('textarea[name="body"]').val().trim();
        const rating = $('#reviewRating').val();

        if (!body) {
            alert('Please write a review');
            return;
        }

        if (rating == 0) {
            alert('Please select a rating');
            return;
        }

        $.post("{{ route('site.reviews.store') }}", {
                _token: "{{ csrf_token() }}"
                , body
                , rating
            })
            .done(() => {
                $(this).find('input[name="body"]').val('');
                $('#reviewRating').val(0);

                // Reset stars
                $('#reviewStars svg')
                    .removeClass('text-yellow-400')
                    .addClass('text-white/50');

                loadReviews();
            })
            .fail(() => {
                alert('Error posting review');
            });
    });

    // ---------------------------
    // Modal open / close
    // ---------------------------
    $(document).on('click', '#openReviewsModal', function() {
        $('#reviewsModal').removeClass('hidden').addClass('flex');
    });

    $(document).on('click', '#closeReviewsModal', function() {
        $('#reviewsModal').removeClass('flex').addClass('hidden');
    });

    // ---------------------------
    // Star Rating Logic (delegated)
    // ---------------------------
    $(document).on('mouseenter', '#reviewStars svg', function() {
        const val = $(this).data('value');

        $('#reviewStars svg').each(function() {
            $(this).toggleClass('text-yellow-400', $(this).data('value') <= val);
            $(this).toggleClass('text-white/50', $(this).data('value') > val);
        });
    });

    $(document).on('mouseleave', '#reviewStars', function() {
        const rating = $('#reviewRating').val();

        $('#reviewStars svg').each(function() {
            $(this).toggleClass('text-yellow-400', $(this).data('value') <= rating);
            $(this).toggleClass('text-white/50', $(this).data('value') > rating);
        });
    });

    $(document).on('click', '#reviewStars svg', function() {
        const val = $(this).data('value');

        $('#reviewRating').val(val);

        $('#reviewStars svg').each(function() {
            $(this).toggleClass('text-yellow-400', $(this).data('value') <= val);
            $(this).toggleClass('text-white/50', $(this).data('value') > val);
        });
    });

    function renderStars(rating) {
        let stars = '';
        for (let i = 1; i <= 5; i++) {
            stars += `
            <svg class="w-4 h-4 ${i <= rating ? 'text-yellow-400' : 'text-white/30'}"
                 xmlns="http://www.w3.org/2000/svg"
                 fill="currentColor"
                 viewBox="0 0 24 24">
                <path d="M12 2l2.9 6 6.6.6-5 4.5 1.5 6.4L12 16.9 6 19.5l1.5-6.4-5-4.5 6.6-.6L12 2z"/>
            </svg>
        `;
        }
        return stars;
    }

</script>
