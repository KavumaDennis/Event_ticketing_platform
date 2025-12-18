<?php

namespace App\Http\Controllers;

use App\Models\Organizer;
use Illuminate\Http\Request;

class OrganizerFollowController extends Controller
{
    //
    public function toggleFollow(Organizer $organizer)
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['error' => 'You must be logged in'], 401);
        }

        $alreadyFollowing = $organizer->followers()
            ->where('user_id', $user->id)
            ->exists();

        if ($alreadyFollowing) {
            // Unfollow
            $organizer->followers()->detach($user->id);
            return response()->json(['status' => 'unfollowed']);
        }

        // Follow
        $organizer->followers()->attach($user->id);
        return response()->json(['status' => 'followed']);
    }

}
