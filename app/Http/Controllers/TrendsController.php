<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Organizer;
use App\Models\Trend;
use App\Models\TrendComment;
use App\Models\TrendLike;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Storage;


class TrendsController extends Controller
{
    use AuthorizesRequests;
    public function index()
    {

        $topCreators = User::withCount('trends')
            ->orderByDesc('trends_count')
            ->take(7)
            ->get();

        // Get the 3 most liked trends for the top cards
        $topTrends = Trend::with('user')
            ->withCount('likes')
            ->orderByDesc('likes_count')
            ->take(3)
            ->get();

        $topEvent = \App\Models\Event::withCount('likes')
            ->orderBy('likes_count', 'desc')
            ->first();


        // Check if user has liked each top trend
        if (Auth::check()) {
            $userLikedTrendIds = TrendLike::where('user_id', Auth::id())
                ->pluck('trend_id')
                ->toArray();

            foreach ($topTrends as $trend) {
                $trend->is_liked = in_array($trend->id, $userLikedTrendIds);
            }
        }

        // Get paginated trends for the main section
        $trends = Trend::with('user')
            ->withCount('likes')
            ->latest()
            ->paginate(4);

        // Check if user has liked each trend
        if (Auth::check()) {
            $userLikedTrendIds = TrendLike::where('user_id', Auth::id())
                ->pluck('trend_id')
                ->toArray();

            foreach ($trends as $trend) {
                $trend->is_liked = in_array($trend->id, $userLikedTrendIds);
            }
        }




        return view('trends', [
            'trends' => $trends,
            'topTrends' => $topTrends,
            'topCreators' => $topCreators,
            'topEvent' => $topEvent,
        ]);
    }

    public function create()
    {
        return view('createTrend');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

            // 🔥 NEW: validate the event tag (optional)
            'event_id' => 'nullable|exists:events,id',
        ]);

        // Handle image upload
        $path = $request->hasFile('image')
            ? $request->file('image')->store('trends', 'public')
            : null;

        // Create trend
        Trend::create([
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'body' => $validated['body'],
            'image' => $path,
            'event_id' => $validated['event_id'] ?? null, // 🔥 store event tag
        ]);

        return redirect()->back()->with('success', 'Trend posted successfully!');
    }

    public function search(Request $request)
    {
        $q = $request->query('q', '');

        if (empty($q)) {
            return response()->json([]);
        }

        try {
            $events = Event::where('event_name', 'like', "%{$q}%")
                ->take(5)
                ->get(['id', 'event_name']);

            // Return as { id, title } so your JS still works
            $events = $events->map(fn($e) => ['id' => $e->id, 'title' => $e->event_name]);

            return response()->json($events);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }





    public function update(Request $request, Trend $trend)
    {
        $this->authorize('update', $trend);

        $request->validate([
            'title' => 'required|max:255',
            'body' => 'nullable',
            'image' => 'image|max:2048'
        ]);

        if ($request->hasFile('image')) {
            if ($trend->image)
                Storage::disk('public')->delete($trend->image);
            $trend->image = $request->file('image')->store('trends', 'public');
        }

        $trend->update([
            'title' => $request->title,
            'body' => $request->body,
        ]);

        return redirect()->route('user.dashboard.trends')->with('success', 'Trend updated!');
    }

    public function destroy(Trend $trend)
    {
        $this->authorize('delete', $trend);

        if ($trend->image)
            Storage::disk('public')->delete($trend->image);
        $trend->delete();

        return redirect()->route('user.dashboard.trends')->with('success', 'Trend deleted.');
    }





    public function show(Trend $trend)
    {
        $trend->load([
            'user',
            'likes',
            'comments.user',
            'comments.likes',
        ])->loadCount('likes'); // <-- ensures likes_count is populated
   

        $topOrganizers = Organizer::withCount('followers')
            ->orderByDesc('followers_count')
            ->take(5)
            ->get();

        $randomTrends = Trend::inRandomOrder()->take(4)->get();

        // Check if current user liked THIS trend
        $trend->is_liked = Auth::check()
            ? $trend->likes->contains('user_id', Auth::id())
            : false;

        return view('singleTrend', compact('trend', 'topOrganizers', 'randomTrends'));
    }



    // Toggle like/unlike
    public function toggleLike(Request $request, Trend $trend)
    {
        if (!auth()->check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $user = auth()->user();

        $existingLike = $trend->likes()
            ->where('user_id', $user->id)
            ->first();

        if ($existingLike) {
            $existingLike->delete();
            $liked = false;
        } else {
            $trend->likes()->create(['user_id' => $user->id]);
            $liked = true;
        }

        return response()->json([
            'liked' => $liked,
            'likes_count' => $trend->likes()->count(),
        ]);
    }


    // Store a comment (AJAX)
    public function comment(Request $request, Trend $trend)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $request->validate([
            'comment' => 'required|string|max:500',
        ]);

        $comment = $trend->comments()->create([
            'user_id' => Auth::id(),
            'comment' => $request->comment,
        ]);

        $user = Auth::user();

        return response()->json([
            'id' => $comment->id,
            'comment' => $comment->comment,
            'user_name' => $user->first_name . ' ' . $user->last_name,
            'user_photo' => $user->profile_pic
                ? asset('storage/' . $user->profile_pic)
                : asset('default-profile.png'),
            'created_at' => 'just now',
        ]);
    }



    public function likeComment(TrendComment $comment)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $user = Auth::user();

        if ($comment->likes()->where('user_id', $user->id)->exists()) {
            $comment->likes()->detach($user->id);
            $liked = false;
        } else {
            $comment->likes()->attach($user->id);
            $liked = true;
        }

        return response()->json([
            'liked' => $liked,
            'likes_count' => $comment->likes()->count(),
        ]);
    }


    public function deleteComment(TrendComment $comment)
    {
        if (!Auth::check() || $comment->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $comment->delete();

        return response()->json(['success' => true]);
    }




}
