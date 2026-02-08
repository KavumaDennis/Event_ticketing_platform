@extends('layouts.dashboard')

@section('content')
<div class="px-6 pb-20">
    <div class="flex justify-between items-end mb-12">
        <div>
            <h1 class="text-2xl font-black text-white uppercase tracking-tighter leading-none">HELP<br><span class="text-green-500">CENTER</span></h1>
            <p class="text-white/70 text-sm font-mono font-medium mt-4 max-w-md">We're here to ensure your event experience is seamless. How can we help today?</p>
        </div>
       
    </div>

    <div class="grid grid-cols-12 gap-8">
        {{-- Contact Form --}}
        <div class="col-span-12 lg:col-span-7">
            <div class="bg-green-400/10 border border-green-400/10 h-full w-full flex flex-col justify-center items-center p-10">
                <h2 class="text-2xl font-bold text-white mb-6">Send Message</h2>
                <form action="{{ route('contact.submit') }}" method="POST" class="w-full">
                    @csrf
                    <input type="hidden" name="name" value="{{ $user->first_name }} {{ $user->last_name }}">
                    <input type="hidden" name="email" value="{{ $user->email }}">
                    
                    @if(session('success'))
                    <div class="mb-6 p-4 bg-green-500/10 border border-green-500/20 text-green-400 rounded-3xl text-sm italic">
                        {{ session('success') }}
                    </div>
                    @endif

                    <div class="grid grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-white/60 font-medium ml-1 text-sm mb-2">Subject</label>
                            <select name="subject" class="w-full p-3 rounded-3xl bg-[#b0a6df]/10 outline outline-[#b0a6df]/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70">
                                <option value="Ticket Issue">Ticket Issue</option>
                                <option value="Payment Problem">Payment Problem</option>
                                <option value="Account Management">Account Management</option>
                                <option value="Event Inquiry">Event Inquiry</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div x-data="{ priority: 'Normal' }">
                            <input type="hidden" name="priority" :value="priority">
                            <label class="block text-white/60 font-medium ml-1 text-sm mb-2">Priority</label>
                            <div class="flex gap-2">
                                <button type="button" @click="priority = 'Low'" 
                                        :class="priority === 'Low' ? 'border-orange-400/50 bg-[#b0a6df]/20' : ''"
                                        class="flex-1 text-center w-full p-3 rounded-3xl bg-[#b0a6df]/10 border border-[#b0a6df]/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold transition-all">Low</button>
                                <button type="button" @click="priority = 'Normal'"
                                        :class="priority === 'Normal' ? 'border-orange-400/50 bg-[#b0a6df]/20' : ''"
                                        class="flex-1 text-center w-full p-3 rounded-3xl bg-[#b0a6df]/10 border border-[#b0a6df]/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold transition-all">Normal</button>
                            </div>
                        </div>
                    </div>
                    <div class="mb-8">
                        <label class="block text-white/60 font-medium ml-1 text-sm mb-2">Details</label>
                        <textarea name="message" rows="5" required class="w-full p-3 rounded-3xl bg-[#b0a6df]/10 outline outline-[#b0a6df]/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70" placeholder="Describe your issue..."></textarea>
                    </div>
                    <button type="submit" class="w-full p-3 bg-black/80 text-white/80 font-medium font-mono text-sm border border-green-400/10 rounded-3xl hover:bg-black transition-colors">Submit Ticket</button>
                </form>
            </div>
        </div>

        {{-- Quick Links/FAQ --}}
        <div class="col-span-12 lg:col-span-5 space-y-6">
            <div class="bg-green-400/10 rounded-3xl p-8">
                <h3 class="text-white font-bold mb-6">Direct Support</h3>
                <div class="space-y-4">
                    <div class="flex items-center gap-4 p-4 bg-orange-400/70 rounded-2xl border border-zinc-800">
                        <div class="size-10 bg-black/95 rounded-[50%] flex items-center justify-center text-green-500"><i class="fab fa-whatsapp"></i></div>
                        <div>
                            <p class="text-black/90 text-sm font-bold">WhatsApp Support</p>
                            <p class="text-black/70 font-mono font-medium text-xs">+256 700 000 000</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4 p-4 bg-orange-400/70 rounded-2xl border border-zinc-800">
                        <div class="size-10 bg-black/95 rounded-[50%] flex items-center justify-center text-orange-400"><i class="far fa-envelope"></i></div>
                        <div>
                            <p class="text-black/90 text-sm font-bold">Email Help</p>
                            <p class="text-black/70 text-xs">support@eticketing.com</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-orange-500 p-8 rounded-[2.5rem] text-black">
                <i class="fas fa-question-circle text-4xl mb-4"></i>
                <h3 class="text-2xl font-black uppercase tracking-tighter">Fast Answers</h3>
                <p class="text-black/70 text-sm mt-2 mb-6 font-medium">Check our most common questions before reaching out. It's usually faster!</p>
                <a href="{{ route('contact') }}" class="inline-block py-3 px-6 bg-black text-white rounded-xl text-xs font-bold uppercase tracking-widest hover:bg-zinc-800 transition-all">View All FAQs</a>
            </div>
        </div>
    </div>
</div>
@endsection
