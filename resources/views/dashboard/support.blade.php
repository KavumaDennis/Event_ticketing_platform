@extends('layouts.dashboard')

@section('content')
<div class="px-6 pb-20">
    <div class="flex justify-between items-end mb-12">
        <div>
            <h1 class="text-6xl font-black text-white uppercase tracking-tighter leading-none">HELP<br><span class="text-green-500">CENTER</span></h1>
            <p class="text-zinc-500 text-lg mt-4 max-w-md">We're here to ensure your event experience is seamless. How can we help today?</p>
        </div>
        <div class="hidden lg:block">
            <div class="size-32 bg-zinc-900 border border-zinc-800 rounded-3xl flex items-center justify-center rotate-12 group hover:rotate-0 transition-all duration-500">
                <i class="fas fa-headset text-4xl text-green-500 group-hover:scale-110 transition-transform"></i>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-12 gap-8">
        {{-- Contact Form --}}
        <div class="col-span-12 lg:col-span-7">
            <div class="bg-zinc-900 border border-zinc-800 rounded-[3rem] p-10">
                <h2 class="text-2xl font-bold text-white mb-6">Send Message</h2>
                <form action="#" method="POST">
                    @csrf
                    <div class="grid grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-zinc-500 text-xs font-bold uppercase mb-2">Subject</label>
                            <select class="w-full bg-zinc-950 border border-zinc-800 rounded-2xl py-4 px-6 text-white text-sm focus:outline-none focus:border-green-500/50">
                                <option>Ticket Issue</option>
                                <option>Payment Problem</option>
                                <option>Account Management</option>
                                <option>Event Inquiry</option>
                                <option>Other</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-zinc-500 text-xs font-bold uppercase mb-2">Priority</label>
                            <div class="flex gap-2">
                                <span class="flex-1 text-center py-4 bg-zinc-950 border border-zinc-800 rounded-2xl text-xs text-zinc-500 cursor-pointer hover:border-blue-500">Low</span>
                                <span class="flex-1 text-center py-4 bg-zinc-950 border border-green-500/30 rounded-2xl text-xs text-green-500 cursor-pointer">Normal</span>
                            </div>
                        </div>
                    </div>
                    <div class="mb-8">
                        <label class="block text-zinc-500 text-xs font-bold uppercase mb-2">Details</label>
                        <textarea rows="5" class="w-full bg-zinc-950 border border-zinc-800 rounded-2xl py-4 px-6 text-white text-sm focus:outline-none focus:border-green-500/50" placeholder="Describe your issue..."></textarea>
                    </div>
                    <button type="submit" class="w-full py-5 bg-green-500 text-black font-black uppercase tracking-widest rounded-2xl hover:bg-green-400 transition-all">Submit Ticket</button>
                </form>
            </div>
        </div>

        {{-- Quick Links/FAQ --}}
        <div class="col-span-12 lg:col-span-5 space-y-6">
            <div class="bg-zinc-900/50 border border-zinc-800 rounded-[2.5rem] p-8">
                <h3 class="text-white font-bold mb-6">Direct Support</h3>
                <div class="space-y-4">
                    <div class="flex items-center gap-4 p-4 bg-zinc-950 rounded-2xl border border-zinc-800">
                        <div class="size-10 bg-green-500/10 rounded-lg flex items-center justify-center text-green-500"><i class="fab fa-whatsapp"></i></div>
                        <div>
                            <p class="text-white text-sm font-bold">WhatsApp Support</p>
                            <p class="text-zinc-500 text-xs">+256 700 000 000</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4 p-4 bg-zinc-950 rounded-2xl border border-zinc-800">
                        <div class="size-10 bg-orange-500/10 rounded-lg flex items-center justify-center text-orange-400"><i class="far fa-envelope"></i></div>
                        <div>
                            <p class="text-white text-sm font-bold">Email Help</p>
                            <p class="text-zinc-500 text-xs">support@eticketing.com</p>
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
