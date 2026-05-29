<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class DiscoveryController extends Controller
{
    public function index(Request $request)
    {
        $q = trim($request->query('q', ''));
        $location = trim($request->query('location', ''));

        $isSearching = $q !== '' || $location !== '';
        $events = collect();
        $categories = collect();

        if ($isSearching) {
            $eventsQuery = Event::with('organizer')
                ->whereDate('event_date', '>=', now());

            if ($q !== '') {
                $eventsQuery->where(function ($query) use ($q) {
                    $query->where('event_name', 'like', "%{$q}%")
                        ->orWhere('location', 'like', "%{$q}%")
                        ->orWhere('venue', 'like', "%{$q}%")
                        ->orWhere('description', 'like', "%{$q}%");
                });
            }

            if ($location !== '') {
                $eventsQuery->where(function ($query) use ($location) {
                    $query->where('location', 'like', "%{$location}%")
                        ->orWhere('venue', 'like', "%{$location}%");
                });
            }

            $events = $eventsQuery
                ->orderBy('event_date')
                ->get();

            if ($request->ajax()) {
                $view = view('partials.discover-search-results', [
                    'events' => $events,
                ])->render();

                return response()->json([
                    'html' => $view,
                ]);
            }
        } else {
            $events = Event::with('organizer')
                ->whereDate('event_date', '>=', now())
                ->orderBy('event_date')
                ->get();

            $eventsByCategory = $events->groupBy(function ($event) {
                return $event->category ?: 'Other';
            });

            $categories = $eventsByCategory->map(function ($groupedEvents, $categoryName) {
                return [
                    'name' => $categoryName,
                    'events' => $groupedEvents,
                ];
            })->values();
        }

        return view('discover', [
            'query' => $q,
            'location' => $location,
            'categories' => $categories,
            'events' => $events,
            'isSearching' => $isSearching,
        ]);
    }
}
