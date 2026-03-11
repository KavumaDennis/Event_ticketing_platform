<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>@yield('title', 'Admin Dashboard')</title>
    @vite('resources/css/app.css')
    <script src="https://kit.fontawesome.com/yourkit.js" crossorigin="anonymous"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <style>
        [x-cloak] { display: none !important; }
        .pw-toggle-wrap {
            position: relative;
        }
        .pw-toggle-btn {
            position: absolute;
            right: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 2.25rem;
            height: 2.25rem;
            border-radius: 999px;
            background: rgba(0, 0, 0, 0.6);
            color: rgba(251, 146, 60, 0.8);
            border: 1px solid rgba(34, 197, 94, 0.15);
            transition: background 200ms ease, color 200ms ease, transform 200ms ease;
        }
        .pw-toggle-btn:hover {
            background: rgba(0, 0, 0, 0.8);
            color: rgba(251, 146, 60, 1);
            transform: translateY(-50%) scale(1.05);
        }
        .pw-toggle-input {
            padding-right: 3rem !important;
        }
    </style>
</head>
<body x-data="{ sidebarOpen: false }" class="bg-black/85 bg-[url(/public/bg-img.png)] bg-cover bg-center bg-fixed bg-blend-multiply">

    {{-- Mobile Sidebar Overlay --}}
    <div x-show="sidebarOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black/60 backdrop-blur-sm z-40 lg:hidden"
         @click="sidebarOpen = false" x-cloak></div>

    {{-- Mobile Sidebar --}}
    <div x-show="sidebarOpen"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="-translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="-translate-x-full"
         class="fixed inset-y-0 left-0 w-64 bg-zinc-950/95 border-r border-white/10 z-50 lg:hidden p-6 overflow-y-auto text-zinc-100"
         x-cloak>
        <div class="flex justify-between items-center mb-8">
            <span class="text-orange-400 font-bold tracking-tighter">ADMIN PANEL</span>
            <button @click="sidebarOpen = false" class="text-white/60">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
            </button>
        </div>
        <div class="space-y-4">
            <a href="{{ route('admin.dashboard') }}" class="block p-3 rounded-xl {{ request()->routeIs('admin.dashboard') ? 'bg-orange-500 text-black font-bold' : 'text-zinc-400' }}">Overview</a>
            <a href="{{ route('admin.users') }}" class="block p-3 rounded-xl {{ request()->routeIs('admin.users') ? 'bg-orange-500 text-black font-bold' : 'text-zinc-400' }}">Users</a>
            <a href="{{ route('admin.referrals') }}" class="block p-3 rounded-xl {{ request()->routeIs('admin.referrals') ? 'bg-orange-500 text-black font-bold' : 'text-zinc-400' }}">Referrals</a>
            <a href="{{ route('admin.organizers') }}" class="block p-3 rounded-xl {{ request()->routeIs('admin.organizers') ? 'bg-orange-500 text-black font-bold' : 'text-zinc-400' }}">Organizers</a>
            <a href="{{ route('admin.events') }}" class="block p-3 rounded-xl {{ request()->routeIs('admin.events') ? 'bg-orange-500 text-black font-bold' : 'text-zinc-400' }}">Events</a>
            <a href="{{ route('admin.promos') }}" class="block p-3 rounded-xl {{ request()->routeIs('admin.promos') ? 'bg-orange-500 text-black font-bold' : 'text-zinc-400' }}">Promo Codes</a>
            <a href="{{ route('admin.finance') }}" class="block p-3 rounded-xl {{ request()->routeIs('admin.finance*') || request()->routeIs('admin.payouts') ? 'bg-orange-500 text-black font-bold' : 'text-zinc-400' }}">Finance</a>
            <a href="{{ route('admin.payouts') }}" class="block p-3 rounded-xl {{ request()->routeIs('admin.payouts') ? 'bg-orange-500 text-black font-bold' : 'text-zinc-400' }}">Payouts</a>
            <a href="{{ route('admin.faqs') }}" class="block p-3 rounded-xl {{ request()->routeIs('admin.faqs') ? 'bg-orange-500 text-black font-bold' : 'text-zinc-400' }}">FAQs</a>
            <a href="{{ route('admin.reviews') }}" class="block p-3 rounded-xl {{ request()->routeIs('admin.reviews') ? 'bg-orange-500 text-black font-bold' : 'text-zinc-400' }}">Reviews</a>
            <a href="{{ route('admin.trends') }}" class="block p-3 rounded-xl {{ request()->routeIs('admin.trends') ? 'bg-orange-500 text-black font-bold' : 'text-zinc-400' }}">Trends</a>
            <hr class="border-white/10 my-4">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="w-full text-left p-3 text-zinc-500 hover:text-white transition-colors">Logout</button>
            </form>
        </div>
    </div>

    <div class="h-screen flex flex-col lg:grid lg:grid-cols-12 gap-3 p-3 overflow-hidden">

        {{-- Mobile Top Bar --}}
        <div class="lg:hidden flex justify-between items-center p-4 bg-green-400/10 rounded-2xl mb-2">
            <button @click="sidebarOpen = true" class="text-orange-400">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="4" x2="20" y1="12" y2="12"/><line x1="4" x2="20" y1="6" y2="6"/><line x1="4" x2="20" y1="18" y2="18"/></svg>
            </button>
            <span class="text-white/70 font-bold tracking-tighter">AKAVAAKO ADMIN</span>
            <div class="w-8 h-8 rounded-full bg-purple-600 flex items-center justify-center text-white font-bold text-xs">
                {{ substr(auth()->user()->first_name, 0, 1) }}
            </div>
        </div>

        {{-- SIDEBAR --}}
        <aside class="w-full col-span-2 rounded-2xl h-fit hidden lg:flex flex-col gap-3 overflow-hidden">
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

                <a href="{{ route('admin.referrals') }}" class="flex items-center gap-2 text-sm px-4 py-3 rounded-xl {{ request()->routeIs('admin.referrals') ? 'bg-green-400/10 border border-green-400/5 text-orange-400/90' : 'text-zinc-400' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-share-2">
                        <circle cx="18" cy="5" r="3" />
                        <circle cx="6" cy="12" r="3" />
                        <circle cx="18" cy="19" r="3" />
                        <line x1="8.59" x2="15.42" y1="13.51" y2="17.49" />
                        <line x1="15.41" x2="8.59" y1="6.51" y2="10.49" /></svg>
                    <span class='font-medium flex items-center pl-2.5 relative after:content-[""] after:bg-zinc-400 after:absolute after:w-[3px] after:h-[10px] after:rounded-lg after:left-0'>Referrals</span>
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

                <a href="{{ route('admin.promos') }}" class="flex items-center gap-2 text-sm px-4 py-3 rounded-xl {{ request()->routeIs('admin.promos') ? 'bg-green-400/10 border border-green-400/5 text-orange-400/90' : 'text-zinc-400' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-tag">
                        <path d="M12 2H2v10l9.29 9.29c.94.94 2.48.94 3.42 0l6.58-6.58c.94-.94.94-2.48 0-3.42L12 2Z" />
                        <path d="M7 7h.01" /></svg>
                    <span class='font-medium flex items-center pl-2.5 relative after:content-[""] after:bg-zinc-400 after:absolute after:w-[3px] after:h-[10px] after:rounded-lg after:left-0'>Promo Codes</span>
                </a>

                <a href="{{ route('admin.finance') }}" class="flex items-center gap-2 text-sm px-4 py-3 rounded-xl {{ request()->routeIs('admin.finance*') ? 'bg-green-400/10 border border-green-400/5 text-orange-400/90' : 'text-zinc-400' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-dollar-sign">
                        <line x1="12" x2="12" y1="2" y2="22" />
                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" /></svg>
                    <span class='font-medium flex items-center pl-2.5 relative after:content-[""] after:bg-zinc-400 after:absolute after:w-[3px] after:h-[10px] after:rounded-lg after:left-0'>Finance</span>
                </a>

                <a href="{{ route('admin.payouts') }}" class="flex items-center gap-2 text-sm px-4 py-3 rounded-xl {{ request()->routeIs('admin.payouts') ? 'bg-green-400/10 border border-green-400/5 text-orange-400/90' : 'text-zinc-400' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-wallet">
                        <path d="M21 12V7H5a2 2 0 0 1 0-4h14v4" />
                        <path d="M3 5v14a2 2 0 0 0 2 2h16v-5" />
                        <path d="M18 12a2 2 0 0 0 0 4h4v-4Z" /></svg>
                    <span class='font-medium flex items-center pl-2.5 relative after:content-[""] after:bg-zinc-400 after:absolute after:w-[3px] after:h-[10px] after:rounded-lg after:left-0'>Payouts</span>
                </a>

                <a href="{{ route('admin.faqs') }}" class="flex items-center gap-2 text-sm px-4 py-3 rounded-xl {{ request()->routeIs('admin.faqs') ? 'bg-green-400/10 border border-green-400/5 text-orange-400/90' : 'text-zinc-400' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-message-square-question">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
                        <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3" />
                        <path d="M12 17h.01" /></svg>
                    <span class='font-medium flex items-center pl-2.5 relative after:content-[""] after:bg-zinc-400 after:absolute after:w-[3px] after:h-[10px] after:rounded-lg after:left-0'>FAQs</span>
                </a>

                <a href="{{ route('admin.reviews') }}" class="flex items-center gap-2 text-sm px-4 py-3 rounded-xl {{ request()->routeIs('admin.reviews') ? 'bg-green-400/10 border border-green-400/5 text-orange-400/90' : 'text-zinc-400' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-star">
                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" /></svg>
                    <span class='font-medium flex items-center pl-2.5 relative after:content-[""] after:bg-zinc-400 after:absolute after:w-[3px] after:h-[10px] after:rounded-lg after:left-0'>Reviews</span>
                </a>

                <a href="{{ route('admin.trends') }}" class="flex items-center gap-2 text-sm px-4 py-3 rounded-xl {{ request()->routeIs('admin.trends') ? 'bg-green-400/10 border border-green-400/5 text-orange-400/90' : 'text-zinc-400' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trending-up">
                        <polyline points="22 7 13.5 15.5 8.5 10.5 2 17" />
                        <polyline points="16 7 22 7 22 13" /></svg>
                    <span class='font-medium flex items-center pl-2.5 relative after:content-[""] after:bg-zinc-400 after:absolute after:w-[3px] after:h-[10px] after:rounded-lg after:left-0'>Trends</span>
                </a>
                <div class="p-4 pb-0 border-t border-zinc-800">
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

    <script>
        function initPasswordToggles(root = document) {
            const inputs = Array.from(root.querySelectorAll('input[type="password"]'));
            inputs.forEach((input) => {
                if (input.dataset.pwToggleAttached === 'true') return;
                input.dataset.pwToggleAttached = 'true';

                const parent = input.parentElement;
                if (!parent) return;

                parent.classList.add('pw-toggle-wrap');
                input.classList.add('pw-toggle-input');

                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'pw-toggle-btn';
                btn.setAttribute('aria-label', 'Show password');
                btn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2.062 12.348a1 1 0 0 1 0-.696A10.75 10.75 0 0 1 21.938 12a1 1 0 0 1 0 .696A10.75 10.75 0 0 1 2.062 12.348Z"/><circle cx="12" cy="12" r="3"/></svg>';

                btn.addEventListener('click', () => {
                    const isHidden = input.type === 'password';
                    input.type = isHidden ? 'text' : 'password';
                    btn.setAttribute('aria-label', isHidden ? 'Hide password' : 'Show password');
                    btn.innerHTML = isHidden
                        ? '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m2 2 20 20"/><path d="M10.73 5.08A10.72 10.72 0 0 1 21.94 11.65a1 1 0 0 1 0 .7 10.74 10.74 0 0 1-1.82 2.78"/><path d="M6.06 6.06A10.75 10.75 0 0 0 2.06 11.65a1 1 0 0 0 0 .7 10.74 10.74 0 0 0 1.82 2.78"/><path d="M9.5 9.5a3 3 0 0 1 4 4"/></svg>'
                        : '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2.062 12.348a1 1 0 0 1 0-.696A10.75 10.75 0 0 1 21.938 12a1 1 0 0 1 0 .696A10.75 10.75 0 0 1 2.062 12.348Z"/><circle cx="12" cy="12" r="3"/></svg>';
                });

                parent.appendChild(btn);
            });
        }
        document.addEventListener('DOMContentLoaded', () => initPasswordToggles());
    </script>
    @stack('scripts')
</body>
</html>
