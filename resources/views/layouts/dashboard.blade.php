<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>@yield('title', 'Dashboard')</title>
    @vite('resources/css/app.css')
    <script src="https://kit.fontawesome.com/yourkit.js" crossorigin="anonymous"></script>
</head>
<body class="bg-black/85 bg-[url(/public/bg-img.png)] bg-cover bg-center bg-fixed bg-blend-multiply">

    <div class="h-screen grid grid-cols-12 gap-3 p-3 overflow-hidden">

        <!-- FIXED SIDEBAR -->
        <nav class="col-span-2 bg-green-400/10 rounded-2xl p-4 h-fit hidden lg:block overflow-y-auto text-white/80">
            @if(auth()->user()->is_admin)
            <a href="{{ route('admin.dashboard') }}" class="block py-2 text-purple-400 font-bold mb-2">
                <i class="fas fa-shield-alt mr-2"></i> Admin Panel
            </a>
            @endif
            <div class="space-y-1 mb-8">
                <a href="{{ route('user.dashboard.overview') }}" class="block py-3 px-4 rounded-xl font-medium text-sm {{ request()->routeIs('user.dashboard.overview') ? 'bg-green-400/10 border border-green-400/5 text-orange-400/90' : 'text-zinc-400' }}">Overview</a>
                <a href="{{ route('user.dashboard.trends') }}" class="block py-3 px-4 rounded-xl font-medium text-sm {{ request()->routeIs('user.dashboard.trends') ? 'bg-green-400/10 border border-green-400/5 text-orange-400/90' : 'text-zinc-400' }}">Trends</a>
                {{-- <a href="{{ route('user.dashboard.following') }}" class="block py-3 px-4 rounded-xl font-medium text-sm {{ request()->routeIs('user.dashboard.following') ? 'bg-green-400/10 border border-green-400/5 text-orange-400/90' : 'text-zinc-400' }}">Following</a> --}}
                {{-- <a href="{{ route('user.dashboard.followers') }}" class="block py-3 px-4 rounded-xl font-medium text-sm {{ request()->routeIs('user.dashboard.followers') ? 'bg-green-400/10 border border-green-400/5 text-orange-400/90' : 'text-zinc-400' }}">Followers</a> --}}
                <a href="{{ route('user.dashboard.reviews') }}" class="block py-3 px-4 rounded-xl font-medium text-sm {{ request()->routeIs('user.dashboard.reviews') ? 'bg-green-400/10 border border-green-400/5 text-orange-400/90' : 'text-zinc-400' }}">My Reviews</a>
                <a href="{{ route('user.dashboard.events') }}" class="block py-3 px-4 rounded-xl font-medium text-sm {{ request()->routeIs('user.dashboard.events') ? 'bg-green-400/10 border border-green-400/5 text-orange-400/90' : 'text-zinc-400' }}">Events</a>
            </div>

            <hr class="my-3 border-white/10" />
            <a href="{{ route('user.dashboard.tickets') }}" class="block py-3 px-4 rounded-xl font-medium text-sm {{ request()->routeIs('user.dashboard.tickets') ? 'bg-green-400/10 border border-green-400/5 text-orange-400/90' : 'text-zinc-400' }}">My Tickets</a>
            <a href="{{ route('user.dashboard.orders') }}" class="block py-3 px-4 rounded-xl font-medium text-sm {{ request()->routeIs('user.dashboard.orders') ? 'bg-green-400/10 border border-green-400/5 text-orange-400/90' : 'text-zinc-400' }}">Orders & Payments</a>
            <a href="{{ route('user.dashboard.security') }}" class="block py-3 px-4 rounded-xl font-medium text-sm {{ request()->routeIs('user.dashboard.security') ? 'bg-green-400/10 border border-green-400/5 text-orange-400/90' : 'text-zinc-400' }}">Security Settings</a>

            <a href="{{ route('user.dashboard.support') }}" class="block py-3 px-4 rounded-xl font-medium text-sm {{ request()->routeIs('user.dashboard.support') ? 'bg-green-400/10 border border-green-400/5 text-orange-400/90' : 'text-zinc-400' }}">Help & Support</a>

            <hr class="my-3 border-white/10" />
            <p class="text-sm text-white/60">Signed in as</p>
            <p class="font-semibold text-orange-400/70">{{ auth()->user()->first_name ?? auth()->user()->name }} {{ auth()->user()->last_name }}</p>
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

                <a href="{{ route('user.dashboard.notifications') }}" class="flex items-center justify-center bg-orange-400/70 border border-green-400/10 size-9 text-black/90 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-bell-icon lucide-bell">
                        <path d="M10.268 21a2 2 0 0 0 3.464 0" />
                        <path d="M3.262 15.326A1 1 0 0 0 4 17h16a1 1 0 0 0 .74-1.673C19.41 13.956 18 12.499 18 8A6 6 0 0 0 6 8c0 4.499-1.411 5.956-2.738 7.326" /></svg>
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
