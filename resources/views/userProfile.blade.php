<x-layout>
    <div class="p-5">




        <!-- Profile Header -->
        <div x-data="{ showFollowers: false, showFollowing: false }">
            <div class="grid grid-cols-4 gap-10">
                <div class="col-span-1 h-fit grid grid-cols-3 p-3 rounded-3xl border-green-400/20 bg-green-400/10">
                    <div class="col-span-2 flex flex-col gap-3 items-center justify-center">
                        <div class="border border-green-400/15  w-fit rounded-[50%] p-1 bg-orange-400/60">
                            <img src="{{ $user->profile_photo_url ?? asset('default.jpg') }}" alt="" class='size-18 rounded-[50%]' alt="" />
                        </div>
                        <div class="">
                            <p class="text-white/40 text-xs font-mono font-light text-center">User</p>
                            <h1 class="text-white/60 text-center ">{{ $user->first_name . ' ' . $user->last_name }}</h1>
                        </div>
                    </div>
                    <div class="">
                        <div class="flex flex-col py-1">
                            <div class=" text-white text-xl">
                                {{ $user->followers()->count() }}
                            </div>
                            <p class="cursor-pointer text-white/60 text-sm flex items-baseline gap-1" @click="showFollowers = true">
                                <span class="text-orange-400/60">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users-icon lucide-users">
                                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                                        <path d="M16 3.128a4 4 0 0 1 0 7.744" />
                                        <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
                                        <circle cx="9" cy="7" r="4" /></svg>
                                </span>
                                <span class="font-mono">Followers</span>
                            </p>
                        </div>
                        <div class="flex flex-col py-1">
                            <div class=" text-white text-xl">
                                {{ $user->following()->count() }}
                            </div>

                            <p class="cursor-pointer text-white/60 text-sm flex items-baseline gap-1" @click="showFollowing = true">
                                <span class="text-orange-400/60">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user-round-check-icon lucide-user-round-check">
                                        <path d="M2 21a8 8 0 0 1 13.292-6" />
                                        <circle cx="10" cy="8" r="5" />
                                        <path d="m16 19 2 2 4-4" /></svg>
                                </span>
                                <span class="font-mono">Following</span>
                            </p>


                        </div>
                        <div class="flex flex-col py-1">
                            <h1 class="text-white text-xl">{{ $user->trends()->count() }}</h1>
                            <p class="text-white/60 text-sm flex items-baseline gap-1">
                                <span class="text-orange-400/60">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chart-no-axes-column-decreasing-icon lucide-chart-no-axes-column-decreasing">
                                        <path d="M5 21V3" />
                                        <path d="M12 21V9" />
                                        <path d="M19 21v-6" /></svg>
                                </span>
                                <span class="font-mono">Trends</span>
                            </p>
                        </div>



                    </div>
                </div>
                <div class="col-span-3 flex flex-col gap-10 p-5">
                    <div class="flex flex-col gap-3">
                        <div class="flex items-center">
                            <span class="pr-3 relative after:content-[''] flex items-center text-sm font-medium text-orange-400/70 after:bg-orange-400/80 after:absolute  after:w-[3px] after:h-[12px] after:rounded-lg after:right-0">
                                Phone
                            </span>
                            <span class="pl-3 text-white/60 font-mono font-light">0759160763</span>
                        </div>
                        <div class="flex items-center">
                            <span class="pr-3 relative after:content-[''] flex items-center text-sm font-medium text-orange-400/70 after:bg-orange-400/80 after:absolute  after:w-[3px] after:h-[12px] after:rounded-lg after:right-0">
                                Email
                            </span>
                            <span class="pl-3 text-white/60 font-mono font-light">{{ $user->email }}</span>
                        </div>

                        <div class="flex">
                            <span class="pr-3 relative after:content-[''] flex items-center h-fit  text-sm font-medium text-orange-400/70 after:bg-orange-400/80 after:absolute  after:w-[3px] after:h-[12px] after:rounded-lg after:right-0">
                                About
                            </span>
                            <span class="pl-3 text-sm text-white/60 font-mono font-light">{{ $user->bio ? $user->bio : 'No bio yet' }}</span>
                        </div>
                    </div>

                    <div class="flex items-end gap-5 h-full">
                        <div>
                            @auth
                            @if(auth()->id() != $user->id)
                            <form action="{{ $user->followers()->where('follower_id', auth()->id())->exists() ? route('user.unfollow', $user) : route('user.follow', $user) }}" method="POST">
                                @csrf
                                @if($user->followers()->where('follower_id', auth()->id())->exists())
                                @method('DELETE')
                                <button class="p-0.5 rounded-3xl bg-orange-400/70 border border-green-400/15 flex items-center gap-1 text-sm font-semibold">
                                    <span class="size-8 flex items-center justify-center rounded-full text-orange-400/70 bg-black/95 border border-green-400/15">
                                        <i class="fa-solid fa-user-check"></i>
                                    </span>
                                    <span class="pr-1 text-sm">
                                        Unfollow
                                    </span>
                                </button>
                                @else
                                <button class="p-0.5 rounded-3xl bg-orange-400/70 border border-green-400/15 flex items-center gap-1 text-sm font-semibold">
                                    <span class="size-8 flex items-center justify-center rounded-full text-orange-400/70 bg-black/95 border border-green-400/15">
                                        <i class="fa-solid fa-user-plus"></i>
                                    </span>
                                    <span class="pr-1 text-sm">
                                        Follow
                                    </span>
                                </button>
                                @endif
                            </form>
                            @endif
                            @endauth


                        </div>

                        <div class="flex justify-between items-center gap-4 bg-orange-400/70 border border-green-400/10 p-0.5 rounded-3xl">
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




            <!-- Followers Modal -->
            <div x-show="showFollowers" x-cloak class="fixed inset-0 bg-black/70 flex items-center justify-center z-50">
                <div class="bg-gray-700 rounded-3xl p-5 w-96">
                    <h2 class="text-xl text-white/70 font-semibold mb-3">Followers</h2>
                    <ul class="space-y-2 max-h-96 overflow-y-auto ">
                        @foreach($followers as $f)
                        <li class="flex items-center justify-between gap-3 bg-green-400/10 border border-green-400/20 p-2 rounded-2xl">
                            <div class="flex items-center gap-3">
                                <img src="{{ $f->follower->profile_photo_url ?? asset('img2.jpg') }}" class="w-10 h-10 rounded-full" alt="{{ $f->follower->first_name }}">
                                <a href="{{ route('user.profile', $f->follower->id) }}" class="text-white/70 hover:underline">{{ $f->follower->first_name }} {{ $f->follower->last_name }}</a>
                            </div>
                            @if(auth()->check() && auth()->id() != $f->follower->id)
                            <form action="{{ $f->follower->followers()->where('follower_id', auth()->id())->exists() ? route('user.unfollow', $f->follower) : route('user.follow', $f->follower) }}" method="POST">
                                @csrf
                                @if($f->follower->followers()->where('follower_id', auth()->id())->exists())
                                @method('DELETE')
                                <button class="px-3 py-1 rounded-2xl bg-red-500/80 border border-red-400/60 text-white/90 text-xs">Unfollow</button>
                                @else
                                <button class="px-3 py-1 rounded-2xl bg-green-500/80 border border-green-400/50 text-white/90 text-xs">Follow</button>
                                @endif
                            </form>
                            @endif
                        </li>
                        @endforeach
                    </ul>
                    <button @click="showFollowers = false" class="mt-3 w-full bg-red-500 rounded-xl py-2 text-white/90 font-medium">Close</button>
                </div>
            </div>

            <!-- Following Modal -->
            <div x-show="showFollowing" x-cloak class="fixed inset-0 bg-black/70 flex items-center justify-center z-50">
                <div class="bg-gray-700 rounded-3xl p-5 w-96">
                    <h2 class="text-xl text-white/70 font-semibold mb-3">Following</h2>
                    <ul class="space-y-2 max-h-96 overflow-y-auto">
                        @foreach($following as $f)
                        @if($f->following) {{-- only display if followed user exists --}}
                        <li class="flex items-center justify-between gap-3 bg-green-400/10 border border-green-400/20 p-2 rounded-2xl">
                            <div class="flex items-center gap-3">
                                <img src="{{ $f->following->profile_photo_url ?? asset('img2.jpg') }}" class="w-10 h-10 rounded-full" alt="{{ $f->following->first_name }}">
                                <a href="{{ route('user.profile', $f->following->id) }}" class="text-white/70 hover:underline">{{ $f->following->first_name }} {{ $f->following->last_name }}</a>
                            </div>

                            @if(auth()->check() && auth()->id() != $f->following->id)
                            <form action="{{ $f->following->followers()->where('follower_id', auth()->id())->exists() ? route('user.unfollow', $f->following) : route('user.follow', $f->following) }}" method="POST">
                                @csrf
                                @if($f->following->followers()->where('follower_id', auth()->id())->exists())
                                @method('DELETE')
                                <button class="px-3 py-1 rounded-2xl bg-red-500/80 border border-red-400/60 text-white/90 text-xs">Unfollow</button>
                                @else
                                <button class="px-3 py-1 rounded-2xl bg-green-500/80 border border-green-400/50 text-white/90 text-xs">Follow</button>
                                @endif
                            </form>
                            @endif
                        </li>
                        @endif
                        @endforeach

                    </ul>
                    <button @click="showFollowing = false" class="mt-3 w-full bg-red-500 rounded-xl py-2 text-white/90 font-medium">Close</button>
                </div>
            </div>
        </div>

        <!-- Trends Grid -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
            @foreach($trends as $trend)
            <div class="col-span-1 h-full flex flex-col gap-2">
                <div class="overflow-hidden w-full h-[150px]">
                    <img src="{{ $trend->image ? asset('storage/'.$trend->image) : asset('img1.jpg') }}" class='h-full w-full rounded-3xl object-cover opacity-80' alt="{{ $trend->title }}" />
                </div>

                <div class="flex justify-between items-center">
                    <div class="text-white/70 flex items-center gap-2">
                        <button class="trend-like-btn size-8 flex justify-center items-center rounded-[50%] bg-green-400/10 border border-green-400/20" data-trend-id="{{ $trend->id }}" data-is-liked="{{ isset($trend->is_liked) && $trend->is_liked ? 'true' : 'false' }}">
                            {{-- <i class="fa-solid fa-heart {{ isset($trend->is_liked) && $trend->is_liked ? 'text-red-500' : 'text-white/70' }}"></i> --}}
                            <p class="{{ isset($trend->is_liked) && $trend->is_liked ? 'text-red-500' : 'text-white/70' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-heart-icon lucide-heart ">
                                    <path d="M2 9.5a5.5 5.5 0 0 1 9.591-3.676.56.56 0 0 0 .818 0A5.49 5.49 0 0 1 22 9.5c0 2.29-1.5 4-3 5.5l-5.492 5.313a2 2 0 0 1-3 .019L5 15c-1.5-1.5-3-3.2-3-5.5" />
                                </svg>
                            </p>
                        </button>
                        <span class="text-sm text-white/70 font-medium trend-likes-count" data-trend-id="{{ $trend->id }}">
                            {{ $trend->likes_count ?? 0 }} Likes
                        </span>
                    </div>

                    <a href="{{ route('trends.show', $trend->id) }}" class="w-fit bg-orange-400/70 border border-green-400/15 p-0.5 rounded-3xl flex items-center gap-1 cursor-pointer">
                        <p class="size-8 flex items-center justify-center rounded-[50%] text-orange-400/80 bg-black/95 border border-green-400/15">
                            <i class="fa-solid fa-ellipsis-vertical"></i>
                        </p>
                        <span class="text-xs font-mono font-medium mr-1">Read Post</span>
                    </a>
                </div>

                <div class="p-4 h-[150px] bg-green-400/10 rounded-3xl">
                    <h2 class="text-md font-semibold text-orange-400/70 mb-2">{{ $trend->title }}</h2>
                    <p class="text-sm font-light font-mono text-white/70 line-clamp-3">
                        {{ Str::limit($trend->body, 200) }}
                    </p>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $trends->links() }}
        </div>

    </div>



</x-layout>
