@extends('layouts.admin')

@section('title', 'Override Organizer Settings')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-green-400/10 overflow-hidden border border-green-400/10 shadow-2xl">
        <div class="p-8 border-b border-zinc-800 bg-zinc-900/40 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <img src="{{ $organizer->organizer_image ? asset('storage/'.$organizer->organizer_image) : asset('default.png') }}" class="w-16 h-16 rounded-2xl object-cover bg-zinc-800 shadow-xl border border-white/10">
                <div>
                    <h2 class="text-xl font-bold text-white tracking-tight">{{ $organizer->business_name }}</h2>
                    <p class="text-zinc-500 text-sm font-mono lowercase tracking-tighter">{{ $organizer->business_email }}</p>
                </div>
            </div>
            <a href="{{ route('admin.organizers.analytics', $organizer->id) }}" class="px-4 py-2 bg-purple-500/10 text-purple-400 border border-purple-500/20 rounded-xl text-xs font-bold uppercase tracking-widest hover:bg-purple-500/20 transition-all">View Full Analytics</a>
        </div>

        <form action="{{ route('admin.organizers.update', $organizer->id) }}" method="POST" class="p-8 space-y-8">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                {{-- Business Profile --}}
                <div class="space-y-4">
                    <h3 class="text-orange-400 text-xs font-bold uppercase tracking-[0.2em] mb-6">Core Business Profile</h3>
                    
                    <div>
                        <label class="block text-zinc-500 text-[10px] uppercase font-bold mb-2 ml-1">Business Name</label>
                        <input type="text" name="business_name" value="{{ $organizer->business_name }}" class="w-full p-3 rounded-xl bg-white/5 outline outline-white/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70">
                    </div>

                    <div>
                        <label class="block text-zinc-500 text-[10px] uppercase font-bold mb-2 ml-1">Business Email</label>
                        <input type="email" name="business_email" value="{{ $organizer->business_email }}" class="w-full p-3 rounded-xl bg-white/5 outline outline-white/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70">
                    </div>

                    <div>
                        <label class="block text-zinc-500 text-[10px] uppercase font-bold mb-2 ml-1">Contact Number</label>
                        <input type="text" name="contact_number" value="{{ $organizer->contact_number }}" class="w-full p-3 rounded-xl bg-white/5 outline outline-white/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70">
                    </div>
                </div>

                {{-- Account Oversight --}}
                <div class="space-y-4">
                    <h3 class="text-orange-400 text-xs font-bold uppercase tracking-[0.2em] mb-6">Platform Oversight</h3>

                    <div>
                        <label class="block text-zinc-500 text-[10px] uppercase font-bold mb-2 ml-1">Service Tier</label>
                        <select name="tier" class="w-full p-3 rounded-xl bg-white/5 outline outline-white/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70">
                            <option value="free" {{ $organizer->tier === 'free' ? 'selected' : '' }}>Standard (Free)</option>
                            <option value="pro" {{ $organizer->tier === 'pro' ? 'selected' : '' }}>Pro (UGX 250k/event)</option>
                            <option value="elite" {{ $organizer->tier === 'elite' ? 'selected' : '' }}>Elite (Subscription)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-zinc-500 text-[10px] uppercase font-bold mb-2 ml-1">Payout Frequency</label>
                        <select name="payout_frequency" class="w-full p-3 rounded-xl bg-white/5 outline outline-white/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70">
                            <option value="daily" {{ $organizer->payout_frequency === 'daily' ? 'selected' : '' }}>Daily Settlement</option>
                            <option value="weekly" {{ $organizer->payout_frequency === 'weekly' ? 'selected' : '' }}>Weekly Settlement</option>
                            <option value="monthly" {{ $organizer->payout_frequency === 'monthly' ? 'selected' : '' }}>Monthly Settlement</option>
                        </select>
                    </div>

                    <div class="flex items-center gap-3 pt-4">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_verified" value="1" class="sr-only peer" {{ $organizer->is_verified ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-zinc-800 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-orange-500"></div>
                            <span class="ml-3 text-xs font-bold text-zinc-400 uppercase tracking-widest">Verified Account</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="pt-8 border-t border-zinc-800 flex justify-between items-center">
                <p class="text-[10px] text-zinc-600 italic max-w-xs">Administrative overrides are logged. Ensure you have the necessary authorization before modifying account tiers or payout settings.</p>
                <div class="flex gap-4">
                    <a href="{{ route('admin.organizers') }}" class="px-6 py-2.5 text-zinc-500 hover:text-white transition-colors text-sm font-bold uppercase tracking-widest">Discard</a>
                    <button type="submit" class="px-10 py-2.5 bg-orange-400 text-black font-bold rounded-lg uppercase text-[10px] hover:bg-orange-400 transition-all shadow-lg shadow-orange-500/20 active:scale-95">Push Changes</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
