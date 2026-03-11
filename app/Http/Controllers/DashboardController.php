<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Ticket;
use App\Models\Trend;
use App\Models\Organizer;
use App\Models\Faq;
use App\Models\Experience;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class DashboardController extends Controller
{
    use AuthorizesRequests;
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

        // Random Trends for Dashboard (Carousel)
        $randomTrends = Trend::with('user')
            ->inRandomOrder()
            ->take(5)
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
            'topCategories',
            'randomTrends'
        ));
    }


    public function trends(Request $request)
    {
        $user = Auth::user();

        // Trends created by user (for management tab)
        $myTrends = Trend::withCount('likes')
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        // Feed Trends (Followed + Popular)
        $followedIds = $user->followedOrganizers()->pluck('organizers.id');
        
        $trendsQuery = Trend::with(['user', 'likes', 'comments'])
            ->withCount('likes', 'comments')
            ->latest();

        if ($request->filled('search')) {
            $trendsQuery->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('body', 'like', '%' . $request->search . '%');
            });
        }

        $trends = $trendsQuery->paginate(5);

        // Sidebar: Top Creators
        $topCreators = \App\Models\User::withCount('trends')
            ->orderByDesc('trends_count')
            ->take(6)
            ->get();

        // Map liked state
        if ($user) {
            $userLikedTrendIds = \App\Models\TrendLike::where('user_id', $user->id)
                ->pluck('trend_id')
                ->toArray();
            
            foreach ($trends as $trend) {
                $trend->is_liked = in_array($trend->id, $userLikedTrendIds);
            }
        }

        if ($request->ajax()) {
            $view = view('partials.trend-feed-card', compact('trends'))->render();
            return response()->json([
                'html' => $view,
                'next_page' => $trends->nextPageUrl()
            ]);
        }

        // For internal sharing: Users this user matches with/follows
        $friends = $user->following()->with('following')->get()->pluck('following');

        return view('dashboard.trends', compact(
            'user',
            'trends',
            'myTrends',
            'myTrends',
            'friends',
            'topCreators'
        ));
    }

    public function sharePost(Request $request)
    {
        $request->validate([
            'trend_id' => 'required|exists:trends,id',
            'friend_id' => 'required|exists:users,id',
        ]);

        $trend = Trend::find($request->trend_id);
        $user = Auth::user();

        \App\Models\Notification::create([
            'user_id' => $request->friend_id,
            'title' => 'Post Shared with You',
            'message' => "{$user->first_name} shared a trend: \"{$trend->title}\"",
            'type' => 'info'
        ]);

        return response()->json(['success' => true]);
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

        $experiences = Experience::with(['views.viewer'])
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        $activeExperiences = $experiences->filter(fn($exp) => $exp->expires_at && $exp->expires_at->isFuture());

        return view('dashboard.profile', compact(
            'user',
            'stats',
            'followedOrganizersEvents',
            'latestTrends',
            'experiences',
            'activeExperiences'
        ));
    }

    public function events(Request $request)
    {
        $user = Auth::user();
        $organizer = $user->organizer()->with('events')->first();

        // Organized events for management tab
        $organizedEvents = collect();
        if ($organizer) {
            $organizedEvents = $organizer->events()->latest()->get();
        }

        // Discovery feed events
        $eventsQuery = Event::with('organizer');

        if ($request->filled('search')) {
            $eventsQuery->where(function ($q) use ($request) {
                $q->where('event_name', 'like', '%' . $request->search . '%')
                    ->orWhere('location', 'like', '%' . $request->search . '%')
                    ->orWhere('venue', 'like', '%' . $request->search . '%');
            });
        }

        $events = $eventsQuery->latest()->paginate(6);

        // Saved events
        $saved = $user->savedEvents()
            ->with('event.organizer')
            ->latest()
            ->get();

        // Get random organizers for sidebar (Increased to 7 as requested)
        $randomOrganizers = \App\Models\Organizer::inRandomOrder()->take(6)->get();

        // Get top trending trends for sidebar
        $trendRankingService = app(\App\Services\TrendRankingService::class);
        $topTrends = $trendRankingService->getTrendingFeed(5);

        if ($request->ajax()) {
            $view = view('partials.event-dashboard-card', compact('events'))->render();
            return response()->json([
                'html' => $view,
                'next_page' => $events->nextPageUrl()
            ]);
        }

        return view('dashboard.events', compact(
            'events',
            'user',
            'saved',
            'organizer',
            'organizedEvents',
            'randomOrganizers',
            'topTrends'
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

    public function calendar(Request $request)
    {
        $user = Auth::user();

        $month = (int) $request->get('month', now()->month);
        $year  = (int) $request->get('year',  now()->year);

        // Clamp month/year to valid values
        if ($month < 1 || $month > 12) { $month = now()->month; }
        if ($year < 2000 || $year > 2100) { $year = now()->year; }

        // Events the user has purchased tickets for
        $ticketEventIds = Ticket::whereHas('purchase', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->pluck('event_id')->unique();

        $ticketEvents = Event::with('organizer')
            ->whereIn('id', $ticketEventIds)
            ->whereYear('event_date', $year)
            ->whereMonth('event_date', $month)
            ->get()
            ->map(fn($e) => array_merge($e->toArray(), ['source' => 'ticket']));

        // Events the user has saved
        $savedEventIds = $user->savedEvents()->pluck('event_id');

        $savedEvents = Event::with('organizer')
            ->whereIn('id', $savedEventIds)
            ->whereYear('event_date', $year)
            ->whereMonth('event_date', $month)
            ->get()
            ->map(fn($e) => array_merge($e->toArray(), ['source' => 'saved']));

        // Merge: ticket events take priority over saved-only
        $allEvents = $ticketEvents->keyBy('id')
            ->merge($savedEvents->keyBy('id'))
            ->values();

        // Re-apply 'ticket' source if it was in both
        $allEvents = $allEvents->map(function ($e) use ($ticketEventIds) {
            if (in_array($e['id'], $ticketEventIds->toArray())) {
                $e['source'] = 'ticket';
            }
            return $e;
        });

        return view('dashboard.calendar', compact('user', 'allEvents', 'month', 'year'));
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

    // NOTIFICATIONS
    public function notifications()
    {
        $notifications = auth()->user()->notifications()
            ->orderBy('read_at', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('dashboard.notifications', compact('notifications'));
    }

    public function markAsRead(Notification $notification)
    {
        if ($notification->user_id !== auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $notification->update(['read_at' => now()]);

        return response()->json(['success' => true]);
    }

    public function markAllAsRead()
    {
        auth()->user()->notifications()->whereNull('read_at')->update(['read_at' => now()]);

        return back()->with('success', 'All notifications marked as read.');
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

    public function organizerSettings()
    {
        $user = Auth::user();
        $organizer = $user->organizer;

        if (!$organizer) {
            return redirect()->route('organizer.create')->with('error', 'You need an organizer profile to view settings.');
        }

        return view('dashboard.organizer-settings', compact('organizer', 'user'));
    }

    public function updateOrganizerSettings(Request $request)
    {
        $user = Auth::user();
        $organizer = $user->organizer;

        if (!$organizer) {
            return response()->json(['success' => false, 'message' => 'Organizer not found.'], 404);
        }

        $validated = $request->validate([
            // Ticket Defaults
            'default_ticket_price' => 'nullable|integer|min:0',
            'default_ticket_quantity' => 'nullable|integer|min:0',
            'default_ticket_type' => 'nullable|string|max:255',

            // Payout Settings
            'payout_mobile_money_number' => 'nullable|string|max:20',
            'payout_bank_name' => 'nullable|string|max:255',
            'payout_account_number' => 'nullable|string|max:50',
            'payout_account_name' => 'nullable|string|max:255',

            // Page Access
            'authorized_emails' => 'nullable|string',

            // Communication details
            'contact_email' => 'nullable|email|max:255',
            'contact_number' => 'nullable|string|max:20',
            'show_logo_in_ticket' => 'nullable|boolean',

            // New Production Level Fields
            'description' => 'nullable|string',
            'facebook_url' => 'nullable|url|max:255',
            'instagram_url' => 'nullable|url|max:255',
            'twitter_url' => 'nullable|url|max:255',
            'linkedin_url' => 'nullable|url|max:255',
            'ticket_instructions' => 'nullable|string',
            'payout_frequency' => 'nullable|in:daily,weekly,monthly',
            'tax_id' => 'nullable|string|max:50',
            'google_analytics_id' => 'nullable|string|max:50',
            'facebook_pixel_id' => 'nullable|string|max:50',

            // Custom Branding (Elite/Pro)
            'ticket_custom_background' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'ticket_custom_logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Handle Branding Files
        if ($request->hasFile('ticket_custom_background')) {
            if ($organizer->ticket_custom_background) {
                Storage::disk('public')->delete($organizer->ticket_custom_background);
            }
            $validated['ticket_custom_background'] = $request->file('ticket_custom_background')->store('branding', 'public');
        }

        if ($request->hasFile('ticket_custom_logo')) {
            if ($organizer->ticket_custom_logo) {
                Storage::disk('public')->delete($organizer->ticket_custom_logo);
            }
            $validated['ticket_custom_logo'] = $request->file('ticket_custom_logo')->store('branding', 'public');
        }

        // Specific handling for boolean toggle if not present in request (though checkbox should be fine)
        $validated['show_logo_in_ticket'] = $request->has('show_logo_in_ticket') ? true : false;

        $organizer->update($validated);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Settings updated successfully!']);
        }

        return back()->with('success', 'Settings updated successfully!');
    }

    /* ===================== ORGANIZER TOOLS ===================== */

    /**
     * Retargeting: Show users who liked but didn't buy
     */
    public function retargeting(Event $event)
    {
        $this->authorize('update', $event); // Check if owner

        $leads = \App\Models\User::whereHas('likes', function ($q) use ($event) {
            $q->where('event_id', $event->id);
        })
            ->whereDoesntHave('ticketPurchases', function ($q) use ($event) {
                $q->where('event_id', $event->id)
                    ->where('status', 'paid');
            })
            ->paginate(15);

        return view('dashboard.retargeting', compact('event', 'leads'));
    }

    /**
     * Send direct email offer to a lead
     */
    public function sendOffer(Request $request, Event $event)
    {
        $this->authorize('update', $event);

        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $user = \App\Models\User::findOrFail($request->user_id);

        \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\SpecialOfferMail($event, $user));

        return response()->json(['success' => true, 'message' => 'Offer sent successfully!']);
    }

    /**
     * Paid Booster: Accelerate visibility
     */
    public function toggleBoost(Event $event)
    {
        $this->authorize('update', $event);

        $event->is_boosted = !$event->is_boosted;
        $event->boosted_until = $event->is_boosted ? now()->addDays(7) : null;
        $event->save();

        $status = $event->is_boosted ? 'boosted' : 'deactivated';

        return back()->with('success', "Event visibility booster {$status} successfully!");
    }
}
