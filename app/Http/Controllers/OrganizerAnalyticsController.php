<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\PromoCode;
use App\Models\TicketPurchase;
use App\Models\EventView;
use App\Models\Event;
use App\Models\Waitlist;
use App\Models\Organizer;

class OrganizerAnalyticsController extends Controller
{
    protected function authorizeOrganizerPromotionAccess(Organizer $organizer): void
    {
        $user = auth()->user();
        if (! $user) {
            abort(403);
        }

        // Primary organizer account may lack a members row; treat as privileged.
        if ((int) $organizer->user_id === (int) $user->id) {
            return;
        }

        if (! $organizer->hasRole($user, ['owner', 'editor', 'finance'])) {
            abort(403);
        }
    }

    public function index()
    {
        if (! auth()->check()) {
            return redirect()->route('show.login');
        }

        $organizer = Organizer::forManagingUser(auth()->user());

        if (! $organizer) {
            return redirect()->route('organizer.create');
        }

        $organizer->load('events');

        return view('dashboard.organizer-analytics', $this->asData($organizer));
    }

    public function indexForAdmin(Organizer $organizer)
    {
        return view('dashboard.organizer-analytics', array_merge(
            $this->asData($organizer),
            ['isAdminView' => true]
        ));
    }

    protected function asData(Organizer $organizer)
    {
        // 1. Overview Stats
        $monthlyRevenue = TicketPurchase::whereIn('event_id', $organizer->events->pluck('id'))
            ->where('status', 'paid')
            ->whereMonth('created_at', now()->month)
            ->sum('total');

        $totalTicketsSold = TicketPurchase::whereIn('event_id', $organizer->events->pluck('id'))
            ->where('status', 'paid')
            ->sum('quantity');

        $profileViews = EventView::whereIn('event_id', $organizer->events->pluck('id'))
            ->count();

        // 2. Ticket Type Distribution
        $ticketTypeData = TicketPurchase::whereIn('event_id', $organizer->events->pluck('id'))
            ->where('status', 'paid')
            ->select('ticket_type', DB::raw('SUM(quantity) as total_sold'))
            ->groupBy('ticket_type')
            ->get();

        // 3. Event Performance
        $eventPerformance = Event::where('organizer_id', $organizer->id)
            ->withCount(['ticketPurchases as tickets_sold' => function($query) {
                $query->where('status', 'paid')->select(DB::raw('SUM(quantity)'));
            }])
            ->withSum(['ticketPurchases as total_revenue' => function($query) {
                $query->where('status', 'paid');
            }], 'total')
            ->withCount('views')
            ->withCount('waitlist')
            ->get()
            ->map(function($event) {
                $event->conversion_rate = $event->views_count > 0 
                    ? ($event->tickets_sold / $event->views_count) * 100 
                    : 0;
                return $event;
            })
            ->sortByDesc('total_revenue');

        

        $waitlistTotal = Waitlist::whereIn('event_id', $organizer->events->pluck('id'))->count();

        // 5. Chart Data
        $chartData = [];
        $labels = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $labels[] = now()->subDays($i)->format('M d');
            $chartData[] = TicketPurchase::whereIn('event_id', $organizer->events->pluck('id'))
                ->where('status', 'paid')
                ->whereDate('created_at', $date)
                ->sum('total');
        }

        // 6. Recent Sales
        $recentSales = TicketPurchase::whereIn('event_id', $organizer->events->pluck('id'))
            ->where('status', 'paid')
            ->with(['event', 'user'])
            ->latest()
            ->take(5)
            ->get();

        return compact(
            'monthlyRevenue', 
            'totalTicketsSold', 
            'profileViews', 
            'chartData', 
            'labels', 
            'recentSales', 
            'organizer',
            'ticketTypeData',
            'eventPerformance',
            'waitlistTotal'
        );
    }

    public function storePromo(Request $request)
    {
        $organizer = Organizer::forManagingUser(auth()->user());
        if (! $organizer) {
            return redirect()->route('organizer.create');
        }

        $this->authorizeOrganizerPromotionAccess($organizer);

        $codeNorm = PromoCode::normalizedCode($request->input('code', ''));
        if (! $codeNorm) {
            return back()->withErrors(['code' => 'Enter a valid promo code.'])->withInput();
        }

        $request->validate([
            'discount_type' => 'required|in:fixed,percentage',
            'discount_amount' => 'required|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'expires_at' => 'nullable|date|after_or_equal:today',
        ]);

        if (PromoCode::where('code', $codeNorm)->exists()) {
            return back()->withErrors(['code' => 'This code is already taken.'])->withInput();
        }

        if ($request->discount_type === 'percentage' && (float) $request->discount_amount > 100) {
            return back()->withErrors(['discount_amount' => 'Percentage cannot exceed 100%.'])->withInput();
        }

        $expiresAt = $request->filled('expires_at')
            ? Carbon::parse($request->expires_at)->endOfDay()
            : null;

        PromoCode::create([
            'organizer_id' => $organizer->id,
            'code' => $codeNorm,
            'discount_type' => $request->discount_type,
            'discount_amount' => $request->discount_amount,
            'usage_limit' => $request->usage_limit,
            'expires_at' => $expiresAt,
            'status' => true,
        ]);

        return back()->with('success', 'Promo Code created successfully!');
    }

    public function togglePromo(PromoCode $promoCode)
    {
        $organizer = Organizer::forManagingUser(auth()->user());
        if (! $organizer || (int) $promoCode->organizer_id !== (int) $organizer->id) {
            abort(403);
        }

        $this->authorizeOrganizerPromotionAccess($organizer);

        $promoCode->update(['status' => ! $promoCode->status]);

        return response()->json(['success' => true, 'status' => $promoCode->status]);
    }
}



