<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    //

 public function toggle($eventId)
{
    if (!Auth::check()) {
        return response()->json(['error' => 'Unauthenticated'], 401);
    }

    $event = Event::findOrFail($eventId);
    $user = Auth::user();

    $like = Like::where('user_id', $user->id)
        ->where('event_id', $event->id)
        ->first();

    if ($like) {
        $like->delete();
        $status = 'unliked';
    } else {
        Like::create([
            'user_id' => $user->id,
            'event_id' => $event->id
        ]);
        $status = 'liked';
    }

    // Refresh the event to get updated likes count
    $event->refresh();
    $likesCount = $event->likes()->count();

    return response()->json([
        'status' => $status,
        'likes_count' => $likesCount,
    ]);
}

}
