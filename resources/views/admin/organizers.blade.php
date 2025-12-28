@extends('layouts.admin')

@section('title', 'Manage Organizers')

@section('content')
<div class="bg-green-400/10 rounded-2xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-orange-400/70 text-black/90 font-mono text-xs uppercase font-medium">
                <tr>
                    <th class="px-6 py-4">Organizer</th>
                    <th class="px-6 py-4">Events</th>
                    <th class="px-6 py-4">Followers</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-800">
                @foreach($organizers as $org)
                <tr class="hover:bg-zinc-800/50 transition-colors">
                    <td class="px-6 py-4 flex items-center gap-3">
                        <img src="{{ $org->organizer_image ? asset('storage/'.$org->organizer_image) : asset('default.jpg') }}" class="w-10 h-10 rounded-xl object-cover bg-zinc-800">
                        <div>
                            <div class="font-medium text-white">{{ $org->business_name }}</div>
                            <div class="text-xs text-zinc-500">{{ $org->business_email }}</div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-zinc-400">{{ $org->events_count }}</td>
                    <td class="px-6 py-4 text-zinc-400">{{ $org->followers_count }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 bg-green-500/10 text-green-400 text-xs rounded border border-green-500/20">Active</span>
                    </td>
                    <td class="px-6 py-4 text-right flex justify-end gap-3">
                        {{-- Verify Button Simulator --}}
                        <form action="{{ route('admin.organizers.verify', $org->id) }}" method="POST" class="inline">
                            @csrf
                            <button class="text-blue-400 hover:text-blue-300 text-sm font-medium transition-colors" title="Verify Organizer">Verify</button>
                        </form>

                        <form action="{{ route('admin.organizers.delete', $org->id) }}" method="POST" onsubmit="return confirm('Delete this organizer? This will delete all their events!')" class="inline">
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
        {{ $organizers->links() }}
    </div>
</div>
@endsection
