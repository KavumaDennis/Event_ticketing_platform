@extends('layouts.admin')

@section('title', 'Manage Trends')

@section('content')
<div class="bg-green-400/10 rounded-xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-orange-400 text-black/90 font-mono text-xs uppercase font-medium">
                <tr>
                    <th class="px-6 py-4">Trend</th>
                    <th class="px-6 py-4">Creator</th>
                    <th class="px-6 py-4">Linked Event</th>
                    <th class="px-6 py-4">Editor's Pick</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-800">
                @foreach($trends as $trend)
                <tr class="hover:bg-zinc-800/50 transition-colors">
                    <td class="px-6 py-4 flex items-center gap-3">
                        @if($trend->is_video)
                        <video src="{{ $trend->first_media_url }}" class="w-12 h-12 rounded-lg object-cover bg-zinc-800" muted loop onmouseover="this.play()" onmouseout="this.pause()"></video>
                        @else
                        <img src="{{ $trend->first_media_url }}" class="w-12 h-12 rounded-lg object-cover bg-zinc-800">
                        @endif
                        <div>
                            <div class="font-medium text-white">{{ $trend->title }}</div>
                            <div class="text-xs text-zinc-500">{{ Str::limit($trend->body, 50) }}</div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-zinc-400">
                        {{ $trend->user->first_name }} {{ $trend->user->last_name }}
                    </td>
                    <td class="px-6 py-4 text-zinc-400">
                        {{ $trend->event->event_name ?? 'None' }}
                    </td>
                    <td class="px-6 py-4">
                        <form action="{{ route('admin.trends.toggle-editors-pick', $trend->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-3 py-1 rounded-full text-xs font-bold transition-colors {{ $trend->is_editors_pick ? 'bg-orange-400/90 text-black' : 'bg-zinc-800 text-zinc-400 hover:bg-zinc-700' }}">
                                {{ $trend->is_editors_pick ? "Editor's Pick" : 'Mark as Pick' }}
                            </button>
                        </form>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('trends.show', $trend->id) }}" target="_blank" class="text-blue-400 hover:text-blue-300 text-sm font-medium transition-colors mr-3">View</a>
                        <form action="{{ route('trends.destroy', $trend->id) }}" method="POST" onsubmit="return confirm('Delete this trend?')" class="inline">
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
        {{ $trends->links() }}
    </div>
</div>
@endsection
