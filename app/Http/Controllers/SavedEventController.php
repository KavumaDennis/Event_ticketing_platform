<?php
namespace App\Http\Controllers;

use App\Models\SavedEvent;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;

class SavedEventController extends Controller
{
    public function toggle($eventId)
    {
        $user = Auth::user();
        $event = Event::findOrFail($eventId);

        $saved = SavedEvent::where('user_id', $user->id)
            ->where('event_id', $event->id)
            ->first();

        if ($saved) {
            $saved->delete();
            $status = 'unsaved';
        } else {
            SavedEvent::create([
                'user_id' => $user->id,
                'event_id' => $event->id
            ]);
            $status = 'saved';
        }

        // Check if user has any remaining saved events
        $hasSavedEvents = SavedEvent::where('user_id', $user->id)->exists();

        return response()->json([
            'status' => $status,
            'has_saved_events' => $hasSavedEvents,
            'saved_count' => SavedEvent::where('user_id', $user->id)->count(),
        ]);
    }
}
