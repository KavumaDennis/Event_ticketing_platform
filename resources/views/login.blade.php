<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    @vite('resources/css/app.css')
</head>
<body>
    <div class="w-full">
        <div class="grid grid-cols-5 w-full h-screen overflow-y-auto bg-black/85 bg-[url(/public/bg-img.png)] bg-cover bg-center bg-fixed  bg-blend-multiply border border-purple-400/10">
            <div class="col-span-2 w-full p-1 h-full flex flex-col justify-between">
                <div class="bg-green-400/10 border border-green-400/10 p-5 h-full w-full flex flex-col justify-center items-center">
                    <p class="text-white/70 mb-3">AKAVAAKO</p>
                    <div class="flex flex-col w-full justify-center items-center gap-5">
                        <div class="flex gap-2 items-center justify-center text-xs w-fit p-1 bg-orange-400/60 rounded-2xl">
                            <p class="font-medium text-black/80 ml-1">Welcome back</p>
                            <span class="bg-black rounded-xl p-1 px-2 text-orange-400/80 font-medium font-mono">user</span>
                        </div>

                        <p class="text-white/70 font-mono font-medium text-xs text-center w-[80%]">
                            Hello user, log in to get access to the best features Akavaako has to offer
                        </p>
                        @if(session('error'))
                        <div class="px-4 py-2 bg-red-500/20 border border-red-500 text-red-100 rounded-xl mb-4 text-sm">
                            {{ session('error') }}
                        </div>
                        @endif

                        @if(session('success'))
                        <div class="px-4 py-2 bg-green-500/20 border border-green-500 text-green-100 rounded-xl mb-4 text-sm">
                            {{ session('success') }}
                        </div>
                        @endif

                        @if($errors->any())
                        <ul class="px-4 py-2 bg-red-100 rounded-xl mb-4 w-full">
                            @foreach($errors->all() as $error)
                            <li class="my-2 text-red-500 text-sm italic list-disc ml-4">{{ $error }}</li>
                            @endforeach
                        </ul>
                        @endif
                        <form action="{{ route('login') }}" method="POST" class="flex flex-col w-full gap-7 text-white/30">
                            @csrf
                            <div class="flex flex-col w-full gap-2">
                                <label htmlFor="" class="text-white/60 font-medium ml-1 text-sm">Email</label>
                                <input type="email" name="email" placeholder="Enter your email" value="{{ old('email') }}" class="p-3 rounded-3xl bg-[#b0a6df]/10 outline outline-[#b0a6df]/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70" />
                            </div>
                            <div class="flex flex-col w-full gap-2">
                                <label htmlFor="" class="text-white/60 font-medium ml-1 text-sm">Password</label>
                                <input type="password" name="password" autocomplete="current-password" placeholder="Enter your password" class="p-3 rounded-3xl bg-[#b0a6df]/10 outline outline-[#b0a6df]/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70" />
                            </div>
                            <div class="flex justify-between items-center text-sm text-blue-400/60 font-light">
                                <div class="flex gap-2 items-center">
                                    <input type="checkbox" name="remember" id="remember">
                                    <label for="remember" class="text-white/80 font-medium cursor-pointer">Remember me</label>
                                </div>

                                <a href="{{ route('password.request') }}">
                                    <p class="pr-3 underline">Forgot Password</p>
                                </a>
                            </div>
                            <button class="w-full p-3 bg-black/80 text-white/80 font-medium font-mono text-sm border border-green-400/10 rounded-3xl">Log In</button>

                            <div class="flex justify-between">
                                <a href="{{ route('google.login') }}" class="p-1 w-full flex justify-center items-center bg-orange-400/60 border border-green-400/10 rounded-3xl gap-2 hover:bg-orange-400/80 transition-all">
                                    <span class="p-1 relative flex items-center pr-3 text-black/90 after:absolute after:right-0 after:h-3 after:w-1 after:rounded-lg after:bg-black/90">
                                        <i class="fa-brands fa-google"></i>
                                    </span>
                                    <span class="text-sm text-black/90 font-medium font-mono mr-2">Login with google</span>
                                </a>
                            </div>
                            <div class="flex flex-col gap-2 items-center w-full justify-center font-medium text-sm">
                                <span class="text-orange-400/70">
                                    Dont have an account?
                                </span>
                                <a href="{{ route('show.signup') }}" class="text-white/70 underline text-xs font-mono font-medium">Sign Up Here</a>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
            <div class="col-span-3 w-full h-full relative p-1">
                <div class="w-full h-full bg-[url(/public/img5.jpg)] bg-center bg-contain bg-blend-darken pt-5">
                    <div class="flex justify-between items-center px-5">
                        <div class="flex gap-5 p-3 rounded-3xl w-fit bg-[url(/public/bg-img.png)] bg-blend-darken bg-black/70 border border-purple-400/10 text-sm text-white/60 font-medium">
                            <a href="{{ route('home') }}">Home</a>
                            <a href="{{ route('events') }}">Events</a>
                            <a href="{{ route('contact') }}">Contact</a>
                            <a href="{{ route('organizers') }}">Organizer</a>
                            <a href="{{ route('trends') }}">Trends</a>
                        </div>

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
                    <div class="absolute bottom-5 left-5 flex flex-col gap-3">
                        <h1 class="uppercase text-xl font-semibold text-orange-400/60">Vumbula uganda</h1>
                        <div class="text-white font-light font-mono">Lorem ipsum, dolor sit amet consectetur adipisicing elit. Atque natus illo alias eligendi temporibus quaerat consequatur quam odit ex rerum.</div>

                        <div class="flex items-center p-1 w-fit bg-orange-400/60 gap-1 rounded-3xl">
                            <a class='flex gap-1 items-center'>
                                <span class='p-2 rounded-[50%] bg-black/95 text-orange-400/80'>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-ticket-icon lucide-ticket">
                                        <path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z" />
                                        <path d="M13 5v2" />
                                        <path d="M13 17v2" />
                                        <path d="M13 11v2" /></svg>
                                </span>
                                <span class='text-sm pr-2 font-semibold'>Get Tickets</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
