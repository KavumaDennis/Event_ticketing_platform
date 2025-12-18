<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserFollow;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class UserController extends Controller
{
    //

    public function show(User $user)
    {
        $authUserId = auth()->id();

        // Load user trends with likes count
        $trends = $user->trends()
            ->withCount('likes')
            ->latest()
            ->paginate(8);

        // Followers & Following
        $followers = $user->followers()->with('follower')->get(); // people following this user
        $following = $user->following()->with('following')->get(); // people this user is following

        return view('userProfile', compact('user', 'trends', 'followers', 'following', 'authUserId'));
    }





    public function follow(User $user)
    {
        $authUser = Auth::user();

        if ($authUser->id == $user->id) {
            return back()->with('error', 'You cannot follow yourself.');
        }

        UserFollow::firstOrCreate([
            'follower_id' => $authUser->id,
            'following_id' => $user->id,
        ]);

        return back()->with('success', 'You are now following ' . $user->first_name);
    }

    public function unfollow(User $user)
    {
        $authUser = Auth::user();

        UserFollow::where('follower_id', $authUser->id)
            ->where('following_id', $user->id)
            ->delete();

        return back()->with('success', 'You unfollowed ' . $user->first_name);
    }

}
