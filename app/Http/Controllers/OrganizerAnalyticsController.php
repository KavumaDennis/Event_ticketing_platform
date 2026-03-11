<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\TicketPurchase;
use App\Models\EventView;
use App\Models\Event;
use App\Models\Waitlist;
use App\Models\Referral;

class OrganizerAnalyticsController extends Controller
{
    public function index()
    {
        $organizer = auth()->user()->organizer;

        if (!$organizer) {
            return redirect()->route('organizer.create');
        }

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

        // 4. Referral & Community Impact
        $referralRevenue = TicketPurchase::whereIn('event_id', $organizer->events->pluck('id'))
            ->where('status', 'paid')
            ->whereHas('user', function($q) {
                $q->whereHas('referredBy');
            })
            ->sum('total');

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
            'referralRevenue',
            'waitlistTotal'
        );
    }

    public function storePromo(Request $request) 
    {
        $request->validate([
            'code' => 'required|unique:promo_codes,code',
            'discount_type' => 'required|in:fixed,percentage',
            'discount_amount' => 'required|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'expires_at' => 'nullable|date|after:today',
        ]);

        $organizer = auth()->user()->organizer;

        \App\Models\PromoCode::create([
            'organizer_id' => $organizer->id,
            'code' => strtoupper($request->code),
            'discount_type' => $request->discount_type,
            'discount_amount' => $request->discount_amount,
            'usage_limit' => $request->usage_limit,
            'expires_at' => $request->expires_at,
            'status' => true
        ]);

        return back()->with('success', 'Promo Code created successfully!');
    }

    public function togglePromo(\App\Models\PromoCode $promoCode)
    {
        if ($promoCode->organizer_id !== auth()->user()->organizer->id) {
            abort(403);
        }

        $promoCode->update(['status' => !$promoCode->status]);
        
        return response()->json(['success' => true, 'status' => $promoCode->status]);
    }
}
