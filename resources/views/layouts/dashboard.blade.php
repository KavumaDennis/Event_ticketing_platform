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
            <a href="{{ route('user.dashboard.overview') }}" class="block py-2">Overview</a>
            <a href="{{ route('user.dashboard.profile') }}" class="block py-2">Profile</a>
            <a href="{{ route('user.dashboard.events') }}" class="block py-2">Events</a>
            <a href="{{ route('user.dashboard.trends') }}" class="block py-2">Trends</a>

            <hr class="my-3 border-white/10" />

            <a href="{{ route('user.dashboard.tickets') }}" class="block py-2">My Tickets</a>
            <a href="{{ route('user.dashboard.orders') }}" class="block py-2">Orders & Payments</a>

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

                <a href="" class="flex items-center justify-center bg-orange-400/70 border border-green-400/10 size-9 text-black/90 rounded-full">
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
