@extends('layouts.admin')

@section('title', 'Manage Users')

@section('content')
<div class="bg-green-400/10 border border-zinc-800 rounded-2xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-orange-400/70 text-black/90 font-mono text-xs uppercase font-medium">
                <tr>
                    <th class="px-6 py-4">User</th>
                    <th class="px-6 py-4">Email</th>
                    <th class="px-6 py-4">Role</th>
                    <th class="px-6 py-4">Joined</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-800">
                @foreach($users as $user)
                <tr class="hover:bg-zinc-800/50 transition-colors">
                    <td class="px-6 py-4 flex items-center gap-3">
                        <img src="{{ $user->profile_pic ? asset('storage/'.$user->profile_pic) : asset('default.jpg') }}" class="w-8 h-8 rounded-full object-cover bg-zinc-800">
                        <span class="font-medium text-white">{{ $user->first_name }} {{ $user->last_name }}</span>
                    </td>
                    <td class="px-6 py-4 text-zinc-400">{{ $user->email }}</td>
                    <td class="px-6 py-4">
                        @if($user->is_admin)
                        <span class="px-2 py-1 bg-purple-500/10 text-purple-400 text-xs rounded border border-purple-500/20">Admin</span>
                        @else
                        <span class="px-2 py-1 bg-zinc-800 text-zinc-400 text-xs rounded border border-zinc-700">User</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-zinc-500 text-sm">{{ $user->created_at->format('M d, Y') }}</td>
                    <td class="px-6 py-4 text-right">
                        @if(!$user->is_admin)
                        <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" onsubmit="return confirm('Permanently delete this user?')" class="inline">
                            @csrf
                            @method('DELETE')
                            <button class="text-red-400 hover:text-red-300 text-sm font-medium transition-colors">Delete</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="p-4 border-t border-zinc-800">
        {{ $users->links() }}
    </div>
</div>
@endsection
