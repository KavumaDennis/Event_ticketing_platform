@extends('layouts.dashboard')

@section('title', 'Claim Your Ticket')

@section('content')
    <div class="px-6 pb-20 flex items-center justify-center h-fit">
        <div class="max-w-xl w-full">
            <div
                class="bg-black/85 bg-[url(/public/bg-img.png)] bg-cover bg-center bg-fixed  bg-blend-multiply border border-green-400/30 backdrop-blur-[1px] p-8 shadow-2xl relative overflow-hidden">
                <div class="absolute -right-20 -top-20 size-60 bg-green-500/5 rounded-full blur-3xl"></div>

                <div class="text-center mb-5">
                    <div
                        class="inline-flex items-center justify-center size-15 bg-green-400/10 rounded-2xl mb-6 border border-green-400/20">
                        <i class="fas fa-gift text-2xl text-green-400"></i>
                    </div>
                    <h1 class="text-2xl font-black text-white uppercase tracking-tighter mb-2">You've Got a Gift!</h1>
                    <p class="text-zinc-500 font-mono font-medium">Claim your ticket to join the event</p>
                </div>

                <div class="bg-green-400/10 border border-green-400/20 rounded-2xl p-6 mb-5">
                    <div class="flex gap-6 items-center">
                        <div class="size-20 bg-zinc-800 rounded-2xl overflow-hidden shrink-0 border border-zinc-700">
                            <img src="{{ $transfer->ticket->event->event_image ? asset('storage/' . $transfer->ticket->event->event_image) : asset('default.png') }}"
                                class="size-full object-cover">
                        </div>
                        <div>
                            <h3 class="text-xl font-medium text-white mb-1">{{ $transfer->ticket->event->event_name }}</h3>
                            <div class="text-zinc-500 text-sm space-y-1 ml-1">
                                <p class="font-mono font-medium">
                                    <i class="far fa-calendar-alt mr-2 text-green-400/50"></i>
                                    {{ \Carbon\Carbon::parse($transfer->ticket->event->event_date)->format('D, M d, Y') }}
                                </p>
                                <p class="font-mono font-medium">
                                    <i class="fas fa-map-marker-alt mr-2 text-green-400/50"></i>
                                    {{ $transfer->ticket->event->location }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3 pt-6 border-t border-zinc-800 flex items-center gap-3">
                        <div
                            class="size-8 rounded-full bg-orange-500/10 border border-orange-500/20 flex items-center justify-center shrink-0">
                            <i class="fas fa-user text-xs text-orange-400"></i>
                        </div>
                        <p class="text-zinc-400 text-xs">Sent by <span
                                class="text-orange-400 font-bold">{{ $transfer->sender->first_name }}
                                {{ $transfer->sender->last_name }}</span></p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <a href="{{ route('user.dashboard.tickets') }}"
                        class="flex-1 px-3 py-2 bg-white/5 border border-white/20 text-orange-400 text-center rounded-lg flex items-center justify-center gap-2 hover:text-white duration-150 transition-colors text-[10px] font-bold uppercase">
                        Maybe Later
                    </a>
                    <form action="{{ route('ticket.transfer.confirm', $transfer->token) }}" method="POST" class="inline flex-1">
                        @csrf
                        <button type="submit"
                            class="flex-1 w-full px-3 py-2 rounded-lg bg-orange-400 text-black text-[10px] font-bold uppercase tracking-wider hover:bg-orange-300 transition-all">
                            Claim Ticket
                        </button>
                    </form>
                </div>

                <p class="text-center text-[10px] text-zinc-600 mt-8 uppercase tracking-widest font-bold">
                    By claiming, the ticket will be instantly moved to your dashboard.
                </p>
            </div>
        </div>
    </div>
@endsection
