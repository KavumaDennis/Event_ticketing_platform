<?php

namespace App\Http\Controllers;

use App\Models\Organizer;
use App\Models\User;
use App\Models\Event;
use App\Models\Trend;
use App\Models\TrendLike;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrganizersController extends Controller
{
  //
  public function index()
  {
    $organizers = Organizer::withCount('events')
      ->orderByDesc('events_count')
      ->take(4)
      ->get();

    // Get dynamic counts
    $totalUsers = User::count();
    $totalEvents = Event::count();
    $totalOrganizers = Organizer::count();

    // Top Categories (derived from events)
    $topCategories = Event::select('category', \DB::raw('count(*) as event_count'))
      ->groupBy('category')
      ->orderByDesc('event_count')
      ->take(6)
      ->get();

    // Generate array of random image numbers for carousel (5 images)
    $carouselImages = ['img1.jpg', 'img2.jpg', 'img3.jpg', 'img4.jpg', 'img5.jpg'];

    return view('organizers', [
      'organizers' => $organizers,
      'totalUsers' => $totalUsers,
      'totalEvents' => $totalEvents,
      'totalOrganizers' => $totalOrganizers,
      'carouselImages' => $carouselImages,
      'topCategories' => $topCategories,
    ]);
  }


  public function organizer_create()
  {
    // Get top organizers with event counts (similar to organizers page)
    $organizers = Organizer::withCount('events')
      ->orderByDesc('events_count')
      ->take(4)
      ->get();

    // Get latest trends with likes count
    $trends = Trend::with('user')
      ->withCount('likes')
      ->latest()
      ->take(4)
      ->get();

    // Check if user has liked each trend
    if (Auth::check()) {
      $userLikedTrendIds = TrendLike::where('user_id', Auth::id())
        ->pluck('trend_id')
        ->toArray();

      foreach ($trends as $trend) {
        $trend->is_liked = in_array($trend->id, $userLikedTrendIds);
      }
    }

    return view('organizer_create', [
      'organizers' => $organizers,
      'trends' => $trends,
    ]);
  }

  public function organizer_signup()
  {
    return view('organizer_signup');
  }

  public function show($id)
  {
    $organizer = Organizer::with(['events', 'followers'])->findOrFail($id);
    return view('organizerDetails', ['organizer' => $organizer]);
  }

  public function store(Request $request)
  {
    $validated = $request->validate([
      'business_name' => 'required|string|max:255',
      'business_email' => 'required|email|unique:organizers,business_email',
      'business_website' => 'nullable|string|max:255',
      'organizer_image' => 'nullable|image|max:2048',
    ]);

    $path = null;
    if ($request->hasFile('organizer_image')) {
      $path = $request->file('organizer_image')->store('organizers', 'public');
    }

    Organizer::create([
      'user_id' => Auth::id(),
      'business_name' => $validated['business_name'],
      'business_email' => $validated['business_email'],
      'business_website' => $validated['business_website'] ?? null,
      'organizer_image' => $path,
    ]);

    return redirect()->route('home')->with('success', 'Contragulations your now an organizers.');
  }



}
