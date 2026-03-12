<?php

namespace App\Http\Controllers;

use App\Models\Organizer;
use App\Models\OrganizerMember;
use App\Models\User;
use Illuminate\Http\Request;

class OrganizerTeamController extends Controller
{
    public function add(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'role' => 'required|in:owner,editor,finance,support',
        ]);

        $user = $request->user();
        $organizer = Organizer::whereHas('members', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->first();

        if (!$organizer || !$organizer->hasRole($user, ['owner'])) {
            abort(403);
        }

        $memberUser = User::where('email', $request->email)->first();
        if (!$memberUser) {
            return back()->withErrors(['email' => 'User with that email does not exist.']);
        }

        $organizer->members()->updateOrCreate(
            ['user_id' => $memberUser->id],
            ['role' => $request->role]
        );

        $organizer->load('members.user');

        return back()->with('success', 'Team member added/updated.');
    }

    public function updateRole(Request $request, OrganizerMember $member)
    {
        $request->validate([
            'role' => 'required|in:owner,editor,finance,support',
        ]);

        $user = $request->user();
        $organizer = $member->organizer;

        if (!$organizer || !$organizer->hasRole($user, ['owner'])) {
            abort(403);
        }

        $member->role = $request->role;
        $member->save();

        return back()->with('success', 'Role updated.');
    }

    public function remove(OrganizerMember $member, Request $request)
    {
        $user = $request->user();
        $organizer = $member->organizer;

        if (!$organizer || !$organizer->hasRole($user, ['owner'])) {
            abort(403);
        }

        if ($member->role === 'owner') {
            $ownerCount = $organizer->members()->where('role', 'owner')->count();
            if ($ownerCount <= 1) {
                return back()->withErrors(['team' => 'You must keep at least one owner.']);
            }
        }

        $member->delete();

        return back()->with('success', 'Member removed.');
    }
}
