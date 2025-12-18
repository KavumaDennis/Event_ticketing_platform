@extends('layouts.dashboard')

@section('title','Cards')

@section('content')
<h2 class="text-2xl mb-4">Dashboard Cards</h2>

<div class="grid grid-cols-4 gap-6 relative">
    <div class="flex flex-col gap-3 relative">
        <div class="col-span-1 p-4 bg-green-400/10 h-fit rounded-2xl">
            <h3 class="font-semibold">Your Profile</h3>
            <div class="mt-3 text-white/60">
                <p class="flex items-center gap-2">
                    <span class="relative text-orange-400/70 text-sm font-medium pr-3 after:absolute after:right-0 after:w-1 after:h-3 after:bg-orange-400/70 after:rounded-lg flex items-center">
                        Events
                    </span>
                    <span>
                        {{ $stats['events_count'] }}
                    </span>
                </p>
                <p class="flex items-center gap-2">
                    <span class="relative text-orange-400/70 text-sm font-medium pr-3 after:absolute after:right-0 after:w-1 after:h-3 after:bg-orange-400/70 after:rounded-lg flex items-center">
                        Trends
                    </span>
                    <span>
                        {{ $stats['trends_count'] }}</p>
                </span>
                <p class="flex items-center gap-2">
                    <span class="relative text-orange-400/70 text-sm font-medium pr-3 after:absolute after:right-0 after:w-1 after:h-3 after:bg-orange-400/70 after:rounded-lg flex items-center">
                        Following
                    </span>
                    <span>
                        {{ $stats['followers_count'] }}
                    </span>
                </p>
                <p class="flex items-center gap-2">
                    <span class="relative text-orange-400/70 text-sm font-medium pr-3 after:absolute after:right-0 after:w-1 after:h-3 after:bg-orange-400/70 after:rounded-lg flex items-center">
                        Saved
                    </span>
                    <span>
                        {{ $stats['saved_count'] }}
                    </span>
                </p>
            </div>
        </div>
        <a href="#" id="editProfileBtn" class="text-xs text-black/90 font-medium font-mono bg-orange-400/70 text-center rounded-3xl p-2 w-full">
            Edit profile
        </a>

        {{-- Modal Overlay --}}
        <div id="editProfileModal" class="fixed inset-0 bg-black/60 flex items-center justify-center z-50 hidden">
            <div class="bg-gray-700 rounded-4xl w-full max-w-lg p-6 relative">

                {{-- Close Button --}}
                <button id="closeModal" class="absolute top-3 right-3 text-white/60 hover:text-white text-xl">&times;</button>

                <h2 class="font-semibold text-orange-400/70 mb-4">Edit Profile</h2>

                <form action="{{ route('user.dashboard.updateProfile') }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-3">

                    @csrf
                    @method('PUT')

                    {{-- First Name --}}
                    <div class="grid grid-cols-2 gap-5">
                        <div class="flex flex-col gap-1 col-span-1">
                            <label class="text-white/60 text-sm">First Name</label>
                            <input type="text" name="first_name" value="{{ Auth::user()->first_name }}" class="p-2 rounded-xl bg-green-400/20 border border-green-400/20 text-white outline-none">
                            @error('first_name')
                            <p class="text-red-400 text-xs">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Last Name --}}
                        <div class="flex flex-col gap-1 col-span-1">
                            <label class="text-white/60 text-sm">Last Name</label>
                            <input type="text" name="last_name" value="{{ Auth::user()->last_name }}" class="p-2 rounded-xl bg-green-400/20 border border-green-400/20 text-white outline-none">
                            @error('last_name')
                            <p class="text-red-400 text-xs">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>


                    {{-- Email --}}
                    <div class="flex flex-col gap-1">
                        <label class="text-white/60 text-sm">Email</label>
                        <input type="email" name="email" value="{{ Auth::user()->email }}" class="p-2 rounded-xl bg-green-400/20 border border-green-400/20 text-white outline-none">
                        @error('email')
                        <p class="text-red-400 text-xs">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Username --}}
                    <div class="flex flex-col gap-1">
                        <label class="text-white/60 text-sm">Username</label>
                        <input type="text" name="username" value="{{ Auth::user()->username }}" class="p-2 rounded-xl bg-green-400/20 border border-green-400/20 text-white outline-none">
                        @error('username')
                        <p class="text-red-400 text-xs">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Bio --}}
                    <div class="flex flex-col gap-1">
                        <label class="text-white/60 text-sm">Bio</label>
                        <textarea name="bio" rows="3" class="p-2 rounded-xl bg-green-400/20 border border-green-400/20 text-white outline-none">{{ Auth::user()->bio }}</textarea>
                        @error('bio')
                        <p class="text-red-400 text-xs">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Profile Picture --}}
                    <div class="flex flex-col gap-1">
                        <label class="text-white/60 text-sm">Profile Picture</label>
                        <input type="file" name="profile_pic" class="p-2 rounded-xl bg-[#2a2a3d] text-white outline-none">

                        {{-- Current Photo Preview --}}
                        @if(Auth::user()->profile_pic)
                        <img src="{{ asset('storage/' . Auth::user()->profile_pic) }}" class="w-20 h-20 rounded-full mt-2 object-cover">
                        @endif

                        @error('profile_pic')
                        <p class="text-red-400 text-xs">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Save Button --}}
                    <button type="submit" class="mt-4 w-full bg-orange-400/70 text-black/90 font-mono font-medium p-2 rounded-3xl hover:bg-orange-500 transition">
                        Save Changes
                    </button>
                </form>
            </div>
        </div>



    </div>

    <div class="col-span-3 p-4 bg-green-400/10 rounded-4xl">
        <h3 class="font-semibold mb-3 text-orange-400/70">Saved Events</h3>
        <div class="grid grid-cols-3 gap-3">
            @foreach($latestEvents as $ev)
            <div class="w-full h-fit p-1  rounded-3xl bg-green-400/10 border border-green-400/20">
                <div class="w-full h-[150px] relative p-2">
                    <img src="{{ $ev->event_image ? asset('storage/'.$ev->event_image) : asset('img3.jpg') }}" onerror="this.onerror=null; this.src='{{ asset('img3.jpg') }}';" class="absolute mix-blend-lighten z-0 top-0 left-0 object-cover w-full h-full rounded-[20px]" alt="{{ $ev->event_name }}" />

                    <div class="flex flex-col gap-2 z-10">
                        <h1 class='text-orange-400/80 z-10 uppercase font-medium'>{{ $ev->event_name }}</h1>

                        <div class="absolute bottom-1 right-1 flex items-center gap-1 bg-orange-400/80 p-1 rounded-3xl w-fit">

                            <!-- LIKE BUTTON -->

                            <div class="flex items-center gap-[3px]">


                                <div class="p-2 bg-black/90 border border-black/10 rounded-[50%] text-orange-400 cursor-pointer save-btn" data-event="{{ $ev->id }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-bookmark-icon lucide-bookmark
        {{ Auth::check() && $ev->isSavedBy(Auth::user()) ? 'text-red-500' : 'text-orange-400/80' }}">
                                        <path d="m19 21-7-4-7 4V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16z" />
                                    </svg>
                                </div>



                                <a href="{{ route('event.show', $ev->id) }}" class="p-1 px-2 z-30 cursor-pointer text-sm bg-black/90 border border-black/10 rounded-2xl font-medium text-orange-400/80">
                                    More
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>


    </div>



    {{-- Modal Script --}}
    <script>
        const modal = document.getElementById('editProfileModal');
        const openBtn = document.getElementById('editProfileBtn');
        const closeBtn = document.getElementById('closeModal');

        openBtn.addEventListener('click', (e) => {
            e.preventDefault();
            modal.classList.remove('hidden');
        });

        closeBtn.addEventListener('click', () => {
            modal.classList.add('hidden');
        });

        window.addEventListener('click', (e) => {
            if (e.target === modal) modal.classList.add('hidden');
        });

    </script>
    @endsection
