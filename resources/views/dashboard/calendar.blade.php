@extends('layouts.dashboard')

@section('title', 'My Calendar')

@push('styles')
<style>
    .cal-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        grid-auto-rows: 1fr;
    }
    .cal-cell {
        min-height: 110px;
        border-right: 1px solid rgba(255,255,255,0.05);
        border-bottom: 1px solid rgba(255,255,255,0.05);
    }
    .cal-cell:nth-child(7n) { border-right: none; }
    .event-chip {
        display: block;
        font-size: 10px;
        font-weight: 600;
        line-height: 1.2;
        padding: 2px 6px;
        border-radius: 4px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        cursor: pointer;
        transition: filter 0.15s;
    }
    .event-chip:hover { filter: brightness(1.2); }
    .chip-ticket { background: rgba(251,146,60,0.25); color: #fb923c; border-left: 3px solid #fb923c; }
    .chip-saved  { background: rgba(96,165,250,0.25); color: #60a5fa; border-left: 3px solid #60a5fa; }

    /* Mini calendar */
    .mini-cal-grid { display: grid; grid-template-columns: repeat(7, 1fr); gap: 2px; }
    .mini-day {
        aspect-ratio: 1;
        display: flex; align-items: center; justify-content: center;
        font-size: 11px; border-radius: 50%; cursor: default;
        color: rgba(255,255,255,0.5);
        transition: background 0.15s;
    }
    .mini-day.has-event { color: #fb923c; font-weight: 700; }
    .mini-day.today { background: #fb923c; color: #000; font-weight: 700; }
    .mini-day.selected { background: rgba(251,146,60,0.2); color: #fb923c; }
    .mini-day.other-month { opacity: 0.25; }

    /* Event detail modal */
    .modal-backdrop { background: rgba(0,0,0,0.7); backdrop-filter: blur(4px); }

    /* Scrollbar hide */
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endpush

@section('content')
<div
    x-data="calendarApp({{ $month }}, {{ $year }}, {{ $allEvents->toJson() }})"
    @keydown.escape.window="closeModal()"
    class="flex flex-col gap-0 h-full"
>

    {{-- ===== TOP BAR ===== --}}
    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-3">
            <button @click="goToToday()" class="px-3 py-1.5 text-xs font-semibold border border-white/10 rounded-lg text-white/70 hover:bg-white/5 transition">Today</button>
            <div class="flex items-center gap-1">
                <a :href="prevUrl()" class="size-8 flex items-center justify-center rounded-full hover:bg-white/10 text-white/60 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                </a>
                <a :href="nextUrl()" class="size-8 flex items-center justify-center rounded-full hover:bg-white/10 text-white/60 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
                </a>
            </div>
            <h2 class="text-xl font-bold text-white font-mono tracking-tight" x-text="monthLabel()"></h2>
        </div>
        <div class="flex items-center gap-4">
            <span class="flex items-center gap-1.5 font-mono font-bold text-xs text-zinc-400"><span class="w-3 h-3 rounded-sm bg-orange-400/30 border-l-2 border-orange-400 inline-block"></span>Ticket</span>
            <span class="flex items-center gap-1.5 font-mono font-bold text-xs text-zinc-400"><span class="w-3 h-3 rounded-sm bg-blue-400/30 border-l-2 border-blue-400 inline-block"></span>Saved</span>
        </div>
    </div>

    <div class="flex gap-4 flex-1 min-h-0">

        {{-- ===== LEFT SIDEBAR ===== --}}
        <div class="hidden lg:flex flex-col gap-4 w-52 shrink-0">

            {{-- Mini Calendar --}}
            <div class="bg-zinc-900/60 border border-green-400/5 font-mono rounded-2xl p-3">
                <div class="flex items-center justify-between  mb-2">
                    <span class="text-xs font-bold text-white/70" x-text="monthNames[month-1] + ' ' + year"></span>
                    <div class="flex gap-1">
                        <a :href="prevUrl()" class="size-5 flex items-center justify-center rounded text-zinc-500 hover:text-white transition">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                        </a>
                        <a :href="nextUrl()" class="size-5 flex items-center justify-center rounded text-zinc-500 hover:text-white transition">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
                        </a>
                    </div>
                </div>
                <div class="mini-cal-grid mb-1">
                    <template x-for="d in ['S','M','T','W','T','F','S']" :key="d">
                        <div class="text-center text-[9px] font-bold text-zinc-600 py-0.5" x-text="d"></div>
                    </template>
                </div>
                <div class="mini-cal-grid">
                    <template x-for="i in leadingBlanks()" :key="'mb'+i">
                        <div class="mini-day other-month"></div>
                    </template>
                    <template x-for="day in daysInMonth()" :key="'md'+day">
                        <div
                            class="mini-day"
                            :class="{
                                'today': isToday(day),
                                'has-event': eventsOnDay(day).length > 0 && !isToday(day),
                                'selected': selectedDay === day && !isToday(day)
                            }"
                            @click="selectedDay = eventsOnDay(day).length > 0 ? day : null"
                            x-text="day"
                        ></div>
                    </template>
                </div>
            </div>

            {{-- Upcoming events list --}}
            <div class="bg-zinc-900/60 border border-green-400/5 rounded-2xl p-3 flex-1 overflow-y-auto no-scrollbar">
                <h4 class="text-[10px] font-bold uppercase text-zinc-500 tracking-widest mb-2">This Month</h4>
                @if($allEvents->count() > 0)
                    @foreach($allEvents->sortBy('event_date') as $ev)
                    <a href="{{ route('event.show', $ev['id']) }}" class="block mb-2 group">
                        <div class="flex gap-2 items-start">
                            <div class="w-0.5 rounded-full self-stretch mt-0.5 {{ $ev['source'] === 'ticket' ? 'bg-orange-400' : 'bg-blue-400' }}"></div>
                            <div class="flex-1 min-w-0">
                                <p class="text-white/80 text-[11px] font-semibold truncate group-hover:text-orange-400 transition-colors">{{ $ev['event_name'] }}</p>
                                <p class="text-zinc-500 text-[10px] font-mono">{{ \Carbon\Carbon::parse($ev['event_date'])->format('M j') }}@if(!empty($ev['start_time'])) · {{ \Carbon\Carbon::parse($ev['start_time'])->format('g:i A') }}@endif</p>
                            </div>
                        </div>
                    </a>
                    @endforeach
                @else
                    <p class="text-zinc-600 text-xs text-center py-4">No events this month</p>
                @endif
            </div>
        </div>

        {{-- ===== MAIN CALENDAR GRID ===== --}}
        <div class="flex-1 bg-zinc-900/40 border border-white/5 rounded-2xl overflow-hidden flex flex-col min-h-0">

            {{-- Day headers --}}
            <div class="grid grid-cols-7 border-b border-white/5 shrink-0">
                <template x-for="(d, i) in ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday']" :key="d">
                    <div
                        class="text-center text-xs font-mono font-semibold uppercase tracking-wider py-2 border-r border-white/5 last:border-r-0"
                        :class="i === 0 || i === 6 ? 'text-zinc-600' : 'text-zinc-500'"
                        x-text="d.slice(0,3)"
                    ></div>
                </template>
            </div>

            {{-- Grid rows --}}
            <div class="flex-1 overflow-y-auto no-scrollbar">
                <div class="cal-grid h-full">

                    {{-- Leading blanks --}}
                    <template x-for="i in leadingBlanks()" :key="'lb'+i">
                        <div class="cal-cell bg-black/20"></div>
                    </template>

                    {{-- Day cells --}}
                    <template x-for="day in daysInMonth()" :key="'dc'+day">
                        <div
                            class="cal-cell p-1 flex flex-col gap-0.5 group"
                            :class="{
                                'bg-orange-400/[0.03]': isToday(day),
                                'hover:bg-white/[0.015]': true
                            }"
                        >
                            {{-- Day number --}}
                            <div class="flex justify-end mb-0.5">
                                <span
                                    class="text-xs font-bold w-6 h-6 flex items-center justify-center rounded-full transition-colors"
                                    :class="isToday(day)
                                        ? 'bg-orange-500 text-black'
                                        : (eventsOnDay(day).length > 0 ? 'text-white/80' : 'text-zinc-600')"
                                    x-text="day"
                                ></span>
                            </div>

                            {{-- Event chips --}}
                            <template x-for="ev in eventsOnDay(day).slice(0, 3)" :key="ev.id">
                                <button
                                    @click.stop="openModal(ev)"
                                    class="event-chip w-full text-left"
                                    :class="ev.source === 'ticket' ? 'chip-ticket' : 'chip-saved'"
                                    :title="ev.event_name"
                                    x-text="(ev.start_time ? formatTime(ev.start_time) + ' ' : '') + ev.event_name"
                                ></button>
                            </template>

                            {{-- Overflow --}}
                            <button
                                x-show="eventsOnDay(day).length > 3"
                                @click.stop="openDayModal(day)"
                                class="text-[9px] text-zinc-500 hover:text-zinc-300 text-left px-1 transition-colors"
                                x-text="'+' + (eventsOnDay(day).length - 3) + ' more'"
                            ></button>
                        </div>
                    </template>

                    {{-- Trailing blanks --}}
                    <template x-for="i in trailingBlanks()" :key="'tb'+i">
                        <div class="cal-cell bg-black/20"></div>
                    </template>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== EVENT DETAIL MODAL ===== --}}
    <div
        x-show="modalOpen"
        x-cloak
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop"
        @click.self="closeModal()"
    >
        <div
            x-show="modalOpen"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="bg-zinc-900 border border-white/10 rounded-2xl shadow-2xl w-full max-w-sm overflow-hidden"
        >
            {{-- Modal header colour bar --}}
            <div class="h-1.5" :class="modalEvent && modalEvent.source === 'ticket' ? 'bg-orange-400' : 'bg-blue-400'"></div>

            <div class="p-5" x-show="modalEvent && !dayModalOpen">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex-1 min-w-0">
                        <span
                            class="text-[9px] font-bold uppercase px-2 py-0.5 rounded-full border mb-2 inline-block"
                            :class="modalEvent && modalEvent.source === 'ticket'
                                ? 'bg-orange-400/20 text-orange-400 border-orange-400/30'
                                : 'bg-blue-400/20 text-blue-400 border-blue-400/30'"
                            x-text="modalEvent && modalEvent.source === 'ticket' ? '🎟 Ticket' : '🔖 Saved'"
                        ></span>
                        <h3 class="text-white font-bold text-lg leading-tight" x-text="modalEvent ? modalEvent.event_name : ''"></h3>
                    </div>
                    <button @click="closeModal()" class="ml-3 text-zinc-500 hover:text-white transition shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                    </button>
                </div>

                <div class="space-y-2.5 text-sm">
                    {{-- Date --}}
                    <div class="flex items-center gap-3 text-zinc-400">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="shrink-0"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                        <span x-text="modalEvent ? formatDate(modalEvent.event_date) : ''"></span>
                    </div>
                    {{-- Time --}}
                    <div class="flex items-center gap-3 text-zinc-400" x-show="modalEvent && modalEvent.start_time">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="shrink-0"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        <span x-text="modalEvent ? formatTime(modalEvent.start_time) + (modalEvent.end_time ? ' – ' + formatTime(modalEvent.end_time) : '') : ''"></span>
                    </div>
                    {{-- Venue --}}
                    <div class="flex items-center gap-3 text-zinc-400" x-show="modalEvent && (modalEvent.venue || modalEvent.location)">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="shrink-0"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                        <span x-text="modalEvent ? (modalEvent.venue || modalEvent.location) : ''"></span>
                    </div>
                    {{-- Organizer --}}
                    <div class="flex items-center gap-3 text-zinc-400" x-show="modalEvent && modalEvent.organizer">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="shrink-0"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        <span x-text="modalEvent && modalEvent.organizer ? modalEvent.organizer.business_name : ''"></span>
                    </div>
                </div>

                <div class="mt-5 flex gap-2">
                    <a
                        :href="modalEvent ? '/events/' + modalEvent.id : '#'"
                        class="flex-1 text-center text-xs font-bold py-2 rounded-xl bg-orange-400 text-black hover:bg-orange-300 transition"
                    >View Event</a>
                    <button @click="closeModal()" class="px-4 text-xs font-bold py-2 rounded-xl bg-white/5 text-zinc-400 hover:bg-white/10 transition">Close</button>
                </div>
            </div>

            {{-- Day modal (multiple events) --}}
            <div class="p-5" x-show="dayModalOpen">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-white font-bold" x-text="dayModalLabel()"></h3>
                    <button @click="closeModal()" class="text-zinc-500 hover:text-white transition">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                    </button>
                </div>
                <div class="space-y-2 max-h-64 overflow-y-auto no-scrollbar">
                    <template x-for="ev in eventsOnDay(dayModalDay)" :key="ev.id">
                        <button @click="openModal(ev); dayModalOpen = false" class="w-full text-left event-chip py-2" :class="ev.source === 'ticket' ? 'chip-ticket' : 'chip-saved'">
                            <span class="font-bold block truncate" x-text="ev.event_name"></span>
                            <span class="text-[9px] opacity-70" x-text="formatTime(ev.start_time)"></span>
                        </button>
                    </template>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
function calendarApp(month, year, events) {
    return {
        month, year, events,
        modalOpen: false,
        modalEvent: null,
        dayModalOpen: false,
        dayModalDay: null,
        selectedDay: null,

        monthNames: ['January','February','March','April','May','June','July','August','September','October','November','December'],

        monthLabel() { return this.monthNames[this.month - 1] + ' ' + this.year; },

        daysInMonth() {
            const count = new Date(this.year, this.month, 0).getDate();
            return Array.from({ length: count }, (_, i) => i + 1);
        },

        leadingBlanks() {
            const first = new Date(this.year, this.month - 1, 1).getDay();
            return Array.from({ length: first }, (_, i) => i + 1);
        },

        trailingBlanks() {
            const total = new Date(this.year, this.month, 0).getDate();
            const first = new Date(this.year, this.month - 1, 1).getDay();
            const used = (first + total) % 7;
            const trailing = used === 0 ? 0 : 7 - used;
            return Array.from({ length: trailing }, (_, i) => i + 1);
        },

        isToday(day) {
            const now = new Date();
            return now.getFullYear() === this.year && (now.getMonth() + 1) === this.month && now.getDate() === day;
        },

        eventsOnDay(day) {
            if (!day) return [];
            const pad = d => String(d).padStart(2, '0');
            const dateStr = `${this.year}-${pad(this.month)}-${pad(day)}`;
            return this.events.filter(e => {
                const evDate = e.event_date.split('T')[0];
                return evDate === dateStr;
            });
        },

        openModal(ev) {
            this.modalEvent = ev;
            this.dayModalOpen = false;
            this.modalOpen = true;
        },

        openDayModal(day) {
            this.dayModalDay = day;
            this.dayModalOpen = true;
            this.modalOpen = true;
        },

        closeModal() {
            this.modalOpen = false;
            this.dayModalOpen = false;
            this.modalEvent = null;
            this.dayModalDay = null;
        },

        dayModalLabel() {
            if (!this.dayModalDay) return '';
            const d = new Date(this.year, this.month - 1, this.dayModalDay);
            return d.toLocaleDateString('en-US', { weekday: 'long', month: 'long', day: 'numeric' });
        },

        goToToday() {
            const now = new Date();
            window.location.href = `/user/dashboard/calendar?month=${now.getMonth()+1}&year=${now.getFullYear()}`;
        },

        prevUrl() {
            let m = this.month - 1, y = this.year;
            if (m < 1) { m = 12; y--; }
            return `/user/dashboard/calendar?month=${m}&year=${y}`;
        },

        nextUrl() {
            let m = this.month + 1, y = this.year;
            if (m > 12) { m = 1; y++; }
            return `/user/dashboard/calendar?month=${m}&year=${y}`;
        },

        formatTime(t) {
            if (!t) return '';
            const [h, m] = t.split(':').map(Number);
            return `${h % 12 || 12}:${String(m).padStart(2,'0')} ${h >= 12 ? 'PM' : 'AM'}`;
        },

        formatDate(d) {
            if (!d) return '';
            // Ensure we only use the YYYY-MM-DD part to avoid timezone shifts
            const dateOnly = d.split('T')[0];
            const dt = new Date(dateOnly + 'T00:00:00');
            return dt.toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
        }
    };
}
</script>
@endpush
