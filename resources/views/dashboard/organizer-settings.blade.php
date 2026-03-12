@extends('layouts.dashboard')

@section('title', 'Manage Organizer')

@section('content')
<div class="max-w-7xl mx-auto ml-2" x-data="{ 
    activeTab: 'general',
    payoutMethod: '{{ $organizer->payout_bank_name || $organizer->payout_account_number ? 'bank' : 'momo' }}'
}">

    {{-- Header --}}
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-white mb-1">Organizer Settings</h1>
            <p class="text-white/60 text-sm">Manage your organizer profile, payouts, and page settings.</p>
        </div>
        <a href="{{ route('organizer.details', $organizer->id) }}" class="flex items-center gap-2 bg-green-400/10 hover:bg-green-400/20 text-green-400 px-4 py-2 rounded-xl transition-colors text-sm font-medium w-fit">
            <span>View Public Profile</span>
            <i class="fas fa-external-link-alt"></i>
        </a>
    </div>

    {{-- Tabs Navigation --}}
    <div class="flex flex-wrap gap-2 mb-8 border-b border-white/10 pb-1">
        <button @click="activeTab = 'general'" 
                :class="activeTab === 'general' ? 'border-orange-400 text-orange-400' : 'border-transparent text-white/60 hover:text-white'"
                class="px-4 py-2 text-sm font-medium border-b-2 transition-colors">
            General
        </button>
        <button @click="activeTab = 'ticket'" 
                :class="activeTab === 'ticket' ? 'border-orange-400 text-orange-400' : 'border-transparent text-white/60 hover:text-white'"
                class="px-4 py-2 text-sm font-medium border-b-2 transition-colors">
            Ticketing
        </button>
        <button @click="activeTab = 'payout'" 
                :class="activeTab === 'payout' ? 'border-orange-400 text-orange-400' : 'border-transparent text-white/60 hover:text-white'"
                class="px-4 py-2 text-sm font-medium border-b-2 transition-colors">
            Payouts
        </button>
        <button @click="activeTab = 'promotions'" 
                :class="activeTab === 'promotions' ? 'border-orange-400 text-orange-400' : 'border-transparent text-white/60 hover:text-white'"
                class="px-4 py-2 text-sm font-medium border-b-2 transition-colors">
            Promotions
        </button>
        <button @click="activeTab = 'access'" 
                :class="activeTab === 'access' ? 'border-orange-400 text-orange-400' : 'border-transparent text-white/60 hover:text-white'"
                class="px-4 py-2 text-sm font-medium border-b-2 transition-colors">
            Team
        </button>
        <button @click="activeTab = 'marketing'" 
                :class="activeTab === 'marketing' ? 'border-orange-400 text-orange-400' : 'border-transparent text-white/60 hover:text-white'"
                class="px-4 py-2 text-sm font-medium border-b-2 transition-colors">
            Marketing
        </button>
        <button @click="activeTab = 'subscription'" 
                :class="activeTab === 'subscription' ? 'border-orange-400 text-orange-400' : 'border-transparent text-white/60 hover:text-white'"
                class="px-4 py-2 text-sm font-medium border-b-2 transition-colors">
            Plan
        </button>
    </div>

    {{-- Alerts --}}
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-400/10 border border-green-400/20 text-green-400 rounded-2xl text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 p-4 bg-red-400/10 border border-red-400/20 text-red-400 rounded-2xl text-sm">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Content Area --}}
    <form action="{{ route('organizer.settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="">
            {{-- GENERAL SETTINGS --}}
            <div x-show="activeTab === 'general'" x-transition.opacity>
                <h2 class="text-lg font-bold text-white mb-1">General Profile</h2>
                <p class="text-white/40 text-sm mb-6">Manage your public business identity and social presence.</p>

                <div class="space-y-8">
                    {{-- Business Bio --}}
                    <div>
                        <h3 class="text-sm font-semibold text-orange-400/80 mb-2 flex items-center gap-2">
                             Business Description
                        </h3>
                        <p class="text-white/40 text-xs mb-4 leading-relaxed">Tell your attendees more about your company or brand. This will be displayed on your organizer profile.</p>
                        <textarea name="description" rows="4" 
                                  class="w-full p-3 rounded-xl bg-white/5 outline outline-white/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70 focus:border-orange-400"
                                  placeholder="Describe your business...">{{ old('description', $organizer->description) }}</textarea>
                    </div>

                    <div class="border-t border-white/5"></div>

                    {{-- Social Links --}}
                    <div>
                        <h3 class="text-sm font-semibold text-orange-400/80 mb-4 flex items-center gap-2">
                             Social Presence
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="flex flex-col gap-2">
                                <label class="text-white/60 text-sm font-medium ml-1 flex items-center gap-2">
                                    <i class="fab fa-facebook text-blue-500"></i> Facebook URL
                                </label>
                                <input type="url" name="facebook_url" value="{{ old('facebook_url', $organizer->facebook_url) }}" 
                                       placeholder="https://facebook.com/yourpage"
                                       class="w-full p-3 rounded-xl bg-white/5 outline outline-white/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70 focus:border-orange-400">
                            </div>
                            <div class="flex flex-col gap-2">
                                <label class="text-white/60 text-sm font-medium ml-1 flex items-center gap-2">
                                    <i class="fab fa-instagram text-pink-500"></i> Instagram URL
                                </label>
                                <input type="url" name="instagram_url" value="{{ old('instagram_url', $organizer->instagram_url) }}" 
                                       placeholder="https://instagram.com/yourprofile"
                                       class="w-full p-3 rounded-xl bg-white/5 outline outline-white/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70 focus:border-orange-400">
                            </div>
                            <div class="flex flex-col gap-2">
                                <label class="text-white/60 text-sm font-medium ml-1 flex items-center gap-2">
                                    <i class="fab fa-twitter text-sky-400"></i> Twitter (X) URL
                                </label>
                                <input type="url" name="twitter_url" value="{{ old('twitter_url', $organizer->twitter_url) }}" 
                                       placeholder="https://twitter.com/yourhandle"
                                       class="w-full p-3 rounded-xl bg-white/5 outline outline-white/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70 focus:border-orange-400">
                            </div>
                            <div class="flex flex-col gap-2">
                                <label class="text-white/60 text-sm font-medium ml-1 flex items-center gap-2">
                                    <i class="fab fa-linkedin text-blue-400"></i> LinkedIn URL
                                </label>
                                <input type="url" name="linkedin_url" value="{{ old('linkedin_url', $organizer->linkedin_url) }}" 
                                       placeholder="https://linkedin.com/company/yourbrand"
                                       class="w-full p-3 rounded-xl bg-white/5 outline outline-white/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70 focus:border-orange-400">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-white/5">
                    <button type="submit" class="px-4 py-2 rounded-lg flex items-center gap-2 bg-orange-400 text-black border border-orange-400 hover:bg-orange-500 transition-all text-[10px] font-bold uppercase">
                        Save Profile Changes
                    </button>
                </div>
            </div>
            
            {{-- TICKET SETTINGS --}}
            <div x-show="activeTab === 'ticket'" x-transition.opacity>
                <h2 class="text-lg font-bold text-white mb-1">Ticket Settings</h2>
                <p class="text-white/40 text-sm mb-6">Manage settings related to tickets for attendees.</p>

                <div class="space-y-8">
                    {{-- Communication details --}}
                    <div>
                        <h3 class="text-sm font-semibold text-orange-400/80 mb-2 flex items-center gap-2">
                             Communication details
                        </h3>
                        <p class="text-white/40 text-xs mb-4 leading-relaxed">Please provide contact details to be shared with the attendee. Attendees will be able to contact you in case they have any questions regarding your event.</p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="flex flex-col gap-2">
                                <label class="text-white/60 text-sm font-medium ml-1">Email</label>
                                <input type="email" name="contact_email" value="{{ old('contact_email', $organizer->contact_email ?? Auth::user()->email) }}" 
                                       class="w-full p-3 rounded-xl bg-white/5 outline outline-white/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70 focus:border-orange-400">
                            </div>
                            <div class="flex flex-col gap-2">
                                <label class="text-white/60 text-sm font-medium ml-1">Contact number</label>
                                <input type="text" name="contact_number" value="{{ old('contact_number', $organizer->contact_number) }}" 
                                       placeholder="{{ $organizer->contact_number ? '' : 'You have not added any contact number yet.' }}"
                                       class="w-full p-3 rounded-xl bg-white/5 outline outline-white/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70 focus:border-orange-400">
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-white/5"></div>

                    {{-- Ticket Defaults --}}
                    <div>
                        <h3 class="text-sm font-semibold text-orange-400/80 mb-4 flex items-center gap-2">
                             Ticket Defaults
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="flex flex-col gap-2">
                                <label class="text-white/60 text-sm font-medium ml-1">Default Price (UGX)</label>
                                <input type="number" name="default_ticket_price" value="{{ old('default_ticket_price', $organizer->default_ticket_price) }}" 
                                       class="w-full p-3 rounded-xl bg-white/5 outline outline-white/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70 focus:border-orange-400">
                            </div>
                            <div class="flex flex-col gap-2">
                                <label class="text-white/60 text-sm font-medium ml-1">Default Quantity</label>
                                <input type="number" name="default_ticket_quantity" value="{{ old('default_ticket_quantity', $organizer->default_ticket_quantity) }}" 
                                       class="w-full p-3 rounded-xl bg-white/5 outline outline-white/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70 focus:border-orange-400">
                            </div>
                            <div class="flex flex-col gap-2">
                                <label class="text-white/60 text-sm font-medium ml-1">Default Type</label>
                                <select name="default_ticket_type" class="w-full p-3 rounded-xl bg-white/5 outline outline-white/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70 focus:border-orange-400">
                                    <option value="Regular" {{ $organizer->default_ticket_type == 'Regular' ? 'selected' : '' }} class="bg-zinc-900">Regular</option>
                                    <option value="VIP" {{ $organizer->default_ticket_type == 'VIP' ? 'selected' : '' }} class="bg-zinc-900">VIP</option>
                                    <option value="VVIP" {{ $organizer->default_ticket_type == 'VVIP' ? 'selected' : '' }} class="bg-zinc-900">VVIP</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-white/5"></div>

                    {{-- Special Instructions --}}
                    <div>
                        <h3 class="text-sm font-semibold text-orange-400/80 mb-2 flex items-center gap-2">
                             Special Instructions 
                        </h3>
                        <p class="text-white/40 text-xs mb-4 leading-relaxed">Add any specific instructions (e.g. parking info, dress code, entry requirements) that should appear on every ticket issued.</p>
                        <textarea name="ticket_instructions" rows="3" 
                                  class="w-full p-3 rounded-xl bg-white/5 outline outline-white/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70 focus:border-orange-400"
                                  placeholder="e.g. Please arrive 30 minutes early...">{{ old('ticket_instructions', $organizer->ticket_instructions) }}</textarea>
                    </div>

                    <div class="border-t border-white/5"></div>

                    {{-- Branding --}}
                    <div>
                        <h3 class="text-sm font-semibold text-orange-400/80 mb-4 flex items-center gap-2">
                             Branding
                        </h3>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-4 bg-white/5 rounded-2xl border border-white/10">
                                <div>
                                    <h4 class="text-white text-sm font-medium">Logo in ticket</h4>
                                    <p class="text-white/40 text-xs">Highlight your brand in the tickets that are sent to attendees.</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="show_logo_in_ticket" value="1" {{ $organizer->show_logo_in_ticket ? 'checked' : '' }} class="sr-only peer">
                                    <div class="w-11 h-6 bg-zinc-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-orange-400"></div>
                                </label>
                            </div>

                            {{-- Elite/Pro Exclusive Branding --}}
                            @php $tier = strtolower($organizer->tier ?? 'free'); @endphp
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                {{-- Custom Logo Upload --}}
                                <div class="p-4 bg-white/5 rounded-2xl border border-white/10 {{ in_array($tier, ['pro', 'elite']) ? '' : 'opacity-40 grayscale pointer-events-none' }}">
                                    <div class="flex justify-between items-start mb-2">
                                        <h4 class="text-white text-xs font-bold uppercase">Custom Ticket Logo</h4>
                                        @if(!in_array($tier, ['pro', 'elite']))
                                            <span class="text-[8px] bg-orange-400 text-black px-2 py-0.5 rounded-full font-bold">PRO+</span>
                                        @endif
                                    </div>
                                    <input type="file" name="ticket_custom_logo" class="text-[10px] text-white/60">
                                    @if($organizer->ticket_custom_logo)
                                        <p class="text-[8px] text-green-400 mt-1">Existing logo uploaded</p>
                                    @endif
                                </div>

                                {{-- Custom Background Upload --}}
                                <div class="p-4 bg-white/5 rounded-2xl border border-white/10 {{ $tier === 'elite' ? '' : 'opacity-40 grayscale pointer-events-none' }}">
                                    <div class="flex justify-between items-start mb-2">
                                        <h4 class="text-white text-xs font-bold uppercase">Custom Ticket BG</h4>
                                        @if($tier !== 'elite')
                                            <span class="text-[8px] bg-green-400 text-black px-2 py-0.5 rounded-full font-bold">ELITE ONLY</span>
                                        @endif
                                    </div>
                                    <input type="file" name="ticket_custom_background" class="text-[10px] text-white/60">
                                    @if($organizer->ticket_custom_background)
                                        <p class="text-[8px] text-green-400 mt-1">Custom background active</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-white/5">
                    <button type="submit" class="px-6 py-2 bg-orange-400 text-black font-bold rounded-xl hover:bg-orange-500 transition shadow-lg shadow-orange-400/20">
                        Save Ticket Settings
                    </button>
                </div>
            </div>

            {{-- PAYOUT SETTINGS --}}
            <div x-show="activeTab === 'payout'" x-transition.opacity style="display: none;">
                <h2 class="text-lg font-bold text-white mb-4">Payout Destination</h2>
                <p class="text-white/40 text-sm mb-6">Choose how you want to receive your earnings from ticket sales.</p>
                
                <div class="space-y-8">
                    <div class="flex flex-wrap gap-3">
                        <button type="button" @click="payoutMethod = 'momo'"
                                :class="payoutMethod === 'momo' ? 'bg-orange-400 text-black' : 'bg-white/10 text-white/70 hover:bg-white/20'"
                                class="px-4 py-2 rounded-lg text-[10px] font-bold uppercase transition">
                            Mobile Money
                        </button>
                        <button type="button" @click="payoutMethod = 'bank'"
                                :class="payoutMethod === 'bank' ? 'bg-orange-400 text-black' : 'bg-white/10 text-white/70 hover:bg-white/20'"
                                class="px-4 py-2 rounded-lg text-[10px] font-bold uppercase transition">
                            Bank Account
                        </button>
                    </div>

                    {{-- Mobile Money Section --}}
                    <div x-show="payoutMethod === 'momo'" x-transition.opacity>
                        <h3 class="text-sm font-semibold text-orange-400/80 mb-4 flex items-center gap-2">
                            <i class="fas fa-mobile-alt"></i> Mobile Money
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="flex flex-col gap-2">
                                <label class="text-white/60 text-sm font-medium ml-1">MTN/Airtel Number</label>
                                <input type="text" name="payout_mobile_money_number" value="{{ old('payout_mobile_money_number', $organizer->payout_mobile_money_number) }}" 
                                       placeholder="e.g. 0770000000"
                                       class="w-full p-3 rounded-xl bg-white/5 outline outline-white/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70 focus:border-orange-400">
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-white/5"></div>

                    {{-- Bank Account Section --}}
                    <div x-show="payoutMethod === 'bank'" x-transition.opacity>
                        <h3 class="text-sm font-semibold text-orange-400/80 mb-4 flex items-center gap-2">
                            <i class="fas fa-university"></i> Bank Account
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="flex flex-col gap-2">
                                <label class="text-white/60 text-sm font-medium ml-1">Bank Name</label>
                                <input type="text" name="payout_bank_name" value="{{ old('payout_bank_name', $organizer->payout_bank_name) }}" 
                                       placeholder="e.g. Stanbic Bank"
                                       class="w-full p-3 rounded-xl bg-white/5 outline outline-white/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70 focus:border-orange-400">
                            </div>
                            <div class="flex flex-col gap-2">
                                <label class="text-white/60 text-sm font-medium ml-1">Account Number</label>
                                <input type="text" name="payout_account_number" value="{{ old('payout_account_number', $organizer->payout_account_number) }}" 
                                       class="w-full p-3 rounded-xl bg-white/5 outline outline-white/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70 focus:border-orange-400">
                            </div>
                            <div class="flex flex-col gap-2">
                                <label class="text-white/60 text-sm font-medium ml-1">Account Holder Name</label>
                                <input type="text" name="payout_account_name" value="{{ old('payout_account_name', $organizer->payout_account_name) }}" 
                                       class="w-full p-3 rounded-xl bg-white/5 outline outline-white/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70 focus:border-orange-400">
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-white/5"></div>

                    {{-- Payout Schedule & Tax --}}
                    <div>
                        <h3 class="text-sm font-semibold text-orange-400/80 mb-4 flex items-center gap-2">
                            <i class="fas fa-calendar-check"></i> Payout Frequency & Tax
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="flex flex-col gap-2">
                                <label class="text-white/60 text-sm font-medium ml-1">Payout Frequency</label>
                                <select name="payout_frequency" class="w-full p-3 rounded-xl bg-white/5 outline outline-white/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70 focus:border-orange-400">
                                    <option value="daily" {{ $organizer->payout_frequency == 'daily' ? 'selected' : '' }} class="bg-zinc-900">Daily</option>
                                    <option value="weekly" {{ $organizer->payout_frequency == 'weekly' ? 'selected' : '' }} class="bg-zinc-900">Weekly</option>
                                    <option value="monthly" {{ ($organizer->payout_frequency ?? 'monthly') == 'monthly' ? 'selected' : '' }} class="bg-zinc-900">Monthly (Default)</option>
                                </select>
                            </div>
                            <div class="flex flex-col gap-2">
                                <label class="text-white/60 text-sm font-medium ml-1">Tax ID / Tin Number (Optional)</label>
                                <input type="text" name="tax_id" value="{{ old('tax_id', $organizer->tax_id) }}" 
                                       placeholder="For tax compliance"
                                       class="w-full p-3 rounded-xl bg-white/5 outline outline-white/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70 focus:border-orange-400">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-white/5">
                    <button type="submit" class="px-6 py-2 bg-orange-400 text-black font-bold rounded-xl hover:bg-orange-500 transition shadow-lg shadow-orange-400/20">
                        Save Payout Settings
                    </button>
                </div>
            </div>

            {{-- PAGE ACCESS --}}
            <div x-show="activeTab === 'access'" x-transition.opacity style="display: none;">
                <h2 class="text-lg font-bold text-white mb-4">Team & Roles</h2>
                <p class="text-white/40 text-sm mb-6">Add team members and assign roles per organizer.</p>

                <div class="space-y-6">
                    {{-- Add Team Member --}}
                    <form action="{{ route('organizer.team.add') }}" method="POST" class="flex flex-col md:flex-row gap-2">
                        @csrf
                        <input type="email" name="email" placeholder="Enter user email address"
                               class="w-full p-3 rounded-xl bg-white/5 outline outline-white/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70 focus:border-orange-400">
                        <select name="role" class="p-3 rounded-2xl bg-white/5 border border-white/10 text-white focus:border-orange-400 outline-none transition-colors">
                            <option value="editor" class="bg-zinc-900">Editor</option>
                            <option value="finance" class="bg-zinc-900">Finance</option>
                            <option value="support" class="bg-zinc-900">Support</option>
                            <option value="owner" class="bg-zinc-900">Owner</option>
                        </select>
                        <button type="submit" class="px-6 py-3 bg-white/10 text-white text-sm font-bold rounded-2xl hover:bg-white/20 transition">
                            Add Member
                        </button>
                    </form>

                    {{-- Members List --}}
                    <div class="space-y-2">
                        @forelse($organizer->members as $member)
                            <div class="flex items-center justify-between p-3 bg-white/5 border border-white/10 rounded-2xl">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-orange-400/10 flex items-center justify-center text-orange-400 text-xs">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-white text-sm">{{ $member->user?->email ?? 'Unknown user' }}</span>
                                        <span class="text-white/40 text-[10px]">Role: {{ ucfirst($member->role) }}</span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <form action="{{ route('organizer.team.update', $member) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <select name="role" onchange="this.form.submit()" class="w-full p-3 rounded-xl bg-white/5 outline outline-white/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70 focus:border-orange-400">
                                            <option value="owner" {{ $member->role === 'owner' ? 'selected' : '' }} class="bg-zinc-900">Owner</option>
                                            <option value="editor" {{ $member->role === 'editor' ? 'selected' : '' }} class="bg-zinc-900">Editor</option>
                                            <option value="finance" {{ $member->role === 'finance' ? 'selected' : '' }} class="bg-zinc-900">Finance</option>
                                            <option value="support" {{ $member->role === 'support' ? 'selected' : '' }} class="bg-zinc-900">Support</option>
                                        </select>
                                    </form>
                                    <form action="{{ route('organizer.team.remove', $member) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:text-red-500 text-xs font-medium transition-colors p-2">
                                            Remove
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <p class="text-white/30 text-xs italic p-12 text-center border border-dashed border-white/10 rounded-2xl">
                                No team members yet.
                            </p>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- MARKETING SETTINGS --}}
            <div x-show="activeTab === 'marketing'" x-transition.opacity style="display: none;">
                <h2 class="text-lg font-bold text-white mb-4">Marketing & Tracking</h2>
                <p class="text-white/40 text-sm mb-6">Integrate tracking tools to monitor your event performance and sales conversions.</p>
                
                <div class="space-y-8">
                    <div>
                        <h3 class="text-sm font-semibold text-orange-400/80 mb-4 flex items-center gap-2">
                            <i class="fas fa-chart-line"></i> Analytics Integration
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="flex flex-col gap-2">
                                <label class="text-white/60 text-sm font-medium ml-1">Google Analytics ID</label>
                                <input type="text" name="google_analytics_id" value="{{ old('google_analytics_id', $organizer->google_analytics_id) }}" 
                                       placeholder="e.g. G-XXXXXXXXXX"
                                       class="w-full p-3 rounded-xl bg-white/5 outline outline-white/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70 focus:border-orange-400">
                                <p class="text-white/30 text-[10px] ml-1">Track page views and user behavior.</p>
                            </div>
                            <div class="flex flex-col gap-2">
                                <label class="text-white/60 text-sm font-medium ml-1">Facebook Pixel ID</label>
                                <input type="text" name="facebook_pixel_id" value="{{ old('facebook_pixel_id', $organizer->facebook_pixel_id) }}" 
                                       placeholder="e.g. 123456789012345"
                                       class="w-full p-3 rounded-xl bg-white/5 outline outline-white/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70 focus:border-orange-400">
                                <p class="text-white/30 text-[10px] ml-1">Optimize your ad spend and track conversions.</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-4 bg-orange-400/10 border border-orange-400/20 rounded-2xl">
                        <div class="flex gap-3">
                            <i class="fas fa-info-circle text-orange-400 mt-1"></i>
                            <div class="text-xs text-white/70 leading-relaxed">
                                <strong class="text-orange-400">Pro Tip:</strong> Using tracking IDs allows you to see which marketing channels are driving the most ticket sales in your Google/Facebook dashboards.
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-white/5">
                    <button type="submit" class="px-6 py-2 bg-orange-400 text-black font-bold rounded-xl hover:bg-orange-500 transition shadow-lg shadow-orange-400/20">
                        Save Marketing Settings
                    </button>
                </div>
            </div>
        </div>
    </form>

    {{-- PROMOTIONS --}}
    <div x-show="activeTab === 'promotions'" x-transition.opacity style="display: none;">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-lg font-bold text-white mb-1">Promotions & Discounts</h2>
                <p class="text-white/40 text-sm">Create and manage promo codes for your events.</p>
            </div>
            <a href="{{ route('organizer.analytics') }}" class="px-3 py-2 rounded-lg flex items-center gap-2 bg-orange-400 text-black border border-orange-400 hover:bg-orange-500 transition-all text-[10px] font-bold uppercase">
                View Analytics
            </a>
        </div>

        <div class="space-y-8">
            {{-- Create Promo Code --}}
            <div class="p-6 bg-green-400/10 border border-green-400/10">
                <h3 class="text-sm font-semibold text-orange-400/80 mb-4">Create New Promo Code</h3>
                <form action="{{ route('organizer.promo.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @csrf
                    <div>
                        <label class="text-white/60 text-xs font-medium ml-1">Code</label>
                        <input type="text" name="code" placeholder="e.g. SUMMER2024" required 
                               class="w-full p-2.5 rounded-xl bg-white/5 border border-white/10 text-white focus:border-orange-400 outline-none uppercase">
                    </div>
                    <div>
                        <label class="text-white/60 text-xs font-medium ml-1">Type</label>
                        <select name="discount_type" class="w-full p-2.5 rounded-xl bg-white/5 border border-white/10 text-white focus:border-orange-400 outline-none cursor-pointer">
                            <option value="percentage" class="bg-zinc-900">Percentage (%)</option>
                            <option value="fixed" class="bg-zinc-900">Fixed Amount (UGX)</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-white/60 text-xs font-medium ml-1">Value</label>
                        <input type="number" name="discount_amount" placeholder="e.g. 15" required min="0" 
                               class="w-full p-2.5 rounded-xl bg-white/5 border border-white/10 text-white focus:border-orange-400 outline-none">
                    </div>
                    <div>
                        <label class="text-white/60 text-xs font-medium ml-1">Usage Limit (Optional)</label>
                        <input type="number" name="usage_limit" placeholder="e.g. 100" min="1" 
                               class="w-full p-2.5 rounded-xl bg-white/5 border border-white/10 text-white focus:border-orange-400 outline-none">
                    </div>
                    <div>
                        <label class="text-white/60 text-xs font-medium ml-1">Expiry Date (Optional)</label>
                        <input type="date" name="expires_at" 
                               class="w-full p-2.5 rounded-xl bg-white/5 border border-white/10 text-white focus:border-orange-400 outline-none">
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="w-fit px-3 py-3 rounded-lg flex items-center gap-2 bg-orange-400 text-black border border-orange-400 hover:bg-orange-500 transition-all text-[10px] font-bold uppercase">
                            Create Promo
                        </button>
                    </div>
                </form>
            </div>

            {{-- Active Promos List --}}
            <div>
                <h3 class="text-sm font-semibold text-white/80 mb-4">Active Promotions</h3>
                <div class="space-y-3">
                    @forelse($organizer->promoCodes ?? [] as $promo)
                    <div class="flex items-center justify-between p-4 bg-white/5 border border-white/10 rounded-2xl" x-data="{ active: {{ $promo->status ? 'true' : 'false' }} }">
                        <div>
                            <div class="flex items-center gap-3">
                                <span class="text-white font-mono font-bold">{{ $promo->code }}</span>
                                <span class="px-2 py-0.5 bg-white/10 text-white/60 text-[10px] rounded uppercase">{{ $promo->discount_type }}</span>
                            </div>
                            <p class="text-white/40 text-xs mt-1">
                                {{ $promo->discount_type == 'percentage' ? $promo->discount_amount . '% off' : number_format($promo->discount_amount) . ' UGX off' }}
                                • Used: {{ $promo->used_count }} / {{ $promo->usage_limit ?? '∞' }}
                            </p>
                        </div>
                        <div class="flex items-center gap-4">
                            <span class="text-xs text-white/40">{{ $promo->expires_at ? 'Expires ' . $promo->expires_at->format('M d') : 'No expiry' }}</span>
                            <button @click="fetch('{{ route('organizer.promo.toggle', $promo->id) }}', {method: 'POST', headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}}).then(r => r.json()).then(d => active = d.status)" 
                                    :class="active ? 'bg-green-400/20 text-green-400' : 'bg-red-400/20 text-red-400'"
                                    class="px-3 py-1.5 rounded-lg text-xs font-bold uppercase transition-colors">
                                <span x-text="active ? 'Active' : 'Inactive'"></span>
                            </button>
                        </div>
                    </div>
                    @empty
                    <p class="text-white/30 text-xs italic text-center py-4">No promo codes created yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- SUBSCRIPTIONS --}}
        <div x-show="activeTab === 'subscription'" x-transition.opacity style="display: none;">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-lg font-bold text-white">Subscription Management</h2>
                <div class="flex items-center gap-2">
                    <span class="text-white/40 text-xs uppercase tracking-widest">Active Plan:</span>
                    <span class="px-4 py-1 bg-orange-400 text-black text-xs font-bold rounded-full uppercase">
                        {{ $organizer->tier ?? 'Free' }}
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- FREE PLAN --}}
                <div class="p-8 rounded-3xl {{ ($organizer->tier ?? 'free') === 'free' ? 'bg-orange-400/5 ring-1 ring-orange-400/10' : 'bg-white/5 opacity-60' }} flex flex-col">
                    <h3 class="text-xl font-bold text-white mb-2">Free Plan</h3>
                    <p class="text-white/40 text-xs mb-6">Perfect for starters and small events.</p>
                    <div class="text-3xl font-bold text-white mb-8 font-mono">UGX 0 <span class="text-xs text-white/40 font-normal">/month</span></div>
                    
                    <ul class="space-y-4 mb-auto">
                        <li class="flex items-center gap-3 text-sm text-white/60"><i class="fas fa-check text-green-400"></i> Standard Event Listing</li>
                        <li class="flex items-center gap-3 text-sm text-white/60"><i class="fas fa-check text-green-400"></i> Basic Sales Analytics</li>
                        <li class="flex items-center gap-3 text-sm text-white/60"><i class="fas fa-times text-red-400/50"></i> Custom Logo in Tickets</li>
                        <li class="flex items-center gap-3 text-sm text-white/60"><i class="fas fa-times text-red-400/50"></i> Priority Display</li>
                    </ul>
                    
                    @if(($organizer->tier ?? 'free') === 'free')
                        <button disabled class="w-full mt-8 py-3 bg-white/5 text-white/40 text-[10px] uppercase font-bold rounded-lg cursor-not-allowed text-sm">
                            Current Plan
                        </button>
                    @endif
                </div>

                {{-- PRO PLAN --}}
                <div class="p-8 rounded-3xl border border-orange-400/30 {{ ($organizer->tier ?? 'free') === 'pro' ? 'bg-orange-400/10 ring-2 ring-orange-400' : 'bg-orange-400/5' }} relative flex flex-col">
                    <div class="absolute -top-3 right-8 bg-orange-400 text-black text-[10px] font-bold px-4 py-1 rounded-lg uppercase tracking-wider">Most Popular</div>
                    <h3 class="text-xl font-bold text-white mb-2">Pro Plan</h3>
                    <p class="text-white/40 text-xs mb-6">For growing organizers who want more.</p>
                    <div class="text-3xl font-bold text-white mb-8 font-mono">UGX 50,000 <span class="text-xs text-white/40 font-normal">/month</span></div>
                    
                    <ul class="space-y-4 mb-auto">
                        <li class="flex items-center gap-3 text-sm text-white/80"><i class="fas fa-check text-green-400"></i> Priority Listing Display</li>
                        <li class="flex items-center gap-3 text-sm text-white/80"><i class="fas fa-check text-green-400"></i> Advanced Performance Data</li>
                        <li class="flex items-center gap-3 text-sm text-white/80"><i class="fas fa-check text-green-400"></i> Custom Logo in Tickets</li>
                        <li class="flex items-center gap-3 text-sm text-white/80"><i class="fas fa-check text-green-400"></i> Reduced Service Fees</li>
                    </ul>
                    
                    @if(($organizer->tier ?? 'free') === 'free')
                        <a href="{{ route('organizer.signup') }}" class="w-full mt-8 py-3 bg-orange-400 text-black uppercase text-[10px] font-bold rounded-lg hover:bg-orange-500 transition text-sm text-center">
                            Upgrade Now
                        </a>
                    @elseif(($organizer->tier ?? 'free') === 'pro')
                        <button disabled class="w-full mt-8 py-3 bg-white/5 text-white/40 text-[10px] uppercase font-bold rounded-lg cursor-not-allowed text-sm">
                            Current Plan
                        </button>
                    @endif
                </div>

                {{-- ELITE PLAN --}}
                <div class="p-8 rounded-3xl border border-white/10 {{ ($organizer->tier ?? 'free') === 'elite' ? 'bg-green-400/5 ring-1 ring-green-400/50' : 'bg-white/5 opacity-60' }} flex flex-col">
                    <h3 class="text-xl font-bold text-white mb-2">Elite Plan</h3>
                    <p class="text-white/40 text-xs mb-6">Full power for professional event firms.</p>
                    <div class="text-3xl font-bold text-white mb-8">UGX 150,000 <span class="text-xs text-white/40 font-normal">/month</span></div>
                    
                    <ul class="space-y-4 mb-auto">
                        <li class="flex items-center gap-3 text-sm text-white/80"><i class="fas fa-check text-green-400"></i> VIP Visibility Multiplier</li>
                        <li class="flex items-center gap-3 text-sm text-white/80"><i class="fas fa-check text-green-400"></i> Dedicated Support Rep</li>
                        <li class="flex items-center gap-3 text-sm text-white/80"><i class="fas fa-check text-green-400"></i> 0% Service Fee on Tickets</li>
                        <li class="flex items-center gap-3 text-sm text-white/80"><i class="fas fa-check text-green-400"></i> White-label Experience</li>
                    </ul>
                    
                    @if(($organizer->tier ?? 'free') !== 'elite')
                        <a href="{{ route('organizer.signup') }}" class="w-full mt-8 py-3 bg-white text-black text-[10px] uppercase font-bold rounded-lg hover:bg-white/90 transition text-sm text-center">
                            Go Elite
                        </a>
                    @else
                        <button disabled class="w-full mt-8 py-3 bg-white/5 text-white/40 text-[10px] uppercase font-bold rounded-lg cursor-not-allowed text-sm">
                            Current Plan
                        </button>
                    @endif
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
