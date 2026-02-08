<?php

namespace App\Http\Controllers;


use Carbon\Carbon;
use App\Models\Event;
use App\Models\Organizer;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Storage;

// ✅ Send confirmation email with the ticket
use App\Mail\TicketPurchased;
use Illuminate\Support\Facades\Mail;

class EventsController extends Controller
{

    use AuthorizesRequests;
    //
    public function index(Request $request)
    {
        // Base query
        $query = Event::query();

        // Get the event with the most likes (top event)
        $topEvent = Event::withCount('likes')
            ->with([
                'organizer' => function ($query) {
                    $query->withCount('events'); // eager load organizer with events count
                }
            ])
            ->orderByDesc('likes_count')
            ->first();


        // Search by name or location
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('event_name', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Load events with organizer and likes
        $events = $query->with(['organizer', 'likes'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        if ($request->ajax()) {
            return view('partials.events-grid', compact('events'))->render();
        }

        // Get all categories
        $categories = Event::select('category')->distinct()->pluck('category');

        return view('events', [
            'events' => $events,
            'categories' => $categories,
            'event' => $topEvent
        ]);
    }

    public function categoryPage(Request $request, $category)
    {
        $query = Event::query()->where('category', $category);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('event_name', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%");
            });
        }

        $events = $query->with(['organizer', 'likes'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        if ($request->ajax()) {
            return view('partials.events-grid', compact('events'))->render();
        }

        $categories = Event::select('category')->distinct()->pluck('category');

        return view('categories', [
            'events' => $events,
            'category' => $category,
            'categories' => $categories
        ]);
    }


    public function singleEvent($id)
    {

        $event = Event::with('organizer')->findOrFail($id);

        return view('singleEvent', ['event' => $event]);
    }

    public function create_event()
    {
        $user = auth()->user();

        $organizer = Organizer::where('user_id', $user->id)->first();

        $topOrganizers = Organizer::withCount('events')
            ->orderByDesc('events_count')
            ->take(4)
            ->get();
        return view('create_event', [
            'organizerName' => $organizer?->business_name ?? null,
            'topOrganizers' => $topOrganizers
        ]);

    }


    public function store(Request $request)
    {
        $user = auth()->user();
        $organizer = Organizer::where('user_id', $user->id)->first();

        $validated = $request->validate([
            'event_name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'venue' => 'required|string|max:255',
            'event_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'description' => 'nullable|string',
            'event_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'regular_price' => 'nullable|numeric|min:0',
            'regular_quantity' => 'nullable|integer|min:0',
            'vip_price' => 'nullable|numeric|min:0',
            'vip_quantity' => 'nullable|integer|min:0',
            'vvip_price' => 'nullable|numeric|min:0',
            'vvip_quantity' => 'nullable|integer|min:0',
        ]);

        $user = auth()->user();
        $organizer = Organizer::where('user_id', $user->id)->first();

        if (!$organizer) {
            return redirect()->back()->withErrors(['organizer' => 'You must have an organizer profile to create events.']);
        }

        $validated['organizer_id'] = $organizer->id;
        $validated['user_id'] = $user->id;
        $validated['start_time'] = \Carbon\Carbon::createFromFormat('H:i', $request->start_time)->format('H:i:s');
        $validated['end_time'] = \Carbon\Carbon::createFromFormat('H:i', $request->end_time)->format('H:i:s');

        if ($request->hasFile('event_image')) {
            $validated['event_image'] = $request->file('event_image')->store('events', 'public');
        }

        Event::create($validated);


        return redirect()->route('events.create')->with('success', 'Event created successfully!');
    }


    public function update(Request $request, Event $event)
    {
        $request->validate([
            'event_name' => 'required|max:255',
            'category' => 'nullable|max:255',
            'location' => 'nullable|max:255',
            'venue' => 'nullable|max:255',
            'event_date' => 'nullable|date',
            'start_time' => 'nullable',
            'end_time' => 'nullable',
            'description' => 'nullable|string',
            'event_image' => 'nullable|image|max:2048',
            'regular_price' => 'nullable|numeric|min:0',
            'regular_quantity' => 'nullable|integer|min:0',
            'vip_price' => 'nullable|numeric|min:0',
            'vip_quantity' => 'nullable|integer|min:0',
            'vvip_price' => 'nullable|numeric|min:0',
            'vvip_quantity' => 'nullable|integer|min:0',
        ]);

        // Handle image upload
        if ($request->hasFile('event_image')) {
            if ($event->event_image) {
                Storage::disk('public')->delete($event->event_image);
            }
            $event->event_image = $request->file('event_image')->store('events', 'public');
        }

        // Update all fields
        $event->update([
            'event_name' => $request->event_name,
            'category' => $request->category,
            'location' => $request->location,
            'venue' => $request->venue,
            'event_date' => $request->event_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'description' => $request->description,
            'regular_price' => $request->regular_price,
            'regular_quantity' => $request->regular_quantity,
            'vip_price' => $request->vip_price,
            'vip_quantity' => $request->vip_quantity,
            'vvip_price' => $request->vvip_price,
            'vvip_quantity' => $request->vvip_quantity,
            'event_image' => $event->event_image, // updated image path if any
        ]);

        return redirect()->route('user.dashboard.events')->with('success', 'Event updated successfully!');
    }


    public function destroy(Event $event)
    {
        if ($event->event_image) {
            Storage::disk('public')->delete($event->event_image);
        }

        $event->delete();
        return redirect()->route('user.dashboard.events')->with('success', 'Event deleted!');
    }

    public function eventsByDate(Request $request)
    {
        $startDate = $request->query('start');
        $endDate = $request->query('end');

        if (!$startDate) {
            abort(404, 'No date provided');
        }

        $endDate = $endDate ?? $startDate;

        $events = Event::whereDate('event_date', '>=', $startDate)
            ->whereDate('event_date', '<=', $endDate)
            ->get();

        return view('events.byDate', [
            'events' => $events,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }

    public function byDate(Request $request)
    {
        $type = $request->query('type', 'tomorrow');
        $today = Carbon::today();

        switch ($type) {
            case 'tomorrow':
                $start = $today->copy()->addDay();
                $end = $start;
                $label = 'Tomorrow';
                break;

            case 'week':
                $start = $today->copy()->startOfWeek(Carbon::MONDAY);
                $end = $today->copy()->endOfWeek(Carbon::SUNDAY);
                $label = "Week ({$start->format('M d')} - {$end->format('M d')})";
                break;

            case 'weekend':
                $start = $today->copy()->next(Carbon::SATURDAY);
                $end = $today->copy()->next(Carbon::SUNDAY);
                $label = "Weekend ({$start->format('M d')} - {$end->format('M d')})";
                break;

            case 'month':
                $start = $today->copy()->addMonth()->startOfMonth();
                $end = $today->copy()->addMonth()->endOfMonth();
                $label = "Next Month ({$start->format('M d')} - {$end->format('M d')})";
                break;

            default:
                $start = $today;
                $end = $today;
                $label = 'Events';
        }

        $events = Event::whereDate('event_date', '>=', $start->toDateString())
            ->whereDate('event_date', '<=', $end->toDateString())
            ->get();

        return view('byDate', ['events' => $events, 'label' => $label]);
    }
  






}
