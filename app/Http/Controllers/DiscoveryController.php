<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Organizer;
use App\Models\Trend;
use App\Models\User;
use Illuminate\Http\Request;

class DiscoveryController extends Controller
{
    public function index(Request $request)
    {
        $q = trim($request->query('q', ''));
        $location = trim($request->query('location', 'Kampala'));

        $events = collect();
        $organizers = collect();
        $trends = collect();
        $users = collect();

        if ($q !== '') {
            $events = Event::with('organizer')
                ->where(function ($query) use ($q) {
                    $query->where('event_name', 'like', "%{$q}%")
                        ->orWhere('location', 'like', "%{$q}%")
                        ->orWhere('venue', 'like', "%{$q}%")
                        ->orWhere('description', 'like', "%{$q}%");
                })
                ->latest()
                ->take(12)
                ->get();

            $organizers = Organizer::where(function ($query) use ($q) {
                    $query->where('business_name', 'like', "%{$q}%")
                        ->orWhere('business_email', 'like', "%{$q}%");
                })
                ->withCount('events')
                ->take(12)
                ->get();

            $trends = Trend::with('user')
                ->where(function ($query) use ($q) {
                    $query->where('title', 'like', "%{$q}%")
                        ->orWhere('body', 'like', "%{$q}%");
                })
                ->latest()
                ->take(12)
                ->get();

            $users = User::where(function ($query) use ($q) {
                    $query->where('first_name', 'like', "%{$q}%")
                        ->orWhere('last_name', 'like', "%{$q}%")
                        ->orWhere('username', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%");
                })
                ->take(12)
                ->get();
        }

        $nearbyEvents = Event::with('organizer')
            ->where('location', 'like', "%{$location}%")
            ->whereDate('event_date', '>=', now())
            ->orderBy('event_date')
            ->take(8)
            ->get();

        $recommendedEvents = collect();
        if ($request->user()) {
            $user = $request->user();
            $likedCategories = $user->likes()->with('event')->get()->pluck('event.category')->filter();
            $savedCategories = $user->savedEvents()->with('event')->get()->pluck('event.category')->filter();
            $ticketCategories = $user->ticketPurchases()->with('event')->get()->pluck('event.category')->filter();

            $categories = $likedCategories
                ->merge($savedCategories)
                ->merge($ticketCategories)
                ->unique()
                ->values();

            $recommendedEvents = Event::with('organizer')
                ->whereDate('event_date', '>=', now())
                ->when($categories->isNotEmpty(), function ($query) use ($categories) {
                    $query->whereIn('category', $categories);
                })
                ->withCount('likes')
                ->orderByDesc('likes_count')
                ->take(8)
                ->get();
        }

        return view('discover', [
            'query' => $q,
            'location' => $location,
            'events' => $events,
            'organizers' => $organizers,
            'trends' => $trends,
            'users' => $users,
            'nearbyEvents' => $nearbyEvents,
            'recommendedEvents' => $recommendedEvents,
        ]);
    }
}
