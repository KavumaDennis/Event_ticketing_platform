@extends('layouts.dashboard')

@section('title', 'Followers')

@section('content')
<div class="w-full">
    <h2 class="text-2xl text-white/90 mb-6">Your Followers</h2>

    @if(!$organizer)
    <div class="bg-yellow-500/10 p-4 rounded-2xl border border-yellow-500/20 text-yellow-500">
        You need to create an organizer profile to have followers.
    </div>
    @else

    <div class="grid grid-cols-1 md:grid-cols-3 w-full gap-4">
        @forelse($followers as $f)
        <div class="bg-green-400/10 p-4 rounded-3xl border border-green-400/10 flex items-center gap-4">
            <img src="{{ $f->profile_pic ? asset('storage/'.$f->profile_pic) : asset('default.jpg') }}" class="w-12 h-12 rounded-full object-cover">
            <div>
                <h3 class="text-white font-medium">{{ $f->first_name }} {{ $f->last_name }}</h3>
                <p class="text-white/50 text-xs">{{ $f->username }}</p>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-10 text-white/50">
            You don't have any followers yet.
        </div>
        @endforelse
    </div>
    @endif
</div>
@endsection
