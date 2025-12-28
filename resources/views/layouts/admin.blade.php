<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>@yield('title', 'Admin Dashboard')</title>
    @vite('resources/css/app.css')
    <script src="https://kit.fontawesome.com/yourkit.js" crossorigin="anonymous"></script>
    {{-- Google Fonts if needed can go here --}}
</head>
<body class="bg-black/85 bg-[url(/public/bg-img.png)] bg-cover bg-center bg-fixed bg-blend-multiply">

    <div class="grid grid-cols-12 gap-3 p-2 text-zinc-100 h-screen overflow-hidden">
        {{-- SIDEBAR --}}
        <aside class="w-full col-span-2 rounded-2xl h-fit flex flex-col gap-3">
            <div class="p-3 text-center font-mono font-medium bg-green-400/10 rounded-xl">
                <span class="text-md text-orange-400/70 tracking-tighter">
                    ADMIN PANEL
                </span>
            </div>

            <nav class="flex-1 bg-green-400/10 rounded-2xl overflow-y-auto p-4 space-y-2">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 text-sm px-4 py-3 rounded-xl {{ request()->routeIs('admin.dashboard') ? 'bg-green-400/10 border border-green-400/5 text-orange-400/90' : 'text-zinc-400' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-layout-dashboard">
                        <rect width="7" height="9" x="3" y="3" rx="1" />
                        <rect width="7" height="5" x="14" y="3" rx="1" />
                        <rect width="7" height="9" x="14" y="12" rx="1" />
                        <rect width="7" height="5" x="3" y="16" rx="1" /></svg>
                    <span class='font-medium flex items-center pl-2.5 relative after:content-[""] after:bg-zinc-400 after:absolute after:w-[3px] after:h-[10px] after:rounded-lg after:left-0'>Overview</span>
                </a>

                <a href="{{ route('admin.users') }}" class="flex items-center gap-2 text-sm px-4 py-3 rounded-xl {{ request()->routeIs('admin.users') ? 'bg-green-400/10 border border-green-400/5 text-orange-400/90' : 'text-zinc-400' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                        <circle cx="9" cy="7" r="4" />
                        <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
                        <path d="M16 3.13a4 4 0 0 1 0 7.75" /></svg>
                    <span class='font-medium flex items-center pl-2.5 relative after:content-[""] after:bg-zinc-400 after:absolute after:w-[3px] after:h-[10px] after:rounded-lg after:left-0'>Users</span>
                </a>

                <a href="{{ route('admin.organizers') }}" class="flex items-center gap-2 text-sm px-4 py-3 rounded-xl {{ request()->routeIs('admin.organizers') ? 'bg-green-400/10 border border-green-400/5 text-orange-400/90' : 'text-zinc-400' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-briefcase">
                        <rect width="20" height="14" x="2" y="7" rx="2" ry="2" />
                        <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16" /></svg>
                    <span class='font-medium flex items-center pl-2.5 relative after:content-[""] after:bg-zinc-400 after:absolute after:w-[3px] after:h-[10px] after:rounded-lg after:left-0'>Organizers</span>
                </a>

                <a href="{{ route('admin.events') }}" class="flex items-center gap-2 text-sm px-4 py-3 rounded-xl {{ request()->routeIs('admin.events') ? 'bg-green-400/10 border border-green-400/5 text-orange-400/90' : 'text-zinc-400' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-ticket">
                        <path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z" />
                        <path d="M13 5v2" />
                        <path d="M13 17v2" />
                        <path d="M13 11v2" /></svg>
                    <span class='font-medium flex items-center pl-2.5 relative after:content-[""] after:bg-zinc-400 after:absolute after:w-[3px] after:h-[10px] after:rounded-lg after:left-0'>Events</span>
                </a>

                <a href="{{ route('admin.finance') }}" class="flex items-center gap-2 text-sm px-4 py-3 rounded-xl {{ request()->routeIs('admin.finance') ? 'bg-green-400/10 border border-green-400/5 text-orange-400/90' : 'text-zinc-400' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-dollar-sign">
                        <line x1="12" x2="12" y1="2" y2="22" />
                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" /></svg>
                    <span class='font-medium flex items-center pl-2.5 relative after:content-[""] after:bg-zinc-400 after:absolute after:w-[3px] after:h-[10px] after:rounded-lg after:left-0'>Finance</span>
                </a>

                <a href="{{ route('admin.faqs') }}" class="flex items-center gap-2 text-sm px-4 py-3 rounded-xl {{ request()->routeIs('admin.faqs') ? 'bg-green-400/10 border border-green-400/5 text-orange-400/90' : 'text-zinc-400' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-message-square-question">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
                        <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3" />
                        <path d="M12 17h.01" /></svg>
                    <span class='font-medium flex items-center pl-2.5 relative after:content-[""] after:bg-zinc-400 after:absolute after:w-[3px] after:h-[10px] after:rounded-lg after:left-0'>FAQs</span>
                </a>
                <div class="p-4 border-t border-zinc-800">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="flex items-center gap-2 text-zinc-500 hover:text-white transition-colors w-full">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-log-out">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                                <polyline points="16 17 21 12 16 7" />
                                <line x1="21" x2="9" y1="12" y2="12" /></svg>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </nav>
        </aside>

        {{-- MAIN CONTENT --}}
        <main class="flex-1 col-span-10 overflow-y-auto p-3">
            <header class="flex justify-between items-center mb-5">
                <h1 class="text-xl font-medium text-white/80">@yield('title')</h1>
                <div class="flex items-center gap-3">
                    <span class="text-sm font-mono font-medium text-zinc-400">Welcome, {{ auth()->user()->first_name }}</span>
                    <div class="w-10 h-10 rounded-full bg-purple-600 flex items-center justify-center text-white font-bold">
                        {{ substr(auth()->user()->first_name, 0, 1) }}
                    </div>
                </div>
            </header>

            @if(session('success'))
            <div class="p-4 mb-6 bg-green-500/10 border border-green-500/20 text-green-400 rounded-xl flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 6 9 17l-5-5" /></svg>
                {{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div class="p-4 mb-6 bg-red-500/10 border border-red-500/20 text-red-400 rounded-xl flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10" />
                    <line x1="12" x2="12" y1="8" y2="12" />
                    <line x1="12" x2="12.01" y1="16" y2="16" /></svg>
                {{ session('error') }}
            </div>
            @endif

            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>
