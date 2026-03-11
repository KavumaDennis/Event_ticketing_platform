<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Waitlist;
use Illuminate\Http\Request;

class WaitlistController extends Controller
{
    public function join(Request $request, Event $event)
    {
        if (!$event->isSoldOut()) {
            return back()->with('error', 'Tickets are still available!');
        }

        Waitlist::firstOrCreate([
            'event_id' => $event->id,
            'user_id' => auth()->id(),
        ]);

        return back()->with('success', "You've been added to the waitlist. We'll notify you if tickets become available!");
    }

    public function leave(Request $request, Event $event)
    {
        Waitlist::where('event_id', $event->id)
            ->where('user_id', auth()->id())
            ->delete();

        return back()->with('success', 'You have left the waitlist.');
    }
}
