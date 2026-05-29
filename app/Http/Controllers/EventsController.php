<?php

namespace App\Http\Controllers;


use Carbon\Carbon;
use App\Models\Event;
use App\Models\Organizer;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Support\ContentFormatter;

// ✅ Send confirmation email with the ticket
use App\Mail\TicketPurchased;
use Illuminate\Support\Facades\Mail;
use App\Models\TicketPurchase;

class EventsController extends Controller
{

    use AuthorizesRequests;
    //
    public function index(Request $request)
    {
        // Base query
        $query = Event::query();

        // Get the event with the most likes (top event)
        $now = now();
        $topEvent = Event::withCount('likes')
            ->with([
                'organizer' => function ($query) {
                    $query->withCount('events'); // eager load organizer with events count
                }
            ])
            ->where(function ($q) use ($now) {
                $q->whereDate('event_date', '>', $now->toDateString())
                    ->orWhere(function ($q) use ($now) {
                        $q->whereDate('event_date', $now->toDateString())
                            ->where(function ($q) use ($now) {
                                $q->whereNull('start_time')
                                    ->orWhere('start_time', '>', $now->format('H:i:s'));
                            });
                    });
            })
            ->orderByDesc('likes_count')
            ->first();

        if (! $topEvent) {
            $topEvent = Event::withCount('likes')
                ->with([
                    'organizer' => function ($query) {
                        $query->withCount('events');
                    }
                ])
                ->orderByDesc('likes_count')
                ->first();
        }

        if (! $topEvent) {
            $topEvent = Event::latest()->first();
        }


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

        $event = Event::with([
            'organizer',
            'rootComments.user',
            'rootComments.likes',
            'rootComments.replies.user',
            'rootComments.replies.likes',
        ])->findOrFail($id);

        $similarEvents = Event::with('organizer')
            ->where('category', $event->category)
            ->where('id', '!=', $event->id)
            ->whereDate('event_date', '>=', now())
            ->take(5)
            ->get();

        $buyerUserIds = TicketPurchase::where('event_id', $event->id)
            ->where('status', 'paid')
            ->pluck('user_id')
            ->unique()
            ->values();

        $alsoBoughtEvents = Event::with('organizer')
            ->whereIn('id', function ($query) use ($buyerUserIds) {
                $query->select('event_id')
                    ->from('ticket_purchases')
                    ->whereIn('user_id', $buyerUserIds)
                    ->where('status', 'paid');
            })
            ->where('id', '!=', $event->id)
            ->take(5)
            ->get();

        // Record View (Optional: prevent multiple hits from same session if needed)
        \App\Models\EventView::create([
            'event_id' => $event->id,
            'ip_address' => request()->ip(),
        ]);

        return view('singleEvent', compact('event', 'similarEvents', 'alsoBoughtEvents'));
    }

    public function create_event()
    {
        $user = auth()->user();

        $organizer = Organizer::where('user_id', $user->id)->first();

        $topOrganizers = Organizer::withCount('events')
            ->orderByDesc('events_count')
            ->take(5)
            ->get();

        $categories = Event::whereNotNull('category')
            ->select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');
        return view('create_event', [
            'organizerName' => $organizer?->business_name ?? null,
            'topOrganizers' => $topOrganizers,
            'categories' => $categories
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
            'ticket_instructions' => 'nullable|string|max:1000',
            'media.*' => 'nullable|file|mimes:jpg,jpeg,png,mp4,mov,avi,wmv,webm|max:51200',
            'media' => 'nullable|array|max:5',
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

        $event = Event::create($validated);

        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $index => $file) {
                $path = $file->store('events', 'public');
                $mime = $file->getMimeType();
                $type = str_contains($mime, 'video') ? 'video' : 'image';
                $width = null;
                $height = null;
                $fullPath = storage_path('app/public/' . $path);

                if ($type === 'image') {
                    [$width, $height] = @getimagesize($fullPath);
                }

                if ($type === 'video') {
                    try {
                        $ffprobe = \FFMpeg\FFProbe::create();
                        $videoStream = $ffprobe->streams($fullPath)->videos()->first();
                        $width = $videoStream->get('width');
                        $height = $videoStream->get('height');
                    } catch (\Exception $e) {
                        $width = null;
                        $height = null;
                    }
                }

                $event->media()->create([
                    'path' => $path,
                    'type' => $type,
                    'order' => $index,
                    'width' => $width,
                    'height' => $height,
                ]);
            }
        }

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
            'ticket_instructions' => 'nullable|string|max:1000',
            'media.*' => 'nullable|file|mimes:jpg,jpeg,png,mp4,mov,avi,wmv,webm|max:51200',
            'media' => 'nullable|array|max:5',
            'regular_price' => 'nullable|numeric|min:0',
            'regular_quantity' => 'nullable|integer|min:0',
            'vip_price' => 'nullable|numeric|min:0',
            'vip_quantity' => 'nullable|integer|min:0',
            'vvip_price' => 'nullable|numeric|min:0',
            'vvip_quantity' => 'nullable|integer|min:0',
        ]);

        if ($request->hasFile('media')) {
            // Delete old media files and records
            foreach ($event->media as $oldMedia) {
                Storage::disk('public')->delete($oldMedia->path);
                $oldMedia->delete();
            }

            // Upload new media
            foreach ($request->file('media') as $index => $file) {
                $path = $file->store('events', 'public');
                $mime = $file->getMimeType();
                $type = str_contains($mime, 'video') ? 'video' : 'image';
                $width = null;
                $height = null;
                $fullPath = storage_path('app/public/' . $path);

                if ($type === 'image') {
                    [$width, $height] = @getimagesize($fullPath);
                }

                if ($type === 'video') {
                    try {
                        $ffprobe = \FFMpeg\FFProbe::create();
                        $videoStream = $ffprobe->streams($fullPath)->videos()->first();
                        $width = $videoStream->get('width');
                        $height = $videoStream->get('height');
                    } catch (\Exception $e) {
                        $width = null;
                        $height = null;
                    }
                }

                $event->media()->create([
                    'path' => $path,
                    'type' => $type,
                    'order' => $index,
                    'width' => $width,
                    'height' => $height,
                ]);
            }
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
            'ticket_instructions' => $request->ticket_instructions,
            'regular_price' => $request->regular_price,
            'regular_quantity' => $request->regular_quantity,
            'vip_price' => $request->vip_price,
            'vip_quantity' => $request->vip_quantity,
            'vvip_price' => $request->vvip_price,
            'vvip_quantity' => $request->vvip_quantity,
        ]);

        return redirect()->route('user.dashboard.events')->with('success', 'Event updated successfully!');
    }


    public function destroy(Event $event)
    {
        foreach ($event->media as $media) {
            Storage::disk('public')->delete($media->path);
            $media->delete();
        }
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

    public function comment(Request $request, Event $event)
    {
        if (!auth()->check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $validated = $request->validate([
            'comment' => 'required|string|max:500',
            'parent_id' => [
                'nullable',
                'integer',
                Rule::exists('event_comments', 'id')->where(
                    fn ($q) => $q->where('event_id', $event->id)->whereNull('parent_id')
                ),
            ],
        ]);
        $parentId = $validated['parent_id'] ?? null;

        $comment = $event->comments()->create([
            'user_id' => auth()->id(),
            'parent_id' => $parentId,
            'comment' => $request->comment,
        ]);
        $comment->load(['user', 'likes']);

        $user = auth()->user();

        return response()->json([
            'id' => $comment->id,
            'parent_id' => $comment->parent_id ? (int) $comment->parent_id : null,
            'comment' => $comment->comment,
            'comment_html' => ContentFormatter::linkify($comment->comment),
            'user_name' => $user->first_name . ' ' . $user->last_name,
            'user_photo' => $user->profile_pic
                ? asset('storage/' . $user->profile_pic)
                : asset('default.png'),
            'created_at' => 'just now',
            'likes_count' => 0,
            'html' => view('partials.event-comment-item', [
                'comment' => $comment,
                'isReply' => $comment->parent_id !== null,
            ])->render(),
        ]);
    }

    public function likeComment(\App\Models\EventComment $comment)
    {
        if (!auth()->check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $userId = auth()->id();
        $existing = $comment->likes()->where('user_id', $userId)->first();

        if ($existing) {
            $existing->delete();
            $liked = false;
        } else {
            $comment->likes()->create(['user_id' => $userId]);
            $liked = true;
        }

        return response()->json([
            'liked' => $liked,
            'likes_count' => $comment->likes()->count(),
        ]);
    }

    public function deleteComment(\App\Models\EventComment $comment)
    {
        if (!auth()->check() || $comment->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $comment->delete();
        return response()->json(['success' => true]);
    }
  






}
