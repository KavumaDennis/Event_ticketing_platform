<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Trend;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function show(string $tag, Request $request)
    {
        $needle = '#' . $tag;

        $events = Event::where('event_name', 'like', "%{$tag}%")
            ->orWhere('description', 'like', "%{$needle}%")
            ->latest()
            ->with('organizer')
            ->take(20)
            ->get();

        $trends = Trend::where('title', 'like', "%{$tag}%")
            ->orWhere('body', 'like', "%{$needle}%")
            ->latest()
            ->with('user')
            ->take(20)
            ->get();

        return view('tag', [
            'tag' => $tag,
            'events' => $events,
            'trends' => $trends,
        ]);
    }
}
