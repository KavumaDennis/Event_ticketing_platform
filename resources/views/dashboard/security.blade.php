@extends('layouts.dashboard')

@section('content')
<div class="px-6 pb-20">
    <div class="mb-10 text-center">
        <h1 class="text-2xl text-white/80 uppercase tracking-tighter">Security Settings</h1>
        <p class="text-zinc-500 text-sm">Protect your account and managed your sessions</p>
    </div>

    <div class="max-w-xl mx-auto">
        {{-- Change Password --}}
        <div class="bg-green-400/10 border border-green-400/10 p-10 h-full w-full flex flex-col justify-center items-center">
          
            
            <h2 class="text-2xl text-white/80 mb-8 flex items-center gap-3">
                <span class="size-10 rounded-xl bg-orange-400/10 flex items-center justify-center text-orange-400">
                    <i class="fas fa-lock text-sm"></i>
                </span>
                Change Password
            </h2>

            <form action="{{ route('user.dashboard.updatePassword') }}" class="w-full" method="POST">
                @csrf
                @method('PUT')

                <div class="space-y-6 w-full">
                    <div>
                        <label class="block text-zinc-500 text-xs font-bold uppercase mb-2 ml-1">Current Password</label>
                        <input type="password" name="current_password" required
                               autocomplete="current-password"
                               class="w-full p-3 rounded-3xl bg-[#b0a6df]/10 outline outline-[#b0a6df]/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70"
                               placeholder="••••••••">
                        @error('current_password') <p class="text-red-500 text-xs mt-1 ml-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-zinc-500 text-xs font-bold uppercase mb-2 ml-1">New Password</label>
                            <input type="password" name="password" required
                                   autocomplete="new-password"
                                   class="w-full p-3 rounded-l-3xl bg-[#b0a6df]/10 outline outline-[#b0a6df]/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70"
                                   placeholder="Min. 8 chars">
                        </div>
                        <div>
                            <label class="block text-zinc-500 text-xs font-bold uppercase mb-2 ml-1">Confirm New</label>
                            <input type="password" name="password_confirmation" required
                                   autocomplete="new-password"
                                   class="w-full p-3 rounded-r-3xl bg-[#b0a6df]/10 outline outline-[#b0a6df]/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70"
                                   placeholder="••••••••">
                        </div>
                    </div>
                </div>

                <div class="mt-10">
                    <button type="submit" 
                            class="w-full p-3 bg-black/80 text-white/80 font-medium font-mono text-sm border border-green-400/10 rounded-3xl">
                        Update Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
