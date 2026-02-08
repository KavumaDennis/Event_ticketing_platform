<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    //
    public function index(Request $request)
    {
        $query = Event::query();

        if ($request->filled('search')) {
            $query->where('event_name', 'like', '%' . $request->search . '%')
                ->orWhere('location', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $events = $query->orderBy('created_at', 'desc')->paginate(10);
        $categories = Event::select('category')->distinct()->pluck('category');

        $reviews = Review::with('user')->latest()->get();

        // Prepare the reviews for Alpine
        $reviewsForAlpine = $reviews->map(function ($r) {
            return [
                'id' => $r->id,
                'body' => $r->body,
                'rating' => $r->rating,
                'user_name' => $r->user ? $r->user->first_name . ' ' . $r->user->last_name : 'Anonymous',
                'user_photo' => $r->user && $r->user->profile_pic
                    ? Storage::url($r->user->profile_pic) 
                    :asset('default.jpg'),
                'created_at' => $r->created_at->toIsoString(),
            ];
        });

        if ($request->ajax()) {
            return view('partials.events-grid', compact('events'))->render();
        }

        return view('home', [
            'events' => $events,
            'categories' => $categories,
            'reviews' => $reviews,
            'reviewsForAlpine' => $reviewsForAlpine, // pass preprocessed reviews
        ]);
    }




    public function store(Request $request)
    {
        $request->validate([
            'body' => 'required|string|max:500',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $review = Review::create([
            'user_id' => auth()->id(),
            'body' => $request->body,
            'rating' => $request->rating,
        ]);

        return response()->json(['success' => true]);
    }


}
