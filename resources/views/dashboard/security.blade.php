@extends('layouts.dashboard')

@section('content')
<div class="px-6 pb-20">
    <div class="mb-10 text-center">
        <h1 class="text-4xl font-black text-white uppercase tracking-tighter">Security Settings</h1>
        <p class="text-zinc-500 text-sm">Protect your account and managed your sessions</p>
    </div>

    <div class="max-w-2xl mx-auto">
        {{-- Change Password --}}
        <div class="bg-zinc-900 border border-zinc-800 rounded-[2.5rem] p-10 relative overflow-hidden group">
            <div class="absolute -right-20 -top-20 size-64 bg-orange-400/5 rounded-full blur-3xl group-hover:bg-orange-400/10 transition-all duration-700"></div>
            
            <h2 class="text-2xl font-bold text-white mb-8 flex items-center gap-3">
                <span class="size-10 rounded-xl bg-orange-400/10 flex items-center justify-center text-orange-400">
                    <i class="fas fa-lock text-sm"></i>
                </span>
                Change Password
            </h2>

            <form action="{{ route('user.dashboard.updatePassword') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <div>
                        <label class="block text-zinc-500 text-xs font-bold uppercase mb-2 ml-1">Current Password</label>
                        <input type="password" name="current_password" required
                               class="w-full bg-zinc-950 border border-zinc-800 rounded-2xl py-4 px-6 text-white text-sm focus:outline-none focus:border-orange-500/50 transition-all"
                               placeholder="••••••••">
                        @error('current_password') <p class="text-red-500 text-xs mt-1 ml-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-zinc-500 text-xs font-bold uppercase mb-2 ml-1">New Password</label>
                            <input type="password" name="password" required
                                   class="w-full bg-zinc-950 border border-zinc-800 rounded-2xl py-4 px-6 text-white text-sm focus:outline-none focus:border-green-500/50 transition-all"
                                   placeholder="Min. 8 chars">
                        </div>
                        <div>
                            <label class="block text-zinc-500 text-xs font-bold uppercase mb-2 ml-1">Confirm New</label>
                            <input type="password" name="password_confirmation" required
                                   class="w-full bg-zinc-950 border border-zinc-800 rounded-2xl py-4 px-6 text-white text-sm focus:outline-none focus:border-green-500/50 transition-all"
                                   placeholder="••••••••">
                        </div>
                    </div>
                </div>

                <div class="mt-10">
                    <button type="submit" 
                            class="w-full py-4 bg-orange-500 text-black font-black uppercase tracking-widest rounded-2xl hover:bg-orange-400 transition-all shadow-lg shadow-orange-500/20">
                        Update Password
                    </button>
                </div>
            </form>
        </div>

        {{-- 2FA - Placeholder for now --}}
        <div class="mt-6 bg-zinc-900/50 border border-zinc-800 rounded-[2.5rem] p-8 flex items-center justify-between opacity-50">
            <div class="flex items-center gap-5">
                <div class="size-12 rounded-2xl bg-zinc-800 flex items-center justify-center text-zinc-500">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <div>
                    <h3 class="text-white font-bold">Two-Factor Authentication</h3>
                    <p class="text-zinc-500 text-xs">Add an extra layer of security to your account.</p>
                </div>
            </div>
            <span class="px-4 py-1 bg-zinc-800 text-zinc-500 rounded-full text-[10px] font-bold">COMING SOON</span>
        </div>
    </div>
</div>
@endsection
