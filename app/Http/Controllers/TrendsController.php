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

    protected $rankingService;

    public function __construct(\App\Services\TrendRankingService $rankingService)
    {
        $this->rankingService = $rankingService;
    }

    public function index(Request $request)
    {
        $user = Auth::user();

        // --- RESTORED FOR VIEW COMPATIBILITY ---
        $topCreators = User::withCount('trends')
            ->orderByDesc('trends_count')
            ->take(7)
            ->get();

        $topTrends = Trend::with('user')
            ->withCount('likes')
            ->orderByDesc('likes_count')
            ->take(3)
            ->get();

        $topEvent = Event::with('likes')
            ->withCount('likes')
            ->orderByDesc('likes_count')
            ->first();

        // Paginated organic trends for the bottom section
        $trends = Trend::with(['user', 'media'])
            ->withCount('likes')
            ->latest()
            ->paginate(4);

        // --- NEW MONETIZATION FEEDS (For future use/demonstration) ---
        // 1. 🔥 Trending Near You
        $location = $request->query('location', ($user ? $user->location : 'Kampala')); 
        $trendingNear = Trend::with(['user', 'event'])
            ->trendingNear($location)
            ->activeBoost()
            ->latest()
            ->take(5)
            ->get();

        // 2. Editor’s Pick
        $editorsPicks = Trend::with(['user', 'event'])
            ->editorsPick()
            ->latest()
            ->take(5)
            ->get();

        // 3. Personalized feed
        $personalized = collect();
        if ($user) {
            $likedEventCategories = $user->likes()
                ->with('event')
                ->get()
                ->pluck('event.category')
                ->unique();
            
            $personalized = Trend::with(['user', 'event'])
                ->whereHas('event', function($q) use ($likedEventCategories) {
                    $q->whereIn('category', $likedEventCategories);
                })
                ->where('user_id', '!=', $user->id)
                ->latest()
                ->take(5)
                ->get();
        }

        // Map liked state for all feeds if user is logged in
        if ($user) {
            $userLikedTrendIds = TrendLike::where('user_id', $user->id)
                ->pluck('trend_id')
                ->toArray();
            
            $feedsToMap = [$topTrends, $trends, $trendingNear, $editorsPicks, $personalized];
            foreach ($feedsToMap as $feed) {
                foreach ($feed as $trend) {
                    $trend->is_liked = in_array($trend->id, $userLikedTrendIds);
                }
            }
        }

        return view('trends', [
            'trends' => $trends,
            'topTrends' => $topTrends,
            'topCreators' => $topCreators,
            'topEvent' => $topEvent,
            'trendingNear' => $trendingNear,
            'editorsPicks' => $editorsPicks,
            'personalized' => $personalized,
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
            'media.*' => 'nullable|file|mimes:jpg,jpeg,png,mp4,mov,avi,wmv,webm|max:51200', // 50MB max
            'media' => 'nullable|array|max:5',
            'event_id' => 'nullable|exists:events,id',
        ]);

        // Create trend
        $trend = Trend::create([
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'body' => $validated['body'],
            'event_id' => $validated['event_id'] ?? null,
        ]);

        // Handle multiple media uploads
        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $index => $file) {
                $path = $file->store('trends', 'public');
                $type = str_contains($file->getMimeType(), 'video') ? 'video' : 'image';
                
                $trend->media()->create([
                    'path' => $path,
                    'type' => $type,
                    'order' => $index,
                ]);
            }
        }

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
            'media.*' => 'nullable|file|mimes:jpg,jpeg,png,mp4,mov,avi,wmv,webm|max:51200',
            'media' => 'nullable|array|max:5',
        ]);

        if ($request->hasFile('media')) {
            // Delete old media files and records
            foreach ($trend->media as $oldMedia) {
                Storage::disk('public')->delete($oldMedia->path);
                $oldMedia->delete();
            }

            // Upload new media
            foreach ($request->file('media') as $index => $file) {
                $path = $file->store('trends', 'public');
                $type = str_contains($file->getMimeType(), 'video') ? 'video' : 'image';
                
                $trend->media()->create([
                    'path' => $path,
                    'type' => $type,
                    'order' => $index,
                ]);
            }
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
            'media',
            'likes',
            'comments.user',
            'comments.likes',
        ])->loadCount('likes'); // <-- ensures likes_count is populated
   

        $topOrganizers = Organizer::withCount('followers')
            ->orderByDesc('followers_count')
            ->take(5)
            ->get();

        $randomTrends = Trend::inRandomOrder()->take(5)->get();

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
