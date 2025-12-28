@extends('layouts.dashboard')

@section('title', 'Following')

@section('content')
<div class="">
    <h2 class="text-2xl text-white/90 mb-6">Following</h2>

    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
        @forelse($following as $org)
        <div class="bg-green-400/10 flex flex-col p-3 rounded-3xl border border-green-400/10 items-center justify-between">
            <div class="flex flex-col items-center gap-2 mb-2">
                <img src="{{ $org->organizer_image ? asset('storage/'.$org->organizer_image) : asset('default.jpg') }}" class="w-16 h-16 rounded-full object-cover">
                <div class="text-center">
                    <h3 class="text-orange-400/80 font-semibold">{{ $org->business_name }}</h3>
                    <p class="text-white/50 text-xs">{{ $org->followers_count }} Followers</p>
                </div>
            </div>

            <div class="pt-3 w-full text-center border-t border-white/10">
                <button onclick="unfollow({{ $org->id }})" class="px-4 py-2 text-xs bg-red-500/20 text-red-400 border border-red-500/30 rounded-2xl hover:bg-red-500 hover:text-white transition-colors">
                    Unfollow
                </button>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-10 text-white/50">
            You are not following any organizers yet.
        </div>
        @endforelse
    </div>
</div>

<script>
    async function unfollow(organizerId) {
        if (!confirm('Are you sure you want to unfollow this organizer?')) return;

        try {
            const response = await postJSON(`/organizer/${organizerId}/follow`);
            if (response.status === 'unfollowed') {
                window.location.reload();
            }
        } catch (e) {
            alert('Something went wrong');
        }
    }

</script>
@endsection
