@extends('layouts.admin')

@section('title', 'Manage Events')

@section('content')
<div class="bg-green-400/10 rounded-xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-orange-400/70 text-black/90 font-mono text-xs uppercase font-medium">
                <tr>
                    <th class="px-6 py-4">Event</th>
                    <th class="px-6 py-4">Organizer</th>
                    <th class="px-6 py-4">Date</th>
                    <th class="px-6 py-4">Venue</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-800">
                @foreach($events as $event)
                <tr class="hover:bg-zinc-800/50 transition-colors">
                    <td class="px-6 py-4 flex items-center gap-3">
                        <img src="{{ $event->event_image ? asset('storage/'.$event->event_image) : asset('public/default.jpg') }}" class="w-12 h-12 rounded-lg object-cover bg-zinc-800">
                        <div>
                            <div class="font-medium text-white">{{ $event->event_name }}</div>
                            <div class="text-xs text-zinc-500">{{ $event->category }}</div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-zinc-400">{{ $event->organizer->business_name ?? 'Unknown' }}</td>
                    <td class="px-6 py-4 text-zinc-400">
                        {{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}<br>
                        <span class="text-xs text-zinc-500">{{ $event->start_time }}</span>
                    </td>
                    <td class="px-6 py-4 text-zinc-400">{{ $event->venue }}</td>
                    <td class="px-6 py-4 text-right">
                        <form action="{{ route('admin.events.delete', $event->id) }}" method="POST" onsubmit="return confirm('Delete this event?')" class="inline">
                            @csrf
                            @method('DELETE')
                            <button class="text-red-400 hover:text-red-300 text-sm font-medium transition-colors">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="p-4 border-t border-zinc-800">
        {{ $events->links() }}
    </div>
</div>
@endsection
