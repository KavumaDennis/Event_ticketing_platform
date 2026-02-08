<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>@yield('title', 'Dashboard')</title>
    @vite('resources/css/app.css')
    <script src="https://kit.fontawesome.com/yourkit.js" crossorigin="anonymous"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <style>
        [x-cloak] {
            display: none !important;
        }

    </style>
</head>
<body x-data="{ sidebarOpen: false }" class="bg-black/85 bg-[url(/public/bg-img.png)] bg-cover bg-center bg-fixed bg-blend-multiply">

    {{-- Mobile Sidebar Overlay --}}
    <div x-show="sidebarOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-40 lg:hidden" @click="sidebarOpen = false" x-cloak></div>

    {{-- Mobile Sidebar --}}
    <div x-show="sidebarOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full" class="fixed inset-y-0 left-0 w-64 bg-zinc-950/95 border-r border-white/10 z-50 lg:hidden p-6 overflow-y-auto" x-cloak>
        <div class="flex justify-between items-center mb-8">
            <span class="text-orange-400 font-bold tracking-tighter">DASHBOARD</span>
            <button @click="sidebarOpen = false" class="text-white/60">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" /></svg>
            </button>
        </div>
        <div class="space-y-4">
            <a href="{{ route('user.dashboard.overview') }}" class="flex items-center gap-3 p-3 rounded-xl {{ request()->routeIs('user.dashboard.overview') ? 'bg-orange-500 text-black font-bold' : 'text-zinc-400' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-layout-dashboard">
                    <rect width="7" height="9" x="3" y="3" rx="1" />
                    <rect width="7" height="5" x="14" y="3" rx="1" />
                    <rect width="7" height="9" x="14" y="12" rx="1" />
                    <rect width="7" height="5" x="3" y="16" rx="1" /></svg>
                Overview
            </a>
            <a href="{{ route('user.dashboard.trends') }}" class="flex items-center gap-3 p-3 rounded-xl {{ request()->routeIs('user.dashboard.trends') ? 'bg-orange-500 text-black font-bold' : 'text-zinc-400' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trending-up">
                    <polyline points="22 7 13.5 15.5 8.5 10.5 2 17" />
                    <underline points="16 7 22 7 22 13" /></svg>
                Trends
            </a>
            <a href="{{ route('user.dashboard.events') }}" class="flex items-center gap-3 p-3 rounded-xl {{ request()->routeIs('user.dashboard.events') ? 'bg-orange-500 text-black font-bold' : 'text-zinc-400' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar">
                    <rect width="18" height="18" x="3" y="4" rx="2" ry="2" />
                    <line x1="16" x2="16" y1="2" y2="6" />
                    <line x1="8" x2="8" y1="2" y2="6" />
                    <line x1="3" x2="21" y1="10" y2="10" /></svg>
                Events
            </a>
            <hr class="border-white/10 my-4">
            <a href="{{ route('user.dashboard.tickets') }}" class="flex items-center gap-3 p-3 rounded-xl {{ request()->routeIs('user.dashboard.tickets') ? 'bg-orange-500 text-black font-bold' : 'text-zinc-400' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-ticket">
                    <path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z" />
                    <path d="M13 5v2" />
                    <path d="M13 17v2" />
                    <path d="M13 11v2" /></svg>
                My Tickets
            </a>
            <a href="{{ route('user.dashboard.orders') }}" class="flex items-center gap-3 p-3 rounded-xl {{ request()->routeIs('user.dashboard.orders') ? 'bg-orange-500 text-black font-bold' : 'text-zinc-400' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shopping-bag">
                    <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z" />
                    <path d="M3 6h18" />
                    <path d="M16 10a4 4 0 0 1-8 0" /></svg>
                Orders
            </a>
            <a href="{{ route('user.dashboard.security') }}" class="flex items-center gap-3 p-3 rounded-xl {{ request()->routeIs('user.dashboard.security') ? 'bg-orange-500 text-black font-bold' : 'text-zinc-400' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-lock">
                    <rect width="18" height="11" x="3" y="11" rx="2" ry="2" />
                    <path d="M7 11V7a5 5 0 0 1 10 0v4" /></svg>
                Security
            </a>
            <a href="{{ route('user.dashboard.support') }}" class="flex items-center gap-3 p-3 rounded-xl {{ request()->routeIs('user.dashboard.support') ? 'bg-orange-500 text-black font-bold' : 'text-zinc-400' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-help-circle">
                    <circle cx="12" cy="12" r="10" />
                    <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3" />
                    <line x1="12" x2="12.01" y1="17" y2="17" /></svg>
                Support
            </a>
        </div>
    </div>

    <div class="h-screen flex flex-col lg:grid lg:grid-cols-12 gap-3 p-3 overflow-hidden">

        {{-- Mobile Top Bar --}}
        <div class="lg:hidden flex justify-between items-center p-4 bg-green-400/10 rounded-2xl mb-2">
            <button @click="sidebarOpen = true" class="text-orange-400">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="4" x2="20" y1="12" y2="12" />
                    <line x1="4" x2="20" y1="6" y2="6" />
                    <line x1="4" x2="20" y1="18" y2="18" /></svg>
            </button>
            <span class="text-white/70 font-bold tracking-tighter">AKAVAAKO</span>
            <a href="{{ route('user.profile', auth()->id()) }}" class="w-8 h-8 rounded-full bg-orange-400/70 flex items-center justify-center text-black font-bold text-xs hover:bg-orange-400 transition-colors">
                {{ substr(auth()->user()->first_name, 0, 1) }}
            </a>
        </div>

        <!-- FIXED SIDEBAR -->
        <nav class="col-span-2 bg-green-400/10 rounded-2xl p-4 h-fit hidden lg:block overflow-y-auto text-white/80">
            @if(auth()->user()->is_admin)
            <a href="{{ route('admin.dashboard') }}" class="block py-2 text-purple-400 font-bold mb-2">
                <i class="fas fa-shield-alt mr-2"></i> Admin Panel
            </a>
            @endif
            <div class="space-y-1">
                <a href="{{ route('user.dashboard.overview') }}" class="flex items-center gap-2 py-3 px-4 rounded-xl font-medium text-sm {{ request()->routeIs('user.dashboard.overview') ? 'bg-green-400/10 border border-green-400/5 text-orange-400/90' : 'text-zinc-400' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-layout-dashboard">
                        <rect width="7" height="9" x="3" y="3" rx="1" />
                        <rect width="7" height="5" x="14" y="3" rx="1" />
                        <rect width="7" height="9" x="14" y="12" rx="1" />
                        <rect width="7" height="5" x="3" y="16" rx="1" /></svg>
                    <span class='font-medium flex items-center pl-2.5 relative after:content-[""] after:bg-zinc-400 after:absolute after:w-[3px] after:h-[10px] after:rounded-lg after:left-0'>Overview</span>
                </a>
                <a href="{{ route('user.dashboard.trends') }}" class="flex items-center gap-2 py-3 px-4 rounded-xl font-medium text-sm {{ request()->routeIs('user.dashboard.trends') ? 'bg-green-400/10 border border-green-400/5 text-orange-400/90' : 'text-zinc-400' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trending-up">
                        <polyline points="22 7 13.5 15.5 8.5 10.5 2 17" />
                        <polyline points="16 7 22 7 22 13" /></svg>
                    <span class='font-medium flex items-center pl-2.5 relative after:content-[""] after:bg-zinc-400 after:absolute after:w-[3px] after:h-[10px] after:rounded-lg after:left-0'>Trends</span>
                </a>
                <a href="{{ route('user.dashboard.events') }}" class="flex items-center gap-2 py-3 px-4 rounded-xl font-medium text-sm {{ request()->routeIs('user.dashboard.events') ? 'bg-green-400/10 border border-green-400/5 text-orange-400/90' : 'text-zinc-400' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar">
                        <rect width="18" height="18" x="3" y="4" rx="2" ry="2" />
                        <line x1="16" x2="16" y1="2" y2="6" />
                        <line x1="8" x2="8" y1="2" y2="6" />
                        <line x1="3" x2="21" y1="10" y2="10" /></svg>
                    <span class='font-medium flex items-center pl-2.5 relative after:content-[""] after:bg-zinc-400 after:absolute after:w-[3px] after:h-[10px] after:rounded-lg after:left-0'>Events</span>
                </a>
                <a href="{{ route('user.dashboard.profile') }}" class="flex items-center gap-2 py-3 px-4 rounded-xl font-medium text-sm {{ request()->routeIs('user.dashboard.profile') ? 'bg-green-400/10 border border-green-400/5 text-orange-400/90' : 'text-zinc-400' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user-round-pen-icon lucide-user-round-pen">
                        <path d="M2 21a8 8 0 0 1 10.821-7.487" />
                        <path d="M21.378 16.626a1 1 0 0 0-3.004-3.004l-4.01 4.012a2 2 0 0 0-.506.854l-.837 2.87a.5.5 0 0 0 .62.62l2.87-.837a2 2 0 0 0 .854-.506z" />
                        <circle cx="10" cy="8" r="5" /></svg>
                    <span class='font-medium flex items-center pl-2.5 relative after:content-[""] after:bg-zinc-400 after:absolute after:w-[3px] after:h-[10px] after:rounded-lg after:left-0'>Profile</span>
                </a>
            </div>

            <hr class="my-3 border-white/10" />
            <a href="{{ route('user.dashboard.tickets') }}" class="flex items-center gap-2 py-3 px-4 rounded-xl font-medium text-sm {{ request()->routeIs('user.dashboard.tickets') ? 'bg-green-400/10 border border-green-400/5 text-orange-400/90' : 'text-zinc-400' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-ticket">
                    <path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z" />
                    <path d="M13 5v2" />
                    <path d="M13 17v2" />
                    <path d="M13 11v2" /></svg>
                <span class='font-medium flex items-center pl-2.5 relative after:content-[""] after:bg-zinc-400 after:absolute after:w-[3px] after:h-[10px] after:rounded-lg after:left-0'>My Tickets</span>
            </a>
            <a href="{{ route('user.dashboard.orders') }}" class="flex items-center gap-2 py-3 px-4 rounded-xl font-medium text-sm {{ request()->routeIs('user.dashboard.orders') ? 'bg-green-400/10 border border-green-400/5 text-orange-400/90' : 'text-zinc-400' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shopping-bag">
                    <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z" />
                    <path d="M3 6h18" />
                    <path d="M16 10a4 4 0 0 1-8 0" /></svg>
                <span class='font-medium flex items-center pl-2.5 relative after:content-[""] after:bg-zinc-400 after:absolute after:w-[3px] after:h-[10px] after:rounded-lg after:left-0'>Orders & Payments</span>
            </a>
            <a href="{{ route('user.dashboard.security') }}" class="flex items-center gap-2 py-3 px-4 rounded-xl font-medium text-sm {{ request()->routeIs('user.dashboard.security') ? 'bg-green-400/10 border border-green-400/5 text-orange-400/90' : 'text-zinc-400' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-lock">
                    <rect width="18" height="11" x="3" y="11" rx="2" ry="2" />
                    <path d="M7 11V7a5 5 0 0 1 10 0v4" /></svg>
                <span class='font-medium flex items-center pl-2.5 relative after:content-[""] after:bg-zinc-400 after:absolute after:w-[3px] after:h-[10px] after:rounded-lg after:left-0'>Security Settings</span>
            </a>

            <a href="{{ route('user.dashboard.support') }}" class="flex items-center gap-2 py-3 px-4 rounded-xl font-medium text-sm {{ request()->routeIs('user.dashboard.support') ? 'bg-green-400/10 border border-green-400/5 text-orange-400/90' : 'text-zinc-400' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-help-circle">
                    <circle cx="12" cy="12" r="10" />
                    <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3" />
                    <line x1="12" x2="12.01" y1="17" y2="17" /></svg>
                <span class='font-medium flex items-center pl-2.5 relative after:content-[""] after:bg-zinc-400 after:absolute after:w-[3px] after:h-[10px] after:rounded-lg after:left-0'>Help & Support</span>
            </a>

            <hr class="my-3 border-white/10" />
            <p class="text-sm text-white/60">Signed in as</p>
            <p class="font-medium font-mono text-orange-400/70">{{ auth()->user()->first_name ?? auth()->user()->name }} {{ auth()->user()->last_name }}</p>
        </nav>

        <!-- RIGHT SIDE: TOP BAR + SCROLLING MAIN -->
        <div class="col-span-12 lg:col-span-10 flex flex-col overflow-hidden">

            <!-- FIXED TOP BAR -->
            <div class="p-2 bg-green-400/10 rounded-2xl w-full mb-5 flex items-center justify-end gap-3 shrink-0">
                <div class="flex items-center gap-2 p-0.5 bg-orange-400/70 border border-green-400/10 rounded-3xl">
                    <div class="flex items-center justify-center bg-black/90 border border-green-400/10 size-8 text-orange-400/80 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 12h14" />
                            <path d="M12 5v14" />
                        </svg>
                    </div>
                    <span class="text-xs mr-1 text-black/90 font-bold font-mono">Create Event</span>
                </div>

                <a href="{{ route('home') }}" class="flex items-center justify-center bg-orange-400/70 border border-green-400/10 size-9 text-black/90 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-house-icon lucide-house">
                        <path d="M15 21v-8a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v8" />
                        <path d="M3 10a2 2 0 0 1 .709-1.528l7-6a2 2 0 0 1 2.582 0l7 6A2 2 0 0 1 21 10v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" /></svg>
                </a>


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

            <!-- SCROLLABLE MAIN CONTENT -->
            <main class="overflow-y-auto pr-2  h-full">
                @yield('content')
            </main>

        </div>

    </div>

    <script>
        window.csrfToken = "{{ csrf_token() }}";
        async function postJSON(url, body = {}) {
            const res = await fetch(url, {
                method: 'POST'
                , headers: {
                    'Content-Type': 'application/json'
                    , 'X-CSRF-TOKEN': window.csrfToken
                }
                , body: JSON.stringify(body)
            });
            return res.json();
        }

    </script>
    @stack('scripts')


</body>

</html>
