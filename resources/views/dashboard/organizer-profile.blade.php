@extends('layouts.dashboard')

@section('title', 'Organizer Profile')

@section('content')
<div class="max-w-3xl">
    <h2 class="text-2xl text-white/90 mb-6">Your Organizer Profile</h2>

    {{-- SUCCESS MESSAGE --}}
    @if (session('success'))
    <div class="p-3 mb-6 bg-green-500 text-white rounded">
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-green-400/10 p-6 rounded-3xl border border-green-400/10">
        
        @if(!$organizer)
        <div class="text-center py-6">
            <p class="text-white/70 mb-4">You haven't set up your organizer profile yet.</p>
            <a href="{{ route('organizer.create') }}" class="px-6 py-2 bg-orange-400 text-black font-semibold rounded-2xl hover:bg-orange-500">
                Create Profile
            </a>
        </div>
        @else

        <form action="{{ route('organizer_store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <input type="hidden" name="id" value="{{ $organizer->id }}">

            <div class="flex items-center gap-6 mb-8">
                <img src="{{ $organizer->organizer_image ? asset('storage/'.$organizer->organizer_image) : asset('default.jpg') }}" class="w-24 h-24 rounded-full object-cover border-2 border-orange-400/50">
                <div>
                    <label class="block text-white/70 text-sm mb-2">Update Logo</label>
                    <input type="file" name="organizer_image" class="text-sm text-white/60 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-orange-400 file:text-black hover:file:bg-orange-500">
                </div>
            </div>

            <div class="grid gap-6">
                <div>
                    <label class="block text-white/70 text-sm mb-2">Business Name</label>
                    <input type="text" name="business_name" value="{{ $organizer->business_name }}" class="w-full bg-black/30 border border-white/10 rounded-xl p-3 text-white focus:outline-none focus:border-orange-400">
                </div>

                <div>
                    <label class="block text-white/70 text-sm mb-2">Business Email</label>
                    <input type="email" name="business_email" value="{{ $organizer->business_email }}" class="w-full bg-black/30 border border-white/10 rounded-xl p-3 text-white focus:outline-none focus:border-orange-400">
                </div>

                <div>
                    <label class="block text-white/70 text-sm mb-2">Business Phone</label>
                    <input type="tel" name="business_phone" value="{{ $organizer->business_phone }}" class="w-full bg-black/30 border border-white/10 rounded-xl p-3 text-white focus:outline-none focus:border-orange-400">
                </div>

                <div>
                    <label class="block text-white/70 text-sm mb-2">Bio / Description</label>
                    <textarea name="bio" rows="4" class="w-full bg-black/30 border border-white/10 rounded-xl p-3 text-white focus:outline-none focus:border-orange-400">{{ $organizer->bio ?? '' }}</textarea>
                </div>
            </div>

            <button type="submit" class="mt-8 w-full bg-orange-400 text-black font-bold py-3 rounded-xl hover:bg-orange-500">
                Save Changes
            </button>
        </form>
        @endif
    </div>
</div>
@endsection
