@extends('layouts.dashboard')

@section('title','Cards')

@section('content')
<div class="grid grid-cols-4 gap-6 relative">
    <div class="flex flex-col gap-3 relative">
        <div class="col-span-1 p-4 bg-green-400/10 h-fit rounded-2xl">
            <h3 class="font-semibold text-white/70">Your Profile</h3>
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
        <div id="editProfileModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm flex items-center justify-center z-50 hidden">
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

    <div class="col-span-3 p-4 bg-green-400/10 rounded-3xl">
        <h3 class="font-semibold mb-3 text-orange-400/70">Saved Events</h3>
        <div class="grid grid-cols-3 gap-3">
            @foreach($latestEvents as $ev)
            <div class="w-full h-fit p-1  rounded-3xl bg-green-400/10 border border-green-400/20">
                <div class="w-full h-[100px] relative p-2">
                    <img src="{{ $ev->event_image ? asset('storage/'.$ev->event_image) : asset('img3.jpg') }}" onerror="this.onerror=null; this.src='{{ asset('img3.jpg') }}';" class="absolute mix-blend-lighten z-0 top-0 left-0 object-cover w-full h-full rounded-[20px]" alt="{{ $ev->event_name }}" />

                    <div class="flex flex-col gap-2 z-10">
                        <h1 class='text-orange-400/80 z-10 uppercase font-medium'>{{ $ev->event_name }}</h1>

                         <div class="absolute bottom-1 right-1 flex items-center gap-1 bg-orange-400/80 rounded-3xl w-fit h-9 p-0.5">
                            <!-- LIKE BUTTON -->
                            <div class="flex items-center h-full gap-[3px]">
                                <button class="like-btn cursor-pointer h-full w-8 flex items-center justify-center bg-black/90 border border-black/10 rounded-[50%] font-medium" data-event="{{ $ev->id }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-heart-icon lucide-heart 
         {{ Auth::check() && $ev->isLikedBy(Auth::user()) ? 'text-red-500' : 'text-orange-400/80' }}">
                                        <path d="M2 9.5a5.5 5.5 0 0 1 9.591-3.676.56.56 0 0 0 .818 0A5.49 5.49 0 0 1 22 9.5c0 2.29-1.5 4-3 5.5l-5.492 5.313a2 2 0 0 1-3 .019L5 15c-1.5-1.5-3-3.2-3-5.5" />
                                    </svg>
                                </button>

                                <!-- Like count -->
                                <span id="likes-count-{{ $ev->id }}" class="text-black text-sm font-medium after:ml-0.5 relative after:w-1 after:bg-black/90 after:rounded-xl after:h-3 flex items-center">
                                    {{ $ev->likes->count() }}
                                </span>
                            </div>
                            <a href="{{ route('event.show', $ev->id) }}" class="h-full flex items-center justify-center px-3 z-30 cursor-pointer text-xs font-mono bg-black/90 border border-black/10 rounded-3xl font-medium text-orange-400/80">
                                More
                            </a>
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
