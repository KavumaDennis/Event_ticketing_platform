<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Document</title>
    @vite('resources/css/app.css')
    <script src="//unpkg.com/alpinejs" defer></script>


    {{-- <script src="//unpkg.com/alpinejs" defer></script> --}}
    <style>
        @keyframes zoomIn {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.3);
            }

            100% {
                transform: scale(1);
            }
        }

        .zoom-animation {
            animation: zoomIn 0.3s ease-in-out;
        }

    </style>

</head>
<body class="bg-black/85 bg-[url(/public/bg-img.png)] bg-cover bg-center bg-fixed  bg-blend-multiply relative" x-data="{ mobileMenuOpen: false }">
    <header>
        <div class="flex justify-between items-center p-5 relative">
            <div class="flex justify-between items-center gap-1 p-0.5 pr-1.5 border border-purple-100/20 rounded-2xl">
                <p class='p-0.5 px-1.5 bg-orange-400 rounded-xl border border-green-400/30 text-black/80'>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-moon">
                        <path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z" /></svg>
                </p>
                <p class='text-amber-400'>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-sun-medium">
                        <circle cx="12" cy="12" r="4" />
                        <path d="M12 3v1" />
                        <path d="M12 20v1" />
                        <path d="M3 12h1" />
                        <path d="M20 12h1" />
                        <path d="m18.364 5.636-.707.707" />
                        <path d="m6.343 17.657-.707.707" />
                        <path d="m5.636 5.636.707.707" />
                        <path d="m17.657 17.657.707.707" /></svg>
                </p>
            </div>
            <div class="p-2 bg-green-400/10 flex justify-between items-center gap-8 rounded-4xl md:gap-28">
                <p class="text-sm text-orange-400/70 ml-2">AKAVAAKO</p>
                <div class="hidden lg:flex items-center gap-5">
                    <a href="{{ route('home') }}" class="p-1 text-sm font-medium {{ request()->routeIs('home') ? 'text-orange-400 font-bold' : 'text-white/60 hover:text-white' }} transition-colors">Home</a>
                    {{-- <a href="{{ route('events') }}" class="p-1 text-sm font-medium {{ request()->routeIs('events') ? 'text-orange-400 font-bold' : 'text-white/60 hover:text-white' }} transition-colors">Events</a> --}}
                    <a href="{{ route('contact') }}" class="p-1 text-sm font-medium {{ request()->routeIs('contact') ? 'text-orange-400 font-bold' : 'text-white/60 hover:text-white' }} transition-colors">Contact Us</a>
                    <a href="{{ route('organizers') }}" class="p-1 text-sm font-medium {{ request()->routeIs('organizers') ? 'text-orange-400 font-bold' : 'text-white/60 hover:text-white' }} transition-colors">Organizer</a>
                    <a href="{{ route('trends') }}" class="p-1 text-sm font-medium {{ request()->routeIs('trends') ? 'text-orange-400 font-bold' : 'text-white/60 hover:text-white' }} transition-colors">Trends</a>
                    <a href="{{ route('events') }}" class="p-2 px-4 flex items-center {{ request()->routeIs('events') ? 'text-orange-400 font-bold' : 'text-white/60 hover:text-white' }} w-fit bg-black/80 border border-green-400/15 rounded-3xl text-sm font-medium">Events</a>
                    {{-- <a href="" class="p-2 px-4 flex items-center w-fit bg-black/80 border border-green-400/15 rounded-3xl text-orange-400/60 text-sm font-medium">Reels</a> --}}
                </div>

                {{-- Hamburger Menu Button --}}
                <div class="lg:hidden flex items-center pr-2">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-orange-400/70 p-2 focus:outline-none">
                        <svg x-show="!mobileMenuOpen" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-menu">
                            <line x1="4" x2="20" y1="12" y2="12" />
                            <line x1="4" x2="20" y1="6" y2="6" />
                            <line x1="4" x2="20" y1="18" y2="18" /></svg>
                        <svg x-show="mobileMenuOpen" x-cloak xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x">
                            <line x1="18" x2="6" y1="6" y2="18" />
                            <line x1="6" x2="18" y1="6" y2="18" /></svg>
                    </button>
                </div>
            </div>
            <div class="flex justify-between items-center gap-3">
                <div class="p-2 rounded-[50%] text-orange-400/70 font-medium font- text-sm">
                    Hey, {{ session('username', 'Guest') }}
                </div>
                @guest
                <div class="hidden md:flex justify-between items-center gap-3 p-0.5 bg-orange-400/60 border border-green-400/10 rounded-3xl pl-3">
                    <a href="{{ route('show.signup') }}" class='text-black/80 font-medium text-xs font-mono'>Sign Up</a>
                    <a href="{{ route('show.login') }}" class='bg-black/90 border border-green-400/15 text-xs font-mono font-medium text-orange-400/60 p-2 px-3 rounded-3xl flex items-center'>Log In</a>
                </div>
                @endguest

                @auth
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="flex items-center p-0.5 w-fit bg-orange-400/60 gap-1 rounded-3xl">
                        <a class='flex gap-1 items-center'>
                            <span class='text-sm pl-2 font-medium font-mono'>Log out</span>
                            <span class='p-2 rounded-[50%] bg-black/95 text-orange-400/80'>
                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-power-icon lucide-power">
                                    <path d="M12 2v10" />
                                    <path d="M18.4 6.6a9 9 0 1 1-12.77.04" /></svg>
                            </span>
                        </a>
                    </button>
                </form>
                @endauth
            </div>
        </div>

        {{-- Mobile Menu Overlay --}}
        <div x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-4" class="lg:hidden absolute top-24 left-5 right-5 z-40 bg-black/85 bg-[url(/public/bg-img.png)] bg-cover bg-center bg-fixed  bg-blend-multiply border border-green-400/30 backdrop-blur-[1px] p-2 rounded-2xl shadow-2xl" x-cloak>
            <div class="grid grid-cols-3 gap-3">
                <a href="{{ route('home') }}" class="p-2 text-sm font-medium {{ request()->routeIs('home') ? 'bg-orange-400/80 text-black font-bold' : 'text-white/80 hover:bg-green-400/10' }} rounded-xl transition" @click="mobileMenuOpen = false">Home</a>
                <a href="{{ route('events') }}" class="p-2 text-sm font-medium {{ request()->routeIs('events') ? 'bg-orange-400/80 text-black font-bold' : 'text-white/80 hover:bg-green-400/10' }} rounded-xl transition" @click="mobileMenuOpen = false">Events</a>
                <a href="{{ route('contact') }}" class="p-2 text-sm font-medium {{ request()->routeIs('contact') ? 'bg-orange-400/80 text-black font-bold' : 'text-white/80 hover:bg-green-400/10' }} rounded-xl transition" @click="mobileMenuOpen = false">Contact Us</a>
                <a href="{{ route('organizers') }}" class="p-2 text-sm font-medium {{ request()->routeIs('organizers') ? 'bg-orange-400/80 text-black font-bold' : 'text-white/80 hover:bg-green-400/10' }} rounded-xl transition" @click="mobileMenuOpen = false">Organizer</a>
                <a href="{{ route('trends') }}" class="p-2 text-sm font-medium {{ request()->routeIs('trends') ? 'bg-orange-400/80 text-black font-bold' : 'text-white/80 hover:bg-green-400/10' }} rounded-xl transition" @click="mobileMenuOpen = false">Trends</a>
                {{-- <a href="" class="p-3 text-lg font-medium text-orange-400/70 hover:bg-green-400/10 rounded-2xl transition" @click="mobileMenuOpen = false">Reels</a> --}}


                @guest
                <div class="border-t border-white/10 col-span-3 mt-2 pt-3 flex flex-col gap-2">
                    <div class="flex gap-4">
                        <a href="{{ route('show.signup') }}" class="flex-1 text-center p-2 text-black border border-green-400/20 font-medium font-mono bg-orange-400/70 rounded-3xl">Sign Up</a>
                        <a href="{{ route('show.login') }}" class="flex-1 text-center p-2 bg-black/90 border border-green-400/20 text-orange-400 font-semibold font-mono rounded-3xl">Log In</a>
                    </div>
                </div>
                @endguest
            </div>
        </div>
    </header>

    <main>

        {{ $slot }}

    </main>

    <footer class="p-2">

        <div class="md:p-15 p-5 pb-5 mt-10 bg-blend-darken bg-black/50 border border-purple-400/10 backdrop-blur-[1px]">
            <div class="grid grid-cols-5 mb-7">
                <div class="col-span-2 hidden md:block flex flex-col justify-between">
                    <div class="text-white">AKAVAAKO</div>
                    <p class=" text-sm text-orange-400/55 font-mono w-[90%]">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Id nihil perferendis consequatur ratione repudiandae esse expedita ipsa reprehenderit error impedit.</p>
                </div>
                <div class="col-span-5 md:col-span-3 grid grid-cols-4 md:flex justify-between gap-10">
                    <div class="col-span-2">
                        <h1 class="text-orange-400/70 font-mono font-medium mb-3">ABOUT US</h1>
                        <div class="flex flex-col gap-1">
                            <p class="text-white/60 text-sm font-semibold">Customer Supprot</p>
                            <p class="text-white/60 text-sm font-semibold">Terms and conditions</p>
                            <p class="text-white/60 text-sm font-semibold">Privacy and Cookie policy</p>
                            <p class="text-white/60 text-sm font-semibold">Payment policy</p>
                        </div>
                    </div>
                    <div class="col-span-2">
                        <h1 class="text-orange-400/70 font-mono font-medium mb-3">ORGANIZER</h1>
                        <div class="flex flex-col gap-1 ml-2">
                            <p class="text-white/60 text-sm font-semibold ">Create event</p>
                            <p class="text-white/60 text-sm font-semibold ">Find organizers</p>
                            <p class="text-white/60 text-sm font-semibold ">FAQs</p>
                            <p class="text-white/60 text-sm font-semibold ">Payment proccess</p>
                        </div>
                    </div>
                    <div class="col-span-2">
                        <h1 class="text-orange-400/70 font-mono font-medium mb-3">EVENTS</h1>
                        <div class="flex flex-col gap-1 ml-2">
                            <p class="text-white/60 font-semibold text-sm  ">Find events</p>
                            <p class="text-white/60 font-semibold text-sm  ">Organizers</p>
                            <p class="text-white/60 font-semibold text-sm  ">Events near you</p>
                            <p class="text-white/60 font-semibold text-sm  ">Tickets</p>
                        </div>
                    </div>
                    <div class="col-span-2">
                        <h1 class="text-orange-400/70 font-mono font-medium mb-3">COMPANY</h1>
                        <div class="flex flex-col gap-1 ml-2">
                            <p class="text-white/60 text-sm  font-semibold">Trends</p>
                            <p class="text-white/60 text-sm  font-semibold">Events</p>
                            <div class="mt-5 flex justify-between items-center gap-3 bg-orange-400/70 border border-green-400/10 p-0.5 rounded-3xl">
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
            </div>

            <div class="grid grid-cols-5 border-t border-white/20 pt-7">
                <div class="col-span-2 hidden md:block grid grid-cols-2 text-white">
                    <p class="col-span-2 flex items-end text-sm font-light mb-5 text-orange-400/55 font-mono w-[90%]">
                        Lorem ipsum, dolor sit amet consectetur adipisicing elit. Commodi culpa, totam deserunt nam dignissimos iure eius tempora quas facilis dicta.
                    </p>
                </div>
                <div class="col-span-5 md:col-span-3 grid grid-cols-4 gap-5">
                    <div class="col-span-2 flex flex-col justify-between gap-3">
                        <div class="flex flex-col gap-3 text-sm font-semibold text-white/80">
                            <p>@copyright 2025 </p>
                            <p>All rights reserved</p>
                            <p>AKAVAAKO</p>
                        </div>
                        <div class="">
                            <img src="{{ asset('img4.jpg') }}" class="h-34 w-full rounded-3xl object-cover" alt="">
                        </div>
                    </div>

                    <div class="col-span-2 p-5 bg-green-400/20 border border-green-400/20 backdrop-blur-[1px] rounded-4xl">
                        <div class="flex flex-col items-center gap-3 mb-5">
                            <h1 class="text-xl md:text-3xl text-center text-orange-400/60">Stay in touch</h1>
                            <p class="font-light text-center w-[80%] text-white/60 text-xs md:text-sm">Sign up to ur newsletter for daily info about Akavaako</p>
                        </div>
                        <form action="{{ route('newsletter.subscribe') }}" method="POST" class="flex flex-col gap-5 text-white/60 font-light text-sm">
                            @csrf

                            @if(session('newsletter_success'))
                            <div class="bg-green-500/20 border border-green-400/40 text-green-300 rounded-3xl p-2 text-xs text-center">
                                {{ session('newsletter_success') }}
                            </div>
                            @endif

                            @if(session('newsletter_error'))
                            <div class="bg-red-500/20 border border-red-400/40 text-red-300 rounded-3xl p-2 text-xs text-center">
                                {{ session('newsletter_error') }}
                            </div>
                            @endif

                            <input type="email" name="email" value="{{ old('email') }}" placeholder="Enter your email address" required class="p-3 rounded-3xl bg-[#b0a6df]/30 outline outline-[#b0a6df]/35 backdrop-blur-4xl placeholder:text-center @error('email') border border-red-400/50 @enderror" />
                            @error('email')
                            <span class="text-red-400 text-xs text-center">{{ $message }}</span>
                            @enderror

                            <button type="submit" class="p-3 w-full bg-black/80 border border-green-400/15 rounded-3xl text-white/70 text-sm font-light font-mono hover:bg-black/90 transition">SUBSCRIBE</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </footer>

    <div class="fixed w-fit right-5 bottom-5 flex gap-2 flex-col items-end z-50">
        <div class="p-2 bg-green-400/30 border border-green-400/20 rounded-xl text-orange-400 w-fit cursor-pointer" id="scroll-top">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-big-up-dash-icon">
                <path d="M9 13a1 1 0 0 0-1-1H5.061a1 1 0 0 1-.75-1.811l6.836-6.835a1.207 1.207 0 0 1 1.707 0l6.835 6.835a1 1 0 0 1-.75 1.811H16a1 1 0 0 0-1 1v2a1 1 0 0 1-1 1h-4a1 1 0 0 1-1-1z" />
                <path d="M9 20h6" />
            </svg>
        </div>
        <div class="flex items-center gap-1 border border-green-400/20 bg-green-400/30 p-1 rounded-2xl w-fit">
            @auth
            <a href="{{ route('user.profile', auth()->id()) }}" class="p-2 bg-black/80 border border-black/10 rounded-xl text-orange-400">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-user-icon lucide-circle-user">
                    <circle cx="12" cy="12" r="10" />
                    <circle cx="12" cy="10" r="3" />
                    <path d="M7 20.662V19a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v1.662" /></svg>
            </a>
            @else
            <div class="p-2 bg-black/80 border border-black/10 rounded-xl text-orange-400">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-user-icon lucide-circle-user">
                    <circle cx="12" cy="12" r="10" />
                    <circle cx="12" cy="10" r="3" />
                    <path d="M7 20.662V19a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v1.662" /></svg>
            </div>
            @endauth
            <div class="p-2 bg-black/80 border border-black/10 rounded-xl text-orange-400 cursor-pointer">
                <a href="{{ route('events.saved') }}" id="saved-events-link">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-bookmark-icon {{ auth()->check() && auth()->user()->savedEvents()->exists() ? 'text-red-500' : 'text-orange-400' }}">
                        <path d="m19 21-7-4-7 4V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16z" />
                    </svg>
                </a>
            </div>

            <a href="{{ route('user.dashboard.overview') }}" class="p-2 bg-black/80 border border-black/10 rounded-xl text-orange-400">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user-pen-icon lucide-user-pen">
                    <path d="M11.5 15H7a4 4 0 0 0-4 4v2" />
                    <path d="M21.378 16.626a1 1 0 0 0-3.004-3.004l-4.01 4.012a2 2 0 0 0-.506.854l-.837 2.87a.5.5 0 0 0 .62.62l2.87-.837a2 2 0 0 0 .854-.506z" />
                    <circle cx="10" cy="7" r="4" /></svg>
            </a>
        </div>
    </div>


    <!-- Load any scripts that must come before Alpine initialization -->
    @stack('before-alpine')

    {{-- <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script> --}}

    <!-- Load Alpine.js after the before-alpine stack -->
    {{-- <script defer src="https://unpkg.com/alpinejs@3/dist/cdn.min.js"></script> --}}

    <script>
        function reviewSlider(reviews = []) {
            return {
                currentIndex: 0
                , reviews: reviews || []
                , get currentReview() {
                    if (!this.reviews.length) {
                        return {
                            user_photo: '{{ asset("default.jpg") }}'
                            , body: 'No review available'
                            , rating: 0
                            , created_at: ''
                            , user_name: 'Anonymous'
                        };
                    }
                    return this.reviews[this.currentIndex];
                }
                , prevReview() {
                    if (!this.reviews.length) return;
                    this.currentIndex = (this.currentIndex - 1 + this.reviews.length) % this.reviews.length;
                }
                , nextReview() {
                    if (!this.reviews.length) return;
                    this.currentIndex = (this.currentIndex + 1) % this.reviews.length;
                }
            }
        }





        document.addEventListener('DOMContentLoaded', function() {
            const buttons = document.querySelectorAll('.trend-like-btn');

            buttons.forEach(function(button) {
                // Initialize heart color based on data attribute
                const isLiked = button.getAttribute('data-is-liked') === 'true';
                const heartIcon = button.querySelector('svg') || button.querySelector('i.fa-heart');
                if (isLiked && heartIcon) {
                    if (heartIcon.tagName === 'svg') {
                        heartIcon.classList.add("text-red-500");
                        heartIcon.classList.remove("text-white/70", "text-orange-400/80");
                    } else if (heartIcon.tagName === 'I') {
                        heartIcon.classList.add("text-red-500");
                        heartIcon.classList.remove("text-white/70", "text-orange-400/80");
                    }
                }

                button.addEventListener('click', function() {
                    const trendId = this.getAttribute('data-trend-id');
                    const heartIcon = button.querySelector('svg') || button.querySelector('i.fa-heart');

                    // Add zoom animation
                    this.classList.add('zoom-animation');
                    setTimeout(() => this.classList.remove('zoom-animation'), 300);

                    fetch("/trends/" + trendId + "/like", {
                            method: "POST"
                            , headers: {
                                "X-CSRF-TOKEN": "{{ csrf_token() }}"
                                , "Content-Type": "application/json"
                            }
                            , body: JSON.stringify({
                                trend_id: trendId
                            })
                        , })
                        .then(function(response) {
                            if (response.status === 401) {
                                alert('Please log in to like trends.');
                                return null;
                            }
                            if (!response.ok) throw new Error('Network response was not ok');
                            return response.json();
                        })
                        .then(function(data) {
                            if (!data) return;

                            // Update all counters for this trend
                            const counters = document.querySelectorAll('.trend-likes-count[data-trend-id="' + trendId + '"]');
                            counters.forEach(c => c.textContent = data.likes_count);

                            // Update all like buttons for this trend
                            const allButtons = document.querySelectorAll('.trend-like-btn[data-trend-id="' + trendId + '"]');
                            allButtons.forEach(btn => {
                                const icon = btn.querySelector('svg') || btn.querySelector('i.fa-heart');
                                if (!icon) return;

                                if (data.liked) {
                                    btn.setAttribute('data-is-liked', 'true');
                                    if (icon.tagName === 'svg' || icon.tagName === 'I') {
                                        icon.classList.add("text-red-500");
                                        icon.classList.remove("text-white/70", "text-orange-400/80");
                                    }
                                } else {
                                    btn.setAttribute('data-is-liked', 'false');
                                    if (icon.tagName === 'svg') {
                                        icon.classList.remove("text-red-500");
                                        icon.classList.add("text-white/70");
                                    } else if (icon.tagName === 'I') {
                                        icon.classList.remove("text-red-500");
                                        icon.classList.add("text-white/70");
                                    }
                                }
                            });
                        })
                        .catch(function(error) {
                            console.error("Error:", error);
                        });
                });
            });
        });


        document.addEventListener('DOMContentLoaded', () => {
            // Like button functionality (using delegation for AJAX support)
            document.addEventListener('click', function(e) {
                const btn = e.target.closest('.like-btn');
                if (!btn) return;

                e.preventDefault();

                // Add zoom animation
                btn.classList.add('zoom-animation');
                setTimeout(() => btn.classList.remove('zoom-animation'), 300);

                const eventId = btn.dataset.event;
                const heart = btn.querySelector('svg');
                const likesCount = document.querySelector(`#likes-count-${eventId}`);

                if (!eventId) {
                    console.error('Event ID not found');
                    return;
                }

                fetch(`/events/${eventId}/like`, {
                        method: 'POST'
                        , headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]') ?.getAttribute('content') || '{{ csrf_token() }}'
                            , 'Accept': 'application/json'
                            , 'Content-Type': 'application/json'
                        }
                    })
                    .then(res => {
                        if (res.status === 401) {
                            alert('Please log in to like events.');
                            return null;
                        }
                        if (!res.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return res.json();
                    })
                    .then(data => {
                        if (!data) return;

                        if (likesCount) {
                            likesCount.innerText = data.likes_count;
                        }

                        if (heart) {
                            if (data.status === 'liked') {
                                heart.classList.add('text-red-500');
                                heart.classList.remove('text-orange-400/80');
                            } else {
                                heart.classList.remove('text-red-500');
                                heart.classList.add('text-orange-400/80');
                            }
                        }
                    })
                    .catch(err => {
                        console.error('Error liking event:', err);
                    });
            });

            // Save button functionality (using delegation for AJAX support)
            document.addEventListener('click', function(e) {
                const btn = e.target.closest('.save-btn');
                if (!btn) return;

                e.preventDefault();

                // Add zoom animation
                btn.classList.add('zoom-animation');
                setTimeout(() => btn.classList.remove('zoom-animation'), 300);

                const eventId = btn.dataset.event;
                const icon = btn.querySelector('svg');
                const savedEventsLink = document.querySelector('#saved-events-link svg');

                if (!eventId) {
                    console.error('Event ID not found');
                    return;
                }

                fetch(`/events/${eventId}/save`, {
                        method: 'POST'
                        , headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]') ?.getAttribute('content') || '{{ csrf_token() }}'
                            , 'Accept': 'application/json'
                            , 'Content-Type': 'application/json'
                        }
                    })
                    .then(res => {
                        if (res.status === 401) {
                            alert('Please log in to save events.');
                            return null;
                        }
                        if (!res.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return res.json();
                    })
                    .then(data => {
                        if (!data) return;

                        if (icon) {
                            if (data.status === 'saved') {
                                icon.classList.add('text-red-500');
                                icon.classList.remove('text-orange-400/80');
                            } else {
                                icon.classList.remove('text-red-500');
                                icon.classList.add('text-orange-400/80');
                            }
                        }

                        // Update header bookmark color based on saved events
                        if (savedEventsLink && data.has_saved_events !== undefined) {
                            if (data.has_saved_events) {
                                savedEventsLink.classList.add('text-red-500');
                                savedEventsLink.classList.remove('text-orange-400');
                            } else {
                                savedEventsLink.classList.remove('text-red-500');
                                savedEventsLink.classList.add('text-orange-400');
                            }
                        }
                    })
                    .catch(err => {
                        console.error('Error saving event:', err);
                    });
            });

            // Scroll to top
            const scrollTopBtn = document.getElementById('scroll-top');
            if (scrollTopBtn) {
                scrollTopBtn.addEventListener('click', () => {
                    window.scrollTo({
                        top: 0
                        , behavior: 'smooth'
                    });
                });
            }
        });



        document.addEventListener('DOMContentLoaded', function() {
            const followBtn = document.getElementById('follow-btn');

            if (followBtn) {
                followBtn.addEventListener('click', function() {
                    let organizerId = this.dataset.organizer;

                    if (!organizerId) {
                        console.error('Organizer ID not found');
                        return;
                    }

                    fetch(`/organizer/${organizerId}/follow`, {
                            method: 'POST'
                            , headers: {
                                "Content-Type": "application/json"
                                , "X-CSRF-TOKEN": "{{ csrf_token() }}"
                            }
                        })
                        .then(response => {
                            if (response.status === 401) {
                                alert('Please log in to follow organizers.');
                                return null;
                            }
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (!data) return;

                            const text = document.getElementById('follow-text');
                            const icon = document.getElementById('follow-icon');

                            if (text) {
                                if (data.status === 'followed') {
                                    text.innerText = "Unfollow";
                                } else if (data.status === 'unfollowed') {
                                    text.innerText = "Follow Organizer";
                                }
                            }

                            if (icon) {
                                if (data.status === 'followed') {
                                    icon.innerHTML = '<i class="fa-solid fa-user-minus"></i>';
                                } else if (data.status === 'unfollowed') {
                                    icon.innerHTML = '<i class="fa-solid fa-user-plus"></i>';
                                }
                            }
                        })
                        .catch(err => {
                            console.error('Error following organizer:', err);
                        });
                });
            }
        });

    </script>


    <!-- Custom scripts that depend on Alpine -->
    @stack('scripts')


    {{-- @stack('carousel') --}}

</body>


</html>
