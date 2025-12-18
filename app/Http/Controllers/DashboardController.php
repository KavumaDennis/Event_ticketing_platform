<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Ticket;
use App\Models\Trend;
use App\Models\Organizer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    // USER DASHBOARD OVERVIEW
    public function overview()
    {
        $user = Auth::user();

        // Trends created by user
        $trends = Trend::withCount('likes')
            ->where('user_id', $user->id)
            ->latest()
            ->take(8)
            ->get();

        // Organizers user follows
        $followedOrganizers = $user->followedOrganizers()
            ->withCount('followers')
            ->get();

        // Saved events
        $saved = $user->savedEvents()
            ->with('event.organizer')
            ->get();

        // Sidebar: top organizers globally
        $topOrganizers = Organizer::withCount('followers')
            ->orderByDesc('followers_count')
            ->take(4)
            ->get();

        return view('dashboard.overview', compact(
            'user',
            'trends',
            'followedOrganizers',
            'saved',
            'topOrganizers'
        ));
    }


    public function trends()
    {
        $user = Auth::user();

        // Trends created by user
        $trends = Trend::withCount('likes')
            ->where('user_id', $user->id)
            ->latest()
            ->take(8)
            ->get();

        // Organizers user follows
        $followedOrganizers = $user->followedOrganizers()
            ->withCount('followers')
            ->get();

        // Saved events
        $saved = $user->savedEvents()
            ->with('event.organizer')
            ->get();

        // Sidebar: top organizers globally
        $topOrganizers = Organizer::withCount('followers')
            ->orderByDesc('followers_count')
            ->take(4)
            ->get();

        return view('dashboard.trends', compact(
            'user',
            'trends',
            'followedOrganizers',
            'saved',
            'topOrganizers'
        ));
    }



    // CARDS VIEW
    public function profile()
    {
        $user = Auth::user();

        $stats = [
            'events_count' => Event::whereIn(
                'organizer_id',
                $user->followedOrganizers()->pluck('organizer_id')
            )->count(),

            'trends_count' => Trend::where('user_id', $user->id)->count(),

            'followers_count' => $user->followedOrganizers()->count(),

            'saved_count' => $user->savedEvents()->count(),
        ];

        $latestEvents = Event::with('organizer')
            ->latest()
            ->take(8)
            ->get();

        $latestTrends = Trend::withCount('likes')
            ->latest()
            ->take(8)
            ->get();

        return view('dashboard.profile', compact(
            'user',
            'stats',
            'latestEvents',
            'latestTrends'
        ));
    }

    public function events()
    {
        $user = Auth::user();

        // Events from organizers the user follows
        $followedOrganizerIds = $user->followedOrganizers()->pluck('organizer_id');

        $events = Event::whereIn('organizer_id', $followedOrganizerIds)
            ->with('organizer', 'likes')
            ->latest()
            ->take(8)
            ->get();

        // Saved events
        $saved = $user->savedEvents()
            ->with('event.organizer')
            ->get();

        // Logged-in user's organizer profile + events
        $organizer = $user->organizer()->with('events')->first();

        return view('dashboard.events', compact(
            'events',
            'user',
            'saved',
            'organizer'
        ));
    }



    // AJAX: Like/unlike Trend
    public function toggleTrendLike(Request $request, Trend $trend)
    {
        $user = $request->user();
        if (!$user)
            return response()->json(['error' => 'Unauthorized'], 401);

        $exists = $trend->likes()->where('user_id', $user->id)->first();

        if ($exists) {
            $exists->delete();
            $status = 'unliked';
        } else {
            $trend->likes()->create(['user_id' => $user->id]);
            $status = 'liked';
        }

        return response()->json([
            'status' => $status,
            'likes_count' => $trend->likes()->count()
        ]);
    }


    // AJAX: Follow/unfollow organizer
    public function toggleOrganizerFollow(Request $request, Organizer $organizer)
    {
        $user = $request->user();
        if (!$user)
            return response()->json(['error' => 'Unauthorized'], 401);

        $exists = $organizer->followers()->where('user_id', $user->id)->exists();

        if ($exists) {
            $organizer->followers()->detach($user->id);
            $status = 'unfollowed';
        } else {
            $organizer->followers()->attach($user->id);
            $status = 'followed';
        }

        return response()->json([
            'status' => $status,
            'followers_count' => $organizer->followers()->count()
        ]);
    }


    // AJAX: Save/Unsave Event
    public function toggleEventSave(Request $request, Event $event)
    {
        $user = $request->user();
        if (!$user)
            return response()->json(['error' => 'Unauthorized'], 401);

        $exists = $event->savedBy()->where('user_id', $user->id)->exists();

        if ($exists) {
            $event->savedBy()->detach($user->id);
            $status = 'unsaved';
        } else {
            $event->savedBy()->attach($user->id);
            $status = 'saved';
        }

        return response()->json(['status' => $status]);
    }


    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'username' => 'required|string|max:50|unique:users,username,' . $user->id,
            'bio' => 'nullable|string|max:1000',
            'profile_pic' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Handle profile picture upload
        if ($request->hasFile('profile_pic')) {
            // Delete old picture if exists
            if ($user->profile_pic && Storage::disk('public')->exists($user->profile_pic)) {
                Storage::disk('public')->delete($user->profile_pic);
            }

            $path = $request->file('profile_pic')->store('profile_pics', 'public');
            $user->profile_pic = $path;
        }

        // Update fields
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->username = $request->username;
        $user->bio = $request->bio;
        $user->save();

        return back()->with('success', 'Profile updated successfully!');
    }


    public function notifications()
    {
        $user = Auth::user();

        $notifications = $user->notifications()->latest()->get();

        return view('dashboard.notifications', compact('notifications'));
    }


    public function savedEvents()
    {
        $user = Auth::user();

        $saved = $user->savedEvents()
            ->with('event.organizer')
            ->get();

        return view('dashboard.saved', compact('saved', 'user'));
    }


    public function myOrders()
    {
        $user = Auth::user();

        $orders = $user->ticketPurchases()
            ->with(['event', 'tickets'])
            ->latest()
            ->get();

        return view('dashboard.orders', compact('orders', 'user'));
    }



    public function myTickets()
    {
        $user = Auth::user();

        $tickets = $user->tickets()
            ->with(['event.organizer'])
            ->latest()
            ->get();

        return view('dashboard.tickets', compact('tickets', 'user'));
    }

    public function viewTicket(Ticket $ticket)
    {
        $user = Auth::user();

        if ($ticket->purchase->user_id !== Auth::id()) {
            abort(403);
        }

        return view('dashboard.ticket-view', compact('ticket', 'user'));
    }





}
