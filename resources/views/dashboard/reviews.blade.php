@extends('layouts.dashboard')

@section('content')
<div class="px-6 pb-20">
    <div class="mb-12">
        <h1 class="text-4xl font-black text-white uppercase tracking-tighter">Event Reviews</h1>
        <p class="text-zinc-500 text-sm">Your feedback helps organizers improve their shows</p>
    </div>

    @if($toReview->count() > 0)
    <div class="mb-16">
        <h2 class="text-sm font-bold text-zinc-500 uppercase tracking-widest mb-6">Pending Reviews</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            @foreach($toReview as $ticket)
            <div class="bg-zinc-900 border border-zinc-800 rounded-[2.5rem] p-8 relative group overflow-hidden">
                <div class="absolute -right-20 -top-20 size-64 bg-orange-400/5 rounded-full blur-3xl group-hover:bg-orange-400/10 transition-all duration-700"></div>
                
                <h3 class="text-xl font-bold text-white mb-2">{{ $ticket->event->event_name }}</h3>
                <p class="text-zinc-500 text-xs mb-8">{{ \Carbon\Carbon::parse($ticket->event->event_date)->format('M d, Y') }}</p>

                <form action="{{ route('reviews.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="event_id" value="{{ $ticket->event_id }}">
                    
                    <div class="mb-6">
                        <label class="block text-zinc-500 text-[10px] font-black uppercase tracking-widest mb-3">Rating</label>
                        <div class="flex gap-2">
                            @for($i=1; $i<=5; $i++)
                            <label class="cursor-pointer group/star">
                                <input type="radio" name="rating" value="{{ $i }}" class="hidden peer" required>
                                <div class="size-10 bg-zinc-950 border border-zinc-800 rounded-xl flex items-center justify-center text-zinc-700 peer-checked:bg-orange-500/20 peer-checked:text-orange-400 peer-checked:border-orange-500/30 group-hover/star:border-orange-500/30 transition-all">
                                    <i class="fas fa-star"></i>
                                </div>
                            </label>
                            @endfor
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-zinc-500 text-[10px] font-black uppercase tracking-widest mb-3">Your Feedback</label>
                        <textarea name="body" rows="3" required
                                  class="w-full bg-zinc-950 border border-zinc-800 rounded-2xl py-4 px-6 text-white text-sm focus:outline-none focus:border-orange-500/50 transition-all"
                                  placeholder="What did you think of the event?"></textarea>
                    </div>

                    <button type="submit" class="w-full py-4 bg-orange-500 text-black font-black uppercase tracking-widest rounded-2xl hover:bg-orange-400 transition-all">Submit Review</button>
                </form>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <div>
        <h2 class="text-sm font-bold text-zinc-500 uppercase tracking-widest mb-6">Your Posted Reviews</h2>
        <div class="space-y-4">
            @forelse($myReviews as $review)
            <div class="bg-zinc-900/40 border border-zinc-800 p-6 rounded-3xl flex justify-between items-start">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <h4 class="text-white font-bold">{{ $review->event->event_name }}</h4>
                        <div class="flex text-orange-400 text-[10px]">
                            @for($i=1; $i<=5; $i++)
                            <i class="{{ $i <= $review->rating ? 'fas' : 'far' }} fa-star"></i>
                            @endfor
                        </div>
                    </div>
                    <p class="text-zinc-400 text-sm leading-relaxed">{{ $review->body }}</p>
                    <p class="text-zinc-600 text-[10px] mt-4 uppercase font-bold">{{ $review->created_at->diffForHumans() }}</p>
                </div>
                <form action="{{ route('reviews.destroy', $review) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="size-10 bg-red-500/10 text-red-500 rounded-xl flex items-center justify-center hover:bg-red-500/20 transition-all"><i class="far fa-trash-alt text-sm"></i></button>
                </form>
            </div>
            @empty
            <p class="text-zinc-600 text-center py-10">You haven't posted any reviews yet.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
