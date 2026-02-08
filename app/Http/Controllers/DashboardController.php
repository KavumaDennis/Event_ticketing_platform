<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Ticket;
use App\Models\Trend;
use App\Models\Organizer;
use App\Models\Faq;
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

        // Recommendations (simple logic: popular events near future)
        $recommendations = Event::with('organizer')
            ->withCount('likes')
            ->where('event_date', '>=', now())
            ->orderByDesc('likes_count')
            ->take(4)
            ->get();

        // Recent Tickets
        $recentTickets = Ticket::whereHas('purchase', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })
        ->with(['event'])
        ->latest()
        ->take(3)
        ->get();

        // Top Categories (derived from events)
        $topCategories = Event::select('category', \DB::raw('count(*) as event_count'))
            ->groupBy('category')
            ->orderByDesc('event_count')
            ->take(6)
            ->get();

        return view('dashboard.overview', compact(
            'user',
            'trends',
            'followedOrganizers',
            'saved',
            'topOrganizers',
            'recommendations',
            'recentTickets',
            'topCategories'
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

        $followedOrganizersIds = $user->followedOrganizers()->pluck('organizers.id');

        $followedOrganizersEvents = Event::whereIn('organizer_id', $followedOrganizersIds)
            ->with('organizer')
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
            'followedOrganizersEvents',
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

        // Use a more direct query to avoid potential hasManyThrough attribute collisions
        $allTickets = Ticket::whereHas('purchase', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })
        ->with(['event.organizer', 'purchase'])
        ->latest()
        ->get();

        $upcoming = $allTickets->filter(function($t) {
            return \Carbon\Carbon::parse($t->event->event_date)->isFuture();
        });

        $past = $allTickets->filter(function($t) {
            return \Carbon\Carbon::parse($t->event->event_date)->isPast();
        });

        // Current schema doesn't have a status on Ticket model, 
        // but we can check the purchase status.
        $cancelled = $allTickets->filter(function($t) {
            return $t->purchase->status === 'failed';
        });

        return view('dashboard.tickets', compact('user', 'upcoming', 'past', 'cancelled'));
    }

    public function viewTicket(Ticket $ticket)
    {
        $user = Auth::user();

        if ($ticket->purchase->user_id !== Auth::id()) {
            abort(403);
        }

        return view('dashboard.ticket-view', compact('ticket', 'user'));
    }





    public function myReviews()
    {
        $user = Auth::user();
        
        // Past tickets for events not yet reviewed by this user
        $pastTickets = $user->tickets()
            ->whereHas('event', function($q) {
                $q->where('event_date', '<', now());
            })
            ->with('event')
            ->get();

        $reviewedEventIds = \App\Models\Review::where('user_id', $user->id)->pluck('event_id')->toArray();
        
        $toReview = $pastTickets->filter(function($t) use ($reviewedEventIds) {
            return !in_array($t->event_id, $reviewedEventIds);
        });

        $myReviews = \App\Models\Review::where('user_id', $user->id)->with('event')->latest()->get();

        return view('dashboard.reviews', compact('user', 'toReview', 'myReviews'));
    }

    public function notifications()
    {
        $user = Auth::user();
        $notifications = $user->notifications()->latest()->get();
        return view('dashboard.notifications', compact('user', 'notifications'));
    }



    public function organizerProfile()
    {
        $user = Auth::user();
        $organizer = $user->organizer; 
        return view('dashboard.organizer-profile', compact('organizer', 'user'));
    }

    public function following()
    {
        $user = Auth::user();
        $following = $user->followedOrganizers()->withCount('followers')->get();
        return view('dashboard.following', compact('following', 'user'));
    }

    public function followers()
    {
        $user = Auth::user();
        $organizer = $user->organizer;
        
        $followers = $organizer ? $organizer->followers()->latest()->get() : collect([]);

        return view('dashboard.followers', compact('followers', 'organizer', 'user'));
    }

    public function security()
    {
        $user = Auth::user();
        return view('dashboard.security', compact('user'));
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();
        $user->update([
            'password' => \Hash::make($request->password),
        ]);

        return back()->with('success', 'Password updated successfully!');
    }

    public function support()
    {
        $user = Auth::user();
        return view('dashboard.support', compact('user'));
    }

}
