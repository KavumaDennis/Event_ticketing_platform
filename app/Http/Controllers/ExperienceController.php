<?php

namespace App\Http\Controllers;

use App\Models\Experience;
use App\Models\ExperienceView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ExperienceController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'media' => 'required|file|mimes:jpg,jpeg,png,webp,mp4,webm,mov|max:20480',
            'caption' => 'nullable|string|max:255',
        ]);

        $file = $request->file('media');
        $type = str_starts_with($file->getMimeType(), 'video') ? 'video' : 'image';
        $path = $file->store('experiences', 'public');

        Experience::create([
            'user_id' => $request->user()->id,
            'media_path' => $path,
            'media_type' => $type,
            'caption' => $request->caption,
            'expires_at' => now()->addDay(),
        ]);

        return back()->with('success', 'Experience added successfully!');
    }

    public function destroy(Experience $experience)
    {
        if ($experience->user_id !== auth()->id()) {
            abort(403);
        }

        if ($experience->media_path && Storage::disk('public')->exists($experience->media_path)) {
            Storage::disk('public')->delete($experience->media_path);
        }

        $experience->delete();

        return back()->with('success', 'Experience deleted successfully!');
    }

    public function view(Experience $experience, Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        if ($experience->expires_at && $experience->expires_at->isPast()) {
            return response()->json(['message' => 'Experience expired'], 410);
        }

        if ($experience->user_id === $user->id) {
            return response()->json(['message' => 'Skipped self view'], 200);
        }

        ExperienceView::updateOrCreate(
            [
                'experience_id' => $experience->id,
                'user_id' => $user->id,
            ],
            [
                'viewed_at' => now(),
            ]
        );

        return response()->json(['message' => 'View recorded'], 200);
    }
}
